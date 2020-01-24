<?php

namespace App\Controller\Turno\Movimiento\Financiero;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurCierre;
use App\Entity\Turno\TurCierreDetalle;
use App\Entity\Turno\TurCierreTipo;
use App\Entity\Turno\TurCostoEmpleado;
use App\Entity\Turno\TurCostoServicio;
use App\Entity\Turno\TurDistribucionEmpleado;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\CierreType;
use App\Form\Type\Turno\CierreDetalleType;
use App\Formato\Infinancierorio\Cierre;
use App\General\General;
use App\Repository\Turno\TurDistribucionEmpleadoRepository;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CierreController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "TurCierre";

    protected $clase = TurCierre::class;
    protected $claseNombre = "TurCierre";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Financiero";
    protected $nombre = "Cierre";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/financiero/cierre/lista", name="turno_movimiento_financiero_cierre_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroTurCierre')
        ];
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAutorizado'] ])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAprobado'] ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAnulado'] ])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TurCierre::class)->lista($raw), "Cierres");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurCierre::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_financiero_cierre_lista'));
            }
        }
        $arCierres = $paginator->paginate($em->getRepository(TurCierre::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('turno/movimiento/financiero/cierre/lista.html.twig', [
            'arCierres' => $arCierres,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/movimiento/financiero/cierre/nuevo/{id}", name="turno_movimiento_financiero_cierre_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierre = new TurCierre();
        if ($id != '0') {
            $arCierre = $em->getRepository(TurCierre::class)->find($id);
            if (!$arCierre) {
                return $this->redirect($this->generateUrl('turno_movimiento_financiero_cierre_lista'));
            }
        }
        $form = $this->createForm(CierreType::class, $arCierre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arCierre->setUsuario($this->getUser()->getUserName());
                }
                $em->persist($arCierre);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_movimiento_financiero_cierre_detalle', ['id' => $arCierre->getCodigoCierrePk()]));
            }
        }
        return $this->render('turno/movimiento/financiero/cierre/nuevo.html.twig', [
            'arCierre' => $arCierre,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/turno/movimiento/financiero/cierre/detalle/{id}", name="turno_movimiento_financiero_cierre_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCierre = $em->getRepository(TurCierre::class)->find($id);
        $form = Estandares::botonera($arCierre->getEstadoAutorizado(), $arCierre->getEstadoAprobado(), $arCierre->getEstadoAnulado());
		$form->add ('btnExcelDistribucion', SubmitType::class, [ 'label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']]);
		$form->add ('btnExcelEmpleado', SubmitType::class, [ 'label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']]);
		$form->add ('btnExcelServicio', SubmitType::class, [ 'label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']]);

        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arCierre->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = false;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arCierre->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TurCierre::class)->autorizar($arCierre);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurCierre::class)->desautorizar($arCierre);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TurCierre::class)->aprobar($arCierre);
            }
            if ($form->get('btnExcelDistribucion')->isClicked()){
	            $arMovimientos = $em->getRepository(TurDistribucionEmpleado::class)->lista($id);
	            $this->exportarExcelPersonalizadoDistribucion($arMovimientos);
            }
	        if ($form->get('btnExcelEmpleado')->isClicked()){
		        $arMovimientos = $em->getRepository(TurCostoEmpleado::class)->lista($id);
		        $this->exportarExcelPersonalizadoEmpleado($arMovimientos);

	        }
	        if ($form->get('btnExcelServicio')->isClicked()){
		        $arMovimientos = $em->getRepository(TurCostoServicio::class)->lista($id);
		        $this->exportarExcelPersonalizadoServicio($arMovimientos);
	        }

            return $this->redirect($this->generateUrl('turno_movimiento_financiero_cierre_detalle', ['id' => $id]));
        }
        $arDistribucionEmpleados = $paginator->paginate($em->getRepository(TurDistribucionEmpleado::class)->lista($id), $request->query->getInt('page', 1), 1000);
        $arCostoEmpleados = $paginator->paginate($em->getRepository(TurCostoEmpleado::class)->lista($id), $request->query->getInt('page', 1), 1000);
        $arCostoServicios = $paginator->paginate($em->getRepository(TurCostoServicio::class)->lista($id), $request->query->getInt('page', 1), 1000);
        return $this->render('turno/movimiento/financiero/cierre/detalle.html.twig', [
            'form' => $form->createView(),
            'arDistribucionEmpleados' => $arDistribucionEmpleados,
            'arCostoEmpleados' => $arCostoEmpleados,
            'arCostoServicios' => $arCostoServicios,
            'arCierre' => $arCierre
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData()
        ];
        $session->set('filtroTurCierre', $filtro);
        return $filtro;

    }


	public function exportarExcelPersonalizadoDistribucion($arMovimientos)
	{
		set_time_limit(0);
		ini_set("memory_limit", -1);
		if ($arMovimientos) {
			$libro = new Spreadsheet();
			$hoja = $libro->getActiveSheet();
			$hoja->setTitle('Distribucion');
			$j=0;
			$arrColumnas=[ 'ID',
				'AÑO',
				'MES',
				'CODIGO EMPLEADO',
				'DOCUMENTO',
				'EMPLEADO',
				'CODIGO CENTRO COSTO',
				'CENTRO COSTO',
				'PAR'
			];
			$j=2;
			for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
				$hoja->getColumnDimension($i)->setAutoSize(true);
				$hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
				$hoja->getStyle(1)->getFont()->setBold(true);
				$hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
				$j++;
			}
			$j = 2;
			foreach ($arMovimientos as $arMovimiento) {
				$hoja->getStyle ($j)->getFont ()->setName ('Arial')->setSize (9);
				$hoja->setCellValue ('A' . $j, $arMovimiento['codigoDistribucionEmpleadoPk']);
				$hoja->setCellValue ('B' . $j, $arMovimiento['anio']);
				$hoja->setCellValue ('C' . $j, $arMovimiento['mes']);
				$hoja->setCellValue ('D' . $j, $arMovimiento['codigoEmpleadoFk']);
				$hoja->setCellValue ('E' . $j, $arMovimiento['codigoCentroCostoFk']);
				$hoja->setCellValue ('F' . $j, $arMovimiento['participacion']);
				$hoja->setCellValue ('G' . $j, $arMovimiento['centroCostoNombre']);
				$hoja->setCellValue ('H' . $j, $arMovimiento['empleadoNumeroIdentificacion']);
				$hoja->setCellValue ('I' . $j, $arMovimiento['empleadoNombreCorto']);
				$j++;
			}

			$libro->setActiveSheetIndex(0);
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=Distribucion.xls");
			header('Cache-Control: max-age=0');
			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
			$writer->save('php://output');
		}
	}

	public function exportarExcelPersonalizadoEmpleado($arMovimientos)
	{
		set_time_limit(0);
		ini_set("memory_limit", -1);
		if ($arMovimientos) {
			$libro = new Spreadsheet();
			$hoja = $libro->getActiveSheet();
			$hoja->setTitle('Empleado');
			$j=0;
			$arrColumnas=[
				'ID',
				'AÑO',
				'MES',
				'CODIGO EMPLEADO',
				'DOCUMENTO',
				'EMPLEADO',
				'NOMINA',
				'PROVISION',
				'APORTE',
				'TOTAL',
			];
			for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
				$hoja->getColumnDimension($i)->setAutoSize(true);
				$hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
				$hoja->getStyle(1)->getFont()->setBold(true);
				$hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
				$j++;
			}
			$j = 2;
			foreach ($arMovimientos as $arMovimiento) {
				$hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
				$hoja->setCellValue ('A' . $j, $arMovimiento['codigoCostoEmpleadoPk']);
				$hoja->setCellValue ('B' . $j, $arMovimiento['anio']);
				$hoja->setCellValue ('C' . $j, $arMovimiento['mes']);
				$hoja->setCellValue ('D' . $j, $arMovimiento['codigoEmpleadoFk']);
				$hoja->setCellValue ('E' . $j, $arMovimiento['empleadoNumeroIdentificacion']);
				$hoja->setCellValue ('F' . $j, $arMovimiento['empleadoNombreCorto']);
				$hoja->setCellValue ('G' . $j, $arMovimiento['vrNomina']);
				$hoja->setCellValue ('H' . $j, $arMovimiento['vrProvision']);
				$hoja->setCellValue ('I' . $j, $arMovimiento['vrAporte']);
				$hoja->setCellValue ('J' . $j, $arMovimiento['vrTotal']);
				$j++;

			}
			$libro->setActiveSheetIndex(0);
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=Empleados.xls");
			header('Cache-Control: max-age=0');
			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
			$writer->save('php://output');
		}

	}

	public function exportarExcelPersonalizadoServicio($arMovimientos)
	{
		set_time_limit(0);
		ini_set("memory_limit", -1);
		if ($arMovimientos) {
			$libro = new Spreadsheet();
			$hoja = $libro->getActiveSheet();
			$hoja->setTitle('Movimientos');
			$j=0;
			$arrColumnas=[
				'ID',
				'CLIENTE',
				'PUESTO',
				'COSTO',
				'TOTAL',
			];
			for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
				$hoja->getColumnDimension($i)->setAutoSize(true);
				$hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
				$hoja->getStyle(1)->getFont()->setBold(true);
				$hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
				$j++;
			}
			$j = 2;
			foreach ($arMovimientos as $arMovimiento) {
				$hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
				$hoja->setCellValue ('A' . $j, $arMovimiento['codigoCostoServicioPk']);
				$hoja->setCellValue ('B' . $j, $arMovimiento['clienteNombreCorto']);
				$hoja->setCellValue ('C' . $j, $arMovimiento['puestoNombre']);
				$hoja->setCellValue ('D' . $j, $arMovimiento['vrCosto']);
				$hoja->setCellValue ('E' . $j, $arMovimiento['vrTotal']);
				$j++;
			}
			$libro->setActiveSheetIndex(0);
			header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=servicios.xls");
			header('Cache-Control: max-age=0');
			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
			$writer->save('php://output');
		}

	}

}
