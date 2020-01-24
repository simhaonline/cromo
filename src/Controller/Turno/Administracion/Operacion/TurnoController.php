<?php


namespace App\Controller\Turno\Administracion\Operacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Turno\TurTurno;
use App\Form\Type\Turno\TurnoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class TurnoController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "TurTurno";

    protected $clase = TurTurno::class;
	protected $claseFormulario = TurnoType::class;
	protected $claseNombre = "TurTurno";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
	protected $grupo = "Operacion";
	protected $nombre = "Turno";

    /**
     * @Route("/turno/administracion/operacion/turno/lista", name="turno_administracion_operacion_turno_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtTurno', TextType::class, ['required' => false, 'data' => $session->get('filtroTurTurnoCodigoTurno')])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroTurTurnoNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurTurnoCodigoTurno', $form->get('txtTurno')->getData());
                $session->set('filtroTurTurnoNombre', $form->get('txtNombre')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurTurno::class, $arrSeleccionados);
				return $this->redirect($this->generateUrl('turno_administracion_operacion_turno_lista'));
			}
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arTurnos = $this->getDoctrine()->getRepository(TurTurno::class)->lista();
                $this->exportarExcelPersonalizado($arTurnos);

            }
        }
        $arTurnos = $paginator->paginate($em->getRepository(TurTurno::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('turno/administracion/operacion/turno/lista.html.twig', [
            'arTurnos' => $arTurnos,
            'form' => $form->createView()
        ]);
	}

	/**
     * @Route("/turno/administracion/operacion/turno/nuevo/{id}", name="turno_administracion_operacion_turno_nuevo")
     */
   public function nuevo(Request $request, $id)
   {
       $em = $this->getDoctrine()->getManager();
       $arTurno = new TurTurno();

       if ($id != "") {
           $arTurno = $em->getRepository(TurTurno::class)->find($id);
       }
       $form = $this->createForm(turnoType::class, $arTurno);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           if ($form->get('guardar')->isClicked()) {
               $arTurno = $form->getData();
               $em->persist($arTurno);
               $em->flush();
               return $this->redirect($this->generateUrl('turno_administracion_operacion_turno_detalle', ['id' => $arTurno->getCodigoTurnoPk()]));
           }
       }
       return $this->render('turno/administracion/operacion/turno/nuevo.html.twig', [
           'form' => $form->createView(),
           'arTurno' => $arTurno
       ]);
   }

  /**
   * @Route("/turno/administracion/operacion/turno/detalle/{id}", name="turno_administracion_operacion_turno_detalle")
   */
	public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != "") {
            $arTurno = $em->getRepository(TurTurno::class)->find($id);
            if (!$arTurno) {
                return $this->redirect($this->generateUrl('turno_administracion_operacion_turno_lista'));
            }
        }
        $arTurno = $em->getRepository(TurTurno::class)->find($id);
        return $this->render('turno/administracion/operacion/turno/detalle.html.twig', [
            'arTurno' => $arTurno
        ]);
    }

    public function exportarExcelPersonalizado($arTurnos)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arTurnos) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Movimientos');
            $j = 0;
            $arrColumnas = ['ID','NOMBRE','HORAS','DESDE','HASTA','HORASDIURNAS','HORASNOCTURNAS','NOVEDAD','DESCANSO',
                'INCAPACIDAD','LICENCIA','VACACION','INGRESO','RETIRO','INDUCCION','AUSENTISMO','COMPLEMENTARIO',
                'DIA','NOCHE'];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arTurnos as $arTurno) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arTurno['codigoTurnoPk']);
                $hoja->setCellValue('B' . $j, $arTurno['nombre']);
                $hoja->setCellValue('C' . $j, $arTurno['horas']);
                $hoja->setCellValue('D' . $j, $arTurno['horaDesde']->format('H:i'));
                $hoja->setCellValue('E' . $j, $arTurno['horaHasta']->format('H:i'));
                $hoja->setCellValue('F' . $j, $arTurno['horasDiurnas']);
                $hoja->setCellValue('G' . $j, $arTurno['horasNocturnas']);
                $hoja->setCellValue('H' . $j, $arTurno['novedad']);
                $hoja->setCellValue('I' . $j, $arTurno['descanso']);
                $hoja->setCellValue('J' . $j, $arTurno['incapacidad']);
                $hoja->setCellValue('K' . $j, $arTurno['licencia']);
                $hoja->setCellValue('L' . $j, $arTurno['vacacion']);
                $hoja->setCellValue('M' . $j, $arTurno['ingreso']);
                $hoja->setCellValue('N' . $j, $arTurno['retiro']);
                $hoja->setCellValue('O' . $j, $arTurno['induccion']);
                $hoja->setCellValue('P' . $j, $arTurno['ausentismo']);
                $hoja->setCellValue('Q' . $j, $arTurno['complementario']);
                $hoja->setCellValue('R' . $j, $arTurno['dia']);
                $hoja->setCellValue('S' . $j, $arTurno['noche']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=Turnos.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}