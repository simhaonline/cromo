<?php


namespace App\Controller\Turno\Informe\Comercial;


use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendiendeFacturaraController extends AbstractController
{
    /**
     * @Route("/turno/informe/comercial/pedienteFacturar/lista", name="turno_informe_comercial_pedienteFacturar_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('label' => 'Nit', 'required'=>false))
            ->add('numero', TextType::class, array('label' => 'Codigo', 'required'=>false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAutorizado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'AUTORIZADO' => '1', 'SIN AUTORIZAR' => '0')))
            ->add('estadoProgramado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'PROGRAMADO' => '1', 'SIN PROGRAMAR' => '0')))
            ->add('estadoFacturado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'FACTURADO' => '1', 'SIN FACTURAR' => '0')))
            ->add('estadoAnulado', ChoiceType::class, array('choices' => array('TODOS' => '2', 'ANULADO' => '1', 'SIN ANULAR' => '0')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [];
        if ($form->isSubmitted()) {
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                $arPendientesFacturas = $em->getRepository(TurPedidoDetalle::class)->pendienteFacturarInforme($raw);
                $this->exportarExcelPersonalizado($arPendientesFacturas);
            }
        }

        $arPedidoDestalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->pendienteFacturarInforme($raw), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/pendienteFacturar.html.twig', [
            'arPedidoDestalles' => $arPedidoDestalles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/informe/comercial/pedienteFacturar/referencia/{codigoPedidoDetalle}", name="turno_informe_comercial_pedienteFacturar_referencia")
     */
    public function Referencia($codigoPedidoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
        $arFacturaDetalles = $em->getRepository(TurFacturaDetalle::class)->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle));
        $arProgramacionDetalles = $em->getRepository(TurProgramacion::class)->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle));
        $arPedidoDevolucionDetalles=[]; //$em->getRepository(TurPedidoDevolucion::class) consultar esto "select pdd from TurPedidoDevolucionDetalle pdd JOIN pdd.pedidoDevolucionRel pd WHERE pdd.codigoPedidoDetalleFk = " . $codigoPedidoDetalle);
        $arContratoDetalle = null;
        if ($arPedidoDetalle->getCodigoContratoDetalleFk()) {
            $arContratoDetalle = $arPedidoDetalle->getContratoDetalleRel();
        }
        return $this->render('turno/informe/comercial/Resumen.html.twig', [
            'arPedidoDetalle' => $arPedidoDetalle,
            'arFacturaDetalles' => $arFacturaDetalles,
            'arProgramacionDetalles' => $arProgramacionDetalles,
            'arPedidoDevolucionDetalles' => $arPedidoDevolucionDetalles,
            'arContratoDetalle' => $arContratoDetalle
        ]);
    }

    /**
     * @param $arPendientesFacturas
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportarExcelPersonalizado($arPendientesFacturas)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arPendientesFacturas) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('PagoDetalle');
            $j = 0;
            $arrColumnas = ['CÓDIG0','NUMERO PEDIDO','FECHA PEDIDO', 'FECHA PROG', 'NIT', 'CLIENTE', 'SECTOR', 'AUT', 'PRO','FAC','ANU','C_COSTO','PUESTO','SERVICIO','MODALIDAD','PERIODO','PLANTILLA','DESDE','HASTA','CANT','LU','MA','MI'.'JU','VI','SA','DO','FE', 'H','HD','HN','HP','HDP','HNP','DIAS','IVA','VALOR','VR.PEND','TOTAL' ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setBold(true);;
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arPendientesFacturas as $arPendientesFactura) {
                $hoja->setCellValue('A' . $j, $arPendientesFactura['codigoPedidoDetallePk']);
                $hoja->setCellValue('B' . $j, $arPendientesFactura['pedidoTipoNombre']);
                $hoja->setCellValue('C' . $j, $arPendientesFactura['pedidoFecha']);
                $hoja->setCellValue('D' . $j, $arPendientesFactura['fechaGeneracion']);
                $hoja->setCellValue('E' . $j, "{$arPendientesFactura['numeroIdentificacion']}-{$arPendientesFactura['digitoVerificacion']}");
                $hoja->setCellValue('F' . $j, $arPendientesFactura['nombreCorto']);
                $hoja->setCellValue('G' . $j, $arPendientesFactura['sectorNombre']);
                $hoja->setCellValue('H' . $j, FuncionesController::boolTexto($arPendientesFactura['sectorNombre']));
                $hoja->setCellValue('I' . $j, FuncionesController::boolTexto($arPendientesFactura['pedidoEstadoAutorizado']));
                $hoja->setCellValue('J' . $j, FuncionesController::boolTexto($arPendientesFactura['pedidoEstadoProgramado']));
                $hoja->setCellValue('K' . $j, FuncionesController::boolTexto($arPendientesFactura['pedidoEstadoFacturado']));
                $hoja->setCellValue('L' . $j, FuncionesController::boolTexto($arPendientesFactura['pedidoEstadoAnulado']));
//                $hoja->setCellValue('M' . $i, $arPendientesFactura[''] $arPedidoDetalle->getPuestoRel()->getCodigoCentroCostoContabilidadFk());
//                $hoja->setCellValue('N' . $i, $arPendientesFactura['puesto']);
                $hoja->setCellValue('O' . $j, $arPendientesFactura['conceptoNombre']);
                $hoja->setCellValue('p' . $j, $arPendientesFactura['modalidadNombre']);
//                $hoja->setCellValue('R' . $i, ' ');
                $hoja->setCellValue('Q' . $j, $arPendientesFactura['numero']);
                $hoja->setCellValue('S' . $j, $arPendientesFactura['diaDesde']);
                $hoja->setCellValue('T' . $j, $arPendientesFactura['diaHasta']);
                $hoja->setCellValue('U' . $j, $arPendientesFactura['cantidad']);
                $hoja->setCellValue('V' . $j, FuncionesController::boolTexto($arPendientesFactura['lunes']));
                $hoja->setCellValue('w' . $j, FuncionesController::boolTexto($arPendientesFactura['martes']));
                $hoja->setCellValue('X' . $j, FuncionesController::boolTexto($arPendientesFactura['miercoles']));
                $hoja->setCellValue('Y' . $j, FuncionesController::boolTexto($arPendientesFactura['jueves']));
                $hoja->setCellValue('Z' . $j, FuncionesController::boolTexto($arPendientesFactura['viernes']));
                $hoja->setCellValue('AA' . $j, FuncionesController::boolTexto($arPendientesFactura['sabado']));
                $hoja->setCellValue('AB' . $j, FuncionesController::boolTexto($arPendientesFactura['domingo']));
                $hoja->setCellValue('AC' . $j, FuncionesController::boolTexto($arPendientesFactura['festivo']));
                $hoja->setCellValue('AD' . $j, $arPendientesFactura['horas']);
                $hoja->setCellValue('AE' . $j, $arPendientesFactura['horasDiurnas']);
                $hoja->setCellValue('AF' . $j, $arPendientesFactura['horasNocturnas']);
                $hoja->setCellValue('AG' . $j, $arPendientesFactura['horasProgramadas']);
                $hoja->setCellValue('AI' . $j, $arPendientesFactura['horasDiurnasProgramadas']);
                $hoja->setCellValue('AJ' . $j, $arPendientesFactura['horasNocturnasProgramadas']);
                $hoja->setCellValue('AI' . $j, $arPendientesFactura['dias']);
                $hoja->setCellValue('AK' . $j, $arPendientesFactura['vrIva']);
                $hoja->setCellValue('AL' . $j, $arPendientesFactura['vrSubtotal']);
                $hoja->setCellValue('AM' . $j, $arPendientesFactura['vrTotalDetallePendiente']);
                $hoja->setCellValue('AN' . $j, $arPendientesFactura['vrTotalDetalle']);

//                if ($arPedidoDetalle->getPuestoRel()) {
//                    $objPHPExcel->setActiveSheetIndex(0)

//                }
//                if ($arPedidoDetalle->getPlantillaRel()) {
//                    $objPHPExcel->setActiveSheetIndex(0)
//                }
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



    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoProgramado' => $form->get('estadoProgramado')->getData(),
            'estadoFacturado' => $form->get('estadoFacturado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }
}