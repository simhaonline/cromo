<?php


namespace App\Controller\Financiero\Administracion\Comprobante;


use App\Controller\MaestroController;
use App\Entity\Cartera\CarAnticipoTipo;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarIngresoConcepto;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Crm\CrmVisitaMotivo;
use App\Entity\Financiero\FinComprobante;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Form\Type\Cartera\AnticipoTipoType;
use App\Form\Type\Cartera\DescuentoConceptoType;
use App\Form\Type\Cartera\IngresoConceptoType;
use App\Form\Type\Cartera\ReciboTipoType;
use App\Form\Type\Crm\VisitaMotivoType;
use App\Form\Type\Financiero\ComprobanteType;
use App\Form\Type\RecursoHumano\ExamenItemType;
use App\Form\Type\RecursoHumano\ExamenTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ComprobanteController extends  MaestroController
{

    public $tipo = "administracion";
    public $modelo = "FinComprobante";
    public $entidad = FinComprobante::class;

    /**
     * @Route("/financiero/administracion/comprobante/comprobante/lista", name="financiero_administracion_comprobante_comprobante_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigo', TextType::class, array('required' => false ))
            ->add('nombre', TextType::class, array('required' => false ))
            ->add('consecutivo', NumberType::class, array('required' => false ))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw=['limiteRegistros' => $form->get('limiteRegistros')->getData()];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->filtros($form);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository($this->entidad)-> eliminar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistros = $em->getRepository($this->entidad)->lista($raw);
                $this->excelLista($arRegistros, "Comprobante");
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('financiero/administracion/comprobante/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     *  @Route("/financiero/administracion/comprobante/comprobante/nuevo/{id}", name="financiero_administracion_comprobante_comprobante_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new $this->entidad;
        if ($id != 0 ||  $id != "") {
            $arRegistro = $em->getRepository($this->entidad)->find($id);
        }
        $form = $this->createForm( ComprobanteType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arRegistro = $form->getData();
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_administracion_comprobante_comprobante_detalle', array('id' => $arRegistro->getcodigoComprobantePk())));
            }
        }
        return $this->render('financiero/administracion/comprobante/nuevo.html.twig', [
            'form' => $form->createView(),
            'arRegistro' => $arRegistro
        ]);
    }

    /**
     *  @Route("/financiero/administracion/comprobante/comprobante/detalle/{id}", name="financiero_administracion_comprobante_comprobante_detalle")

     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->entidad)->find($id);
        return $this->render('financiero/administracion/comprobante/detalle.html.twig', [

            'arRegistro' => $arRegistro,
        ]);
    }

    public function filtros($form)
    {
        $filtro = [
            'codigo' => $form->get('codigo')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'consecutivo' => $form->get('consecutivo')->getData(),
        ];
        return $filtro;

    }

    public function excelLista($arRegistros, $nombre)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arRegistros) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Items');
            $j = 0;
            $arrColumnas=['ID', 'NOMBRE', 'CONSECUTIVO' ];

            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arRegistros as $arRegistro) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arRegistro['codigoComprobantePk']);
                $hoja->setCellValue('B' . $j, $arRegistro['nombre']);
                $hoja->setCellValue('B' . $j, $arRegistro['consecutivo']);

                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename={$nombre}.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }else{
            Mensajes::error("No existen registros para exportar");
        }
    }
}