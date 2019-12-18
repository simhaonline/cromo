<?php


namespace App\Controller\RecursoHumano\Informe\Nomina;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenModulo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuRequisito;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class pagoDetalleController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/recursohumano/informe/nomina/pagodetalle/lista", name="recursohumano_informe_nomina_pagodetalle_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('codigoContratoFk', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoDetalleFechaDesde') ? date_create($session->get('filtroRhuInformePagoDetalleFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoDetalleFechaHasta') ? date_create($session->get('filtroRhuInformePagoDetalleFechaHasta')) : null])
            ->add('concepto', EntityType::class, array(
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoConceptoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'attr' => ['class' => 'form-control to-select-2'],
                'data' => $session->get('arSeguridadUsuarioProcesofiltroModulo') || ""
            ))
            ->add('pagoTipo', EntityType::class, array(
                'class' => RhuPagoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'attr' => ['class' => 'form-control to-select-2'],
                'data' => $session->get('arSeguridadUsuarioProcesofiltroModulo') || ""
            ))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcelEmpleado', SubmitType::class, ['label' => 'Excel empleado', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcelConcepto', SubmitType::class, ['label' => 'Excel concepto', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked() || $form->get('btnExcelEmpleado')->isClicked() || $form->get('btnExcelConcepto')->isClicked()) {
                $arConcepto = $form->get('concepto')->getData();
                if ($arConcepto) {
                    $session->set('filtroRhuInformePagoDetalleConcepto', $arConcepto->getCodigoConceptoPk());
                } else {
                    $session->set('filtroRhuInformePagoDetalleConcepto', null);
                }
                $arPagoTipo = $form->get('pagoTipo')->getData();
                if ($arPagoTipo) {
                    $session->set('filtroRhuInformePagoDetalleTipo', $arPagoTipo->getCodigoPagoTipoPk());
                } else {
                    $session->set('filtroRhuInformePagoDetalleTipo', null);
                }
                $session->set('filtroRhuInformePagoDetalleCodigoEmpleado', $form->get('codigoEmpleadoFk')->getData());
                $session->set('filtroRhuInformePagoDetalleCodigoContrato', $form->get('codigoContratoFk')->getData());
                $session->set('filtroRhuInformePagoDetalleFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroRhuInformePagoDetalleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arPagoDetalles = $em->getRepository(RhuPagoDetalle::class)->informe();
                $this->exportarExcelPersonalizado($arPagoDetalles);

            }
            if ($form->get('btnExcelEmpleado')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuPagoDetalle::class)->excelResumenEmpleado()->getQuery()->getArrayResult(), "pagoDetalleEmpleado");
            }
            if ($form->get('btnExcelConcepto')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuPagoDetalle::class)->excelResumenConcepto()->getQuery()->getArrayResult(), "pagoDetalleEmpleado");
            }
        }
        $arPagoDetalles = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/informe/nomina/pagodetalle/lista.html.twig', [
            'arPagoDetalles' => $arPagoDetalles,
            'form' => $form->createView()
        ]);
    }

    public function exportarExcelPersonalizado($arPagoDetalles)
    {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arPagoDetalles) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('PagoDetalle');
            $j = 0;
            $arrColumnas = ['ID', 'TIPO', 'NUMERO', 'COD', 'NI', 'EMPLEADO', 'GRUPO', 'COD', 'CONCEPTO', 'DESDE', 'HASTA', 'VR_PAGO', 'DEVENGADO', 'DEDUCCION', 'H', 'D', '%', 'IBC', 'IBP', 'CRE', 'PEN', 'SAL', 'PV'];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arPagoDetalles as $arPagoDetalle) {
                $devengado = 0;
                $deduccion = 0;
                if($arPagoDetalle['operacion'] == -1){
                    $deduccion = $arPagoDetalle['vrPago'];
                }
                if($arPagoDetalle['operacion'] == 1){
                    $devengado = $arPagoDetalle['vrPago'];
                }
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arPagoDetalle['codigoPagoDetallePk']);
                $hoja->setCellValue('B' . $j, $arPagoDetalle['pagoTipoNombre']);
                $hoja->setCellValue('C' . $j, $arPagoDetalle['pagoNumero']);
                $hoja->setCellValue('D' . $j, $arPagoDetalle['pagoCodigoEmpleadoFk']);
                $hoja->setCellValue('E' . $j, $arPagoDetalle['empleadoNumeroIdentificacion']);
                $hoja->setCellValue('F' . $j, $arPagoDetalle['empleadoNombreCorto']);
                $hoja->setCellValue('G' . $j, $arPagoDetalle['grupoNombre']);
                $hoja->setCellValue('H' . $j, $arPagoDetalle['codigoConceptoFk']);
                $hoja->setCellValue('I' . $j, $arPagoDetalle['conceptoNombre']);
                $hoja->setCellValue('J' . $j, $arPagoDetalle['pagoFechaDesde']->format('Y-m-d'));
                $hoja->setCellValue('K' . $j, $arPagoDetalle['pagoFechaHasta']->format('Y-m-d'));
                $hoja->setCellValue('L' . $j, $arPagoDetalle['vrPagoOperado']);
                $hoja->setCellValue('M' . $j, $devengado);
                $hoja->setCellValue('N' . $j, $deduccion);
                $hoja->setCellValue('O' . $j, $arPagoDetalle['horas']);
                $hoja->setCellValue('P' . $j, $arPagoDetalle['dias']);
                $hoja->setCellValue('Q' . $j, $arPagoDetalle['porcentaje']);
                $hoja->setCellValue('R' . $j, $arPagoDetalle['vrIngresoBaseCotizacion']);
                $hoja->setCellValue('S' . $j, $arPagoDetalle['vrIngresoBasePrestacion']);
                $hoja->setCellValue('T' . $j, $arPagoDetalle['codigoCreditoFk']);
                $hoja->setCellValue('U' . $j, FuncionesController::boolTexto($arPagoDetalle['pension']));
                $hoja->setCellValue('V' . $j, FuncionesController::boolTexto($arPagoDetalle['salud']));
                $hoja->setCellValue('W' . $j, $arPagoDetalle['porcentajeVacaciones']);
                $j++;
            }

            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=PagoDetalle.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');

        }
    }
}