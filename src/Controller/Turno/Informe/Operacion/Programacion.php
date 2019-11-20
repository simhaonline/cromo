<?php


namespace App\Controller\Turno\Informe\Operacion;


use App\Controller\Estructura\FuncionesController;

use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
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
                $arProgramaciones = $em->getRepository(TurProgramacion::class)->programaciones()->getResult();
                $this->exportarExcelPersonalizado($arProgramaciones);
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
            $hoja->setTitle('Programaciones');
            $j = 0;
            $arrColumnas=[ 'cliente', 'puesto','programacion','empleado','numero identificacion','nombre empleado','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','H','P.D',];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setBold(true);;
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arProgramaciones as $arProgramacion) {
                $hoja->setCellValue('A' . $j, $arProgramacion['cliente']);
                $hoja->setCellValue('B' . $j, $arProgramacion['puestoNombre']);
                $hoja->setCellValue('C' . $j, $arProgramacion['codigoProgramacionPk']);
                $hoja->setCellValue('D' . $j, $arProgramacion['codigoEmpleadoFk']);
                $hoja->setCellValue('E' . $j, $arProgramacion['numeroIdentificacion']);
                $hoja->setCellValue('F' . $j, $arProgramacion['empleadoNombreCorto']);
                $hoja->setCellValue('G' . $j, $arProgramacion['dia1']);
                $hoja->setCellValue('H' . $j, $arProgramacion['dia2']);
                $hoja->setCellValue('I' . $j, $arProgramacion['dia3']);
                $hoja->setCellValue('J' . $j, $arProgramacion['dia4']);
                $hoja->setCellValue('K' . $j, $arProgramacion['dia5']);
                $hoja->setCellValue('L' . $j, $arProgramacion['dia6']);
                $hoja->setCellValue('M' . $j, $arProgramacion['dia7']);
                $hoja->setCellValue('N' . $j, $arProgramacion['dia8']);
                $hoja->setCellValue('O' . $j, $arProgramacion['dia9']);
                $hoja->setCellValue('P' . $j, $arProgramacion['dia10']);
                $hoja->setCellValue('Q' . $j, $arProgramacion['dia11']);
                $hoja->setCellValue('R' . $j, $arProgramacion['dia12']);
                $hoja->setCellValue('S' . $j, $arProgramacion['dia13']);
                $hoja->setCellValue('T' . $j, $arProgramacion['dia14']);
                $hoja->setCellValue('U' . $j, $arProgramacion['dia15']);
                $hoja->setCellValue('V' . $j, $arProgramacion['dia16']);
                $hoja->setCellValue('W' . $j, $arProgramacion['dia17']);
                $hoja->setCellValue('X' . $j, $arProgramacion['dia18']);
                $hoja->setCellValue('Y' . $j, $arProgramacion['dia19']);
                $hoja->setCellValue('Z' . $j, $arProgramacion['dia20']);
                $hoja->setCellValue('AA' . $j, $arProgramacion['dia21']);
                $hoja->setCellValue('AB' . $j, $arProgramacion['dia22']);
                $hoja->setCellValue('AC' . $j, $arProgramacion['dia23']);
                $hoja->setCellValue('AD' . $j, $arProgramacion['dia24']);
                $hoja->setCellValue('AE' . $j, $arProgramacion['dia25']);
                $hoja->setCellValue('AF' . $j, $arProgramacion['dia26']);
                $hoja->setCellValue('AG' . $j, $arProgramacion['dia27']);
                $hoja->setCellValue('AG' . $j, $arProgramacion['dia28']);
                $hoja->setCellValue('AI' . $j, $arProgramacion['dia29']);
                $hoja->setCellValue('AJ' . $j, $arProgramacion['dia30']);
                $hoja->setCellValue('AK' . $j, $arProgramacion['dia31']);
                $hoja->setCellValue('AL' . $j, $arProgramacion['horasDiurnas']);
                $hoja->setCellValue('AM' . $j, $arProgramacion['horasNocturnas']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=soportes.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}