<?php


namespace App\Controller\Turno\Informe\Juridico;


use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class contratoDetalleController extends  Controller
{
    /**
     * @Route("/turno/informe/juridico/contratoDetalle/lista", name="turno_informe_juridico_contratodetalle_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCliente', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformeContratoDetalleFechaDesde') ? date_create($session->get('filtroRhuInformeContratoDetalleFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformeContratoDetalleFechaHasta') ? date_create($session->get('filtroRhuInformeContratoDetalleFechaHasta')): null])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoTerminado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SIN TERMINAR' => '0', 'TERMINADO' => '1'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuInformeContratoDetalleCodigoCliente',  $form->get('txtCliente')->getData());
                $session->set('filtroRhuInformeContratoDetalleEstadoAutorizado',  $form->get('estadoAutorizado')->getData());
                $session->set('filtroRhuInformeContratoDetalleEstadoTerminado',  $form->get('estadoTerminado')->getData());
                $session->set('filtroRhuInformeContratoDetalleFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuInformeContratoDetalleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
            if ($form->get('btnExcel')->isClicked()){
                $arMovimientos = $em->getRepository(TurContratoDetalle::class)->informe();
                $this->exportarExcelPersonalizado($arMovimientos);
            }
        }
        $arContratoDetalles = $paginator->paginate($em->getRepository(TurContratoDetalle::class)->informe(), $request->query->getInt('page', 1), 500);
        return $this->render('turno/informe/juridico/contratoDetalle/lista.html.twig', [
                'arContratoDetalles' => $arContratoDetalles,
                'form' => $form->createView()
            ]
        );
    }

    public function exportarExcelPersonalizado($arMovimientos){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arMovimientos) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Movimientos');
            $j = 0;
            $arrColumnas=[ 
                    'ID',
                    'CLIENTE',
                    'PUESTO',
                    'ITEM',
                    'MODALIDAD',
                    'PERIODO',
                    'DESDE',
                    'HASTA',
                    'CANT',
                    'L',
                    'M',
                    'X',
                    'J',
                    'V',
                    'S',
                    'D',
                    'F',
                    'H',
                    'HD',
                    'HN',
                    'DIAS',
                    'AUT',
                    'TER',
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

                $hoja->setCellValue('A' . $j, $arMovimiento['codigoContratoDetallePk']);
                $hoja->setCellValue('B' . $j, $arMovimiento['cliente']);
                $hoja->setCellValue('C' . $j, $arMovimiento['puesto']);
                $hoja->setCellValue('D' . $j, $arMovimiento['item']);
                $hoja->setCellValue('E' . $j, $arMovimiento['modalidad']);
                $hoja->setCellValue('F' . $j, $arMovimiento['periodo']);
                $hoja->setCellValue('G' . $j, $arMovimiento['fechaDesde']->format('Y/m/d'));
                $hoja->setCellValue('H' . $j, $arMovimiento['fechaHasta']->format('Y/m/d'));
                $hoja->setCellValue('I' . $j, $arMovimiento['cantidad']);
                $hoja->setCellValue('J' . $j, $arMovimiento['lunes']);
                $hoja->setCellValue('K' . $j, $arMovimiento['martes']);
                $hoja->setCellValue('L' . $j, $arMovimiento['miercoles']);
                $hoja->setCellValue('M' . $j, $arMovimiento['jueves']);
                $hoja->setCellValue('N' . $j, $arMovimiento['viernes']);
                $hoja->setCellValue('O' . $j, $arMovimiento['sabado']);
                $hoja->setCellValue('P' . $j, $arMovimiento['domingo']);
                $hoja->setCellValue('Q' . $j, $arMovimiento['festivo']);
                $hoja->setCellValue('R' . $j, $arMovimiento['horas']);
                $hoja->setCellValue('S' . $j, $arMovimiento['horasDiurnas']);
                $hoja->setCellValue('T' . $j, $arMovimiento['horasNocturnas']);
                $hoja->setCellValue('U' . $j, $arMovimiento['dias']);
                $hoja->setCellValue('V' . $j, $arMovimiento['estadoAutorizado']);
                $hoja->setCellValue('W' . $j, $arMovimiento['estadoTerminado']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=detalleContrato.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }





}