<?php


namespace App\Controller\Turno\Informe\Operacion;


use App\Controller\Estructura\FuncionesController;

use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Formato\Turno\informeProgramacion;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Programacion extends AbstractController
{
    /**
     * @Route("/turno/informe/operacion/programacion/lista", name="turno_informe_operacion_programacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $dateFecha = (new \DateTime('now'));
        $arrDiaSemana = "";
        $form = $this->createFormBuilder()
            ->add('txtAnio', TextType::class, ['required' => false, 'data' =>  $dateFecha->format('Y'), 'attr' => ['class' => 'form-control']])
            ->add('txtMes', TextType::class, ['required' => false, 'data' => $dateFecha->format('m'), 'attr' => ['class' => 'form-control']])
            ->add('txtEmpleado', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('codigoClienteFk', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('codigoPuesto', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('nuemeroPedido', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->add('btnImprimir', SubmitType::class, ['label' => 'Imprimir', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurProgramacionAnio', $form->get('txtAnio')->getData());
                $session->set('filtroTurProgramacionMes', $form->get('txtMes')->getData());
                $session->set('filtroRhuEmpleadoCodigoEmpleado', $form->get('txtEmpleado')->getData());
                $session->set('filtroTurCodigoCliente', $form->get('codigoClienteFk')->getData());
                $session->set('filtroTurProgramacionCodigoPuesto', $form->get('codigoPuesto')->getData());
                $session->set('filtroTurProgramacionNuemeroPedido', $form->get('nuemeroPedido')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arProgramaciones = $em->getRepository(TurProgramacion::class)->programaciones();
                $this->exportarExcelPersonalizado($arProgramaciones);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $programacion = $em->getRepository(TurProgramacion::class)->programaciones();
                $formatoProgramacion = new informeProgramacion();
                $formatoProgramacion->Generar($em, $programacion);
            }
        }
        $arrDiaSemana = FuncionesController::diasMes($dateFecha, $em->getRepository(TurFestivo::class)->festivos($dateFecha->format('Y-m-') . '01', $dateFecha->format('Y-m-t')));
        $arProgramaciones = $paginator->paginate($em->getRepository(TurProgramacion::class)->programaciones(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/operacion/programacion/programacion.html.twig', [
            'arProgramaciones' => $arProgramaciones,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()
        ]);
    }

    public function exportarExcelPersonalizado($arProgramaciones)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arProgramaciones) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('programacion');
            $j = 0;
            $arrColumnas=['ID', 'AÑO','MES', 'COD', 'CLIENTE', 'COD', 'PUESTO','COD', 'IDENTIFICACION','EMPLEADO','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','H', 'HD', 'HN','PD',];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arProgramaciones as $arProgramacion) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arProgramacion['codigoProgramacionPk']);
                $hoja->setCellValue('B' . $j, $arProgramacion['anio']);
                $hoja->setCellValue('C' . $j, $arProgramacion['mes']);
                $hoja->setCellValue('D' . $j, $arProgramacion['codigoClienteFk']);
                $hoja->setCellValue('E' . $j, $arProgramacion['cliente']);
                $hoja->setCellValue('F' . $j, $arProgramacion['codigoPuestoFk']);
                $hoja->setCellValue('G' . $j, $arProgramacion['puestoNombre']);
                $hoja->setCellValue('H' . $j, $arProgramacion['codigoEmpleadoFk']);
                $hoja->setCellValue('I' . $j, $arProgramacion['numeroIdentificacion']);
                $hoja->setCellValue('J' . $j, $arProgramacion['empleadoNombreCorto']);
                $hoja->setCellValue('K' . $j, $arProgramacion['dia1']);
                $hoja->setCellValue('L' . $j, $arProgramacion['dia2']);
                $hoja->setCellValue('M' . $j, $arProgramacion['dia3']);
                $hoja->setCellValue('N' . $j, $arProgramacion['dia4']);
                $hoja->setCellValue('O' . $j, $arProgramacion['dia5']);
                $hoja->setCellValue('P' . $j, $arProgramacion['dia6']);
                $hoja->setCellValue('Q' . $j, $arProgramacion['dia7']);
                $hoja->setCellValue('R' . $j, $arProgramacion['dia8']);
                $hoja->setCellValue('S' . $j, $arProgramacion['dia9']);
                $hoja->setCellValue('T' . $j, $arProgramacion['dia10']);
                $hoja->setCellValue('U' . $j, $arProgramacion['dia11']);
                $hoja->setCellValue('V' . $j, $arProgramacion['dia12']);
                $hoja->setCellValue('W' . $j, $arProgramacion['dia13']);
                $hoja->setCellValue('X' . $j, $arProgramacion['dia14']);
                $hoja->setCellValue('Y' . $j, $arProgramacion['dia15']);
                $hoja->setCellValue('Z' . $j, $arProgramacion['dia16']);
                $hoja->setCellValue('AA' . $j, $arProgramacion['dia17']);
                $hoja->setCellValue('AB' . $j, $arProgramacion['dia18']);
                $hoja->setCellValue('AC' . $j, $arProgramacion['dia19']);
                $hoja->setCellValue('AD' . $j, $arProgramacion['dia20']);
                $hoja->setCellValue('AE' . $j, $arProgramacion['dia21']);
                $hoja->setCellValue('AF' . $j, $arProgramacion['dia22']);
                $hoja->setCellValue('AG' . $j, $arProgramacion['dia23']);
                $hoja->setCellValue('AH' . $j, $arProgramacion['dia24']);
                $hoja->setCellValue('AI' . $j, $arProgramacion['dia25']);
                $hoja->setCellValue('AJ' . $j, $arProgramacion['dia26']);
                $hoja->setCellValue('AK' . $j, $arProgramacion['dia27']);
                $hoja->setCellValue('AL' . $j, $arProgramacion['dia28']);
                $hoja->setCellValue('AM' . $j, $arProgramacion['dia29']);
                $hoja->setCellValue('AN' . $j, $arProgramacion['dia30']);
                $hoja->setCellValue('AO' . $j, $arProgramacion['dia31']);
                $hoja->setCellValue('AP' . $j, $arProgramacion['horas']);
                $hoja->setCellValue('AQ' . $j, $arProgramacion['horasDiurnas']);
                $hoja->setCellValue('AR' . $j, $arProgramacion['horasNocturnas']);
                $hoja->setCellValue('AS' . $j, $arProgramacion['codigoPedidoDetalleFk']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=programacion.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}