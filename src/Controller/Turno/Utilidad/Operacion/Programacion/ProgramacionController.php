<?php

namespace App\Controller\Turno\Utilidad\Operacion\Programacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurSecuencia;
use App\Entity\Turno\TurSimulacion;
use App\Entity\Turno\TurTurno;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProgramacionController extends AbstractController
{

    /**
     * @Route("turno/utilidad/operacion/programacion", name="turno_utilidad_operacion_programacion")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $fechaActual = new \DateTime('now');
        $strAnio = $fechaActual->format('Y');
        $strMes = $fechaActual->format('m');
        $form = $this->createFormBuilder()
            ->add('codigoCliente', TextType::class, array('required' => false))
            ->add('codigoPedidoDetalle', TextType::class, array('required' => false))
            ->add('codigoPuesto', TextType::class, array('required' => false))
            ->add('anio', TextType::class, array('required' => false, 'data'=>$strAnio))
            ->add('mes', TextType::class, array('required' => false, 'data'=>$strMes))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnDescartar', SubmitType::class, array('label' => 'Descartar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        $raw = [];
        if ($form->isSubmitted() ) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnDescartar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurPedidoDetalle::class)->descartar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion'));
            }
        }
        $arPedidoDetalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->pendienteProgramar($raw), $request->query->getInt('page', 1), 500);
        return $this->render('turno/utilidad/operacion/programacion/lista.html.twig', [
            'arPedidoDetalles' => $arPedidoDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("turno/utilidad/operacion/programacion/detalle/{id}", name="turno_utilidad_operacion_programacion_detalle")
     */
    public function prototipo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($id);
        $fechaProgramacion = FuncionesController::primerDia(new \DateTime('now'));
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnGenerar = ['label' => 'Generar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnSimular = ['label' => 'Simular', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnSimularLimpiar = ['label' => 'Limpiar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnExcel = ['label' => 'Excel', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arPedidoDetalle->getEstadoProgramado()) {
            $arrBtnGenerar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
            ->add('fechaSimulacion', DateType::class, array('data' => $fechaProgramacion, 'format' => 'yyyyMMdd'))
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnGenerar', SubmitType::class, $arrBtnGenerar)
            ->add('btnSimular', SubmitType::class, $arrBtnSimular)
            ->add('btnSimularLimpiar', SubmitType::class, $arrBtnSimularLimpiar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnExcel', SubmitType::class, $arrBtnExcel)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurPrototipo::class)->actualizar($arrControles);
                return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurPrototipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion_detalle', ['id' => $id]));
            }
            if($form->get('btnSimular')->isClicked()) {
                $em->getRepository(TurSimulacion::class)->limpiar($id);
                $em->getRepository(TurPrototipo::class)->actualizar($arrControles);
                $fechaProgramacion = $form->get('fechaSimulacion')->getData();
                $em->getRepository(TurPrototipo::class)->generarSimulacion($arPedidoDetalle, $fechaProgramacion);
                return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion_detalle', ['id' => $id]));
            }
            if($form->get('btnGenerar')->isClicked()) {
                $em->getRepository(TurProgramacion::class)->generar($arPedidoDetalle);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

            }
            if($form->get('btnSimularLimpiar')->isClicked()) {
                $em->getRepository(TurSimulacion::class)->limpiar($id);
                return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion_detalle', ['id' => $id]));
            }
            if($form->get('btnExcel')->isClicked()) {
                $arSimulaciones = $em->getRepository(TurSimulacion::class)->listaExcel($id);
                $this->exportarExcelPersonalizado($arSimulaciones);
            }
        }
        $arPrototipos = $em->getRepository(TurPrototipo::class)->listaProgramar($arPedidoDetalle->getCodigoContratoDetalleFk());
        $arSimulaciones = $em->getRepository(TurSimulacion::class)->listaProgramar($id);
        $arSecuencias = $em->getRepository(TurSecuencia::class)->findAll();

        $fechaProgramacion = new \DateTime('now');
        $strAnioMes = $fechaProgramacion->format('Y/m');
        $arrDiaSemana = array();
        $arrFestivos = $em->getRepository(TurFestivo::class)->fechaArray($fechaProgramacion->format("Y-m-01"), $fechaProgramacion->format("Y-m-t"));
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $boolFestivo = 0;
            $fechaRecorrida = $fechaProgramacion->format("Y-m-" . ($i < 10 ? '0' . $i : $i));
            if ($diaSemana == 'd' || in_array($fechaRecorrida, $arrFestivos)) {
                $boolFestivo = true;
            }
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana, 'festivo' => $boolFestivo);
        }
        return $this->render('turno/utilidad/operacion/programacion/prototipo.html.twig', [
            'arPrototipos' => $arPrototipos,
            'arSimulaciones' => $arSimulaciones,
            'arPedidoDetalle' => $arPedidoDetalle,
            'arSecuencias' => $arSecuencias,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()
        ]);
    }

    private function devuelveDiaSemanaEspaniol($dateFecha)
    {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }

    /**
     * @Route("turno/utilidad/operacion/programacion/consultarSecuencia", name="turno_utilidad_operacion_programacion_consultarSecuencia")
     */
    public function ajaxConsultarSecuencia(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id-secuencia');
        $secuencia = $em->getRepository(TurSecuencia::class)->find($id);
        $infoSecuencia = [
            'lunes' => $secuencia->getLunes(),
            'martes' => $secuencia->getMartes(),
            'miercoles' => $secuencia->getMiercoles(),
            'jueves' => $secuencia->getJueves(),
            'viernes' => $secuencia->getViernes(),
            'sabado' => $secuencia->getSabado(),
            'domingo' => $secuencia->getDomingo(),
            'festivo' => $secuencia->getFestivo(),
            'domFestivo' => $secuencia->getDomingoFestivo(),
            'horas' => $secuencia->getHoras(),
            'dias' => $secuencia->getDias(),
            'homologa' => $secuencia->getHomologar(),
        ];
        for ($i = 1; $i <= 31; $i++) {
            $infoSecuencia["dia" . $i] = call_user_func_array([$secuencia, "getDia{$i}"], []);
        }
        $response = new JsonResponse($infoSecuencia);
        return $response;

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoCliente')->getData(),
            'codigoPedidoDetalle' => $form->get('codigoPedidoDetalle')->getData(),
            'codigoPuesto' => $form->get('codigoPuesto')->getData(),
            'mes' => $form->get('mes')->getData(),
            'anio' => $form->get('anio')->getData(),
        ];
        return $filtro;
    }

    public function exportarExcelPersonalizado($arSecuencias)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arSecuencias) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Movimientos');
            $j = 0;
            $arrColumnas=[
                'ANIO','MES','PUESTO','EMPLEADO','D1','D2','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12','D13',
                'D14','D15','D16','D17','D18','D19','D20','D21','D22','D23','D24','D25','D26','D27','D28','D29','D30','D31'
            ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arSecuencias as $arSecuencia) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arSecuencia['anio']);
                $hoja->setCellValue('B' . $j, $arSecuencia['mes']);
                $hoja->setCellValue('C' . $j, $arSecuencia['puestoNombre']);
                $hoja->setCellValue('D' . $j, $arSecuencia['nombreCorto']);
                $hoja->setCellValue('E' . $j, $arSecuencia['dia1']);
                $hoja->setCellValue('F' . $j, $arSecuencia['dia2']);
                $hoja->setCellValue('G' . $j, $arSecuencia['dia3']);
                $hoja->setCellValue('H' . $j, $arSecuencia['dia4']);
                $hoja->setCellValue('I' . $j, $arSecuencia['dia5']);
                $hoja->setCellValue('J' . $j, $arSecuencia['dia6']);
                $hoja->setCellValue('K' . $j, $arSecuencia['dia7']);
                $hoja->setCellValue('L' . $j, $arSecuencia['dia8']);
                $hoja->setCellValue('M' . $j, $arSecuencia['dia9']);
                $hoja->setCellValue('N' . $j, $arSecuencia['dia10']);
                $hoja->setCellValue('O' . $j, $arSecuencia['dia11']);
                $hoja->setCellValue('P' . $j, $arSecuencia['dia12']);
                $hoja->setCellValue('Q' . $j, $arSecuencia['dia13']);
                $hoja->setCellValue('R' . $j, $arSecuencia['dia14']);
                $hoja->setCellValue('S' . $j, $arSecuencia['dia15']);
                $hoja->setCellValue('T' . $j, $arSecuencia['dia16']);
                $hoja->setCellValue('U' . $j, $arSecuencia['dia17']);
                $hoja->setCellValue('V' . $j, $arSecuencia['dia18']);
                $hoja->setCellValue('W' . $j, $arSecuencia['dia19']);
                $hoja->setCellValue('X' . $j, $arSecuencia['dia20']);
                $hoja->setCellValue('Y' . $j, $arSecuencia['dia21']);
                $hoja->setCellValue('Z' . $j, $arSecuencia['dia22']);
                $hoja->setCellValue('AA' . $j, $arSecuencia['dia23']);
                $hoja->setCellValue('AB' . $j, $arSecuencia['dia24']);
                $hoja->setCellValue('AC' . $j, $arSecuencia['dia25']);
                $hoja->setCellValue('AD' . $j, $arSecuencia['dia26']);
                $hoja->setCellValue('AE' . $j, $arSecuencia['dia27']);
                $hoja->setCellValue('AF' . $j, $arSecuencia['dia28']);
                $hoja->setCellValue('AG' . $j, $arSecuencia['dia29']);
                $hoja->setCellValue('AH' . $j, $arSecuencia['dia30']);
                $hoja->setCellValue('AI' . $j, $arSecuencia['dia31']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=simulacion.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');

        }
    }

}
