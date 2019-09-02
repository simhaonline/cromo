<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Programacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Entity\Turno\TurSoporte;
use App\Form\Type\RecursoHumano\ProgramacionType;
use App\Formato\RecursoHumano\Programacion;
use App\Formato\RecursoHumano\ResumenConceptos;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProgramacionController extends ControllerListenerGeneral
{
    protected $clase = RhuProgramacion::class;
    protected $claseNombre = "RhuProgramacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Programacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/programacion/lista", name="recursohumano_movimiento_nomina_programacion_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Programaciones");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar( RhuProgramacion::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/programacion/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/programacion/nuevo/{id}", name="recursohumano_movimiento_nomina_programacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new RhuProgramacion();
        if ($id != 0) {
            $arProgramacion = $em->getRepository($this->clase)->find($id);
            if (!$arProgramacion) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_empleado_lista'));
            }
        } else {
            $arProgramacion->setFechaDesde(new \DateTime('now'));
            $arProgramacion->setFechaHasta(new \DateTime('now'));
            $arProgramacion->setFechaHastaPeriodo(new \DateTime('now'));
        }
        $form = $this->createForm(ProgramacionType::class, $arProgramacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($arProgramacion->getGrupoRel()) {
                    $arProgramacion->setDias(($arProgramacion->getFechaDesde()->diff($arProgramacion->getFechaHasta()))->days + 1);
                    $em->persist($arProgramacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $arProgramacion->getCodigoProgramacionPk()]));
                } else {
                    Mensajes::error('Debe seleccionar un grupo para la programacion');
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/programacion/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("recursohumano/movimiento/nomina/programacion/detalle/{id}", name="recursohumano_movimiento_nomina_programacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arProgramacion = $this->clase;
        if ($id != 0) {
            $arProgramacion = $em->getRepository($this->clase)->find($id);
            if (!$arProgramacion) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_lista'));
            }
        }
        $arrBtnCargarContratos = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Cargar contratos'];
        $arrBtnExcelDetalle = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Excel'];
        $arrBtnExcelPagoDetalles = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Excel detalle'];
        $arrBtnImprimirResumen = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Resumen','disabled' => true];
        $arrBtnEliminarTodos = ['attr' => ['class' => 'btn btn-sm btn-danger'], 'label' => 'Eliminar todos'];
        $arrBtnEliminar = ['attr' => ['class' => 'btn btn-sm btn-danger'], 'label' => 'Eliminar'];
        if ($arProgramacion->getEstadoAutorizado()) {
            $arrBtnCargarContratos['attr']['class'] .= ' hidden';
            $arrBtnEliminarTodos['attr']['class'] .= ' hidden';
            $arrBtnEliminar['attr']['class'] .= ' hidden';
            $arrBtnImprimirResumen['disabled'] = false;
        }


        $form = Estandares::botonera($arProgramacion->getEstadoAutorizado(), $arProgramacion->getEstadoAprobado(), $arProgramacion->getEstadoAnulado());
        $form->add('btnCargarContratos', SubmitType::class, $arrBtnCargarContratos);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->add('btnEliminarTodos', SubmitType::class, $arrBtnEliminarTodos);
        $form->add('btnImprimirResumen', SubmitType::class, $arrBtnImprimirResumen);
        $form->add('btnExcelDetalle', SubmitType::class, $arrBtnExcelDetalle);
        $form->add('btnExcelPagoDetalles', SubmitType::class, $arrBtnExcelPagoDetalles);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnCargarContratos')->isClicked()) {
                $em->getRepository(RhuProgramacion::class)->cargarContratos($arProgramacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(RhuProgramacionDetalle::class)->eliminar($arrSeleccionados, $arProgramacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormato = new Programacion();
                $objFormato->Generar($em, $id);
            }
            if ($form->get('btnImprimirResumen')->isClicked()) {
                $objFormato = new ResumenConceptos();
                $objFormato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuProgramacion::class)->autorizar($arProgramacion, $this->getUser()->getUsername());
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuProgramacion::class)->aprobar($arProgramacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(RhuProgramacion::class)->desautorizar($arProgramacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminarTodos')->isClicked()) {
                if (!$arProgramacion->getEstadoAutorizado()) {
                    $em->getRepository(RhuProgramacionDetalle::class)->eliminarTodoDetalles($arProgramacion);
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $id]));
                }
            }
            if ($form->get('btnExcelDetalle')->isClicked()) {
                General::get()->setExportar(($em->getRepository(RhuProgramacionDetalle::class)->exportar($id))->execute(), "ProgramacionDetalle");
            }
            if ($form->get('btnExcelPagoDetalles')->isClicked()) {
                $this->generarExcelDetalle($arProgramacion->getCodigoProgramacionPk());
            }
        }
        $arProgramacionDetalles = $paginator->paginate($em->getRepository(RhuProgramacionDetalle::class)->lista($arProgramacion->getCodigoProgramacionPk()), $request->query->get('page', 1), 1000);
        return $this->render('recursohumano/movimiento/nomina/programacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arProgramacion' => $arProgramacion,
            'clase' => array('clase' => 'RhuProgramacion', 'codigo' => $id),
            'arProgramacionDetalles' => $arProgramacionDetalles
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/programacion/detalle/resumen/{id}", name="recursohumano_movimiento_nomina_programacion_detalle_resumen")
     */
    public function resumenPagoDetalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arProgramacionDetalle = $em->getRepository(RhuProgramacionDetalle::class)->find($id);
        $arrBtnActualizar = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Actualizar'];
        $form = $this->createFormBuilder()
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(RhuProgramacionDetalle::class)->actualizar($arProgramacionDetalle, $this->getUser()->getUsername());
            }
        }

        if (!$arProgramacionDetalle->getProgramacionRel()->getEstadoAutorizado()) {
            Mensajes::error('El empleado aun no tiene pagos generados');
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arPago = $em->getRepository(RhuPago::class)->findOneBy(array('codigoProgramacionDetalleFk' => $id));
        if($arPago) {
            $arPagoDetalles = $em->getRepository(RhuPagoDetalle::class)->lista($arPago->getCodigoPagoPk());
        } else {
            $arPagoDetalles = null;
        }

        return $this->render('recursohumano/movimiento/nomina/programacion/resumen.html.twig', [
            'arProgramacionDetalle' => $arProgramacionDetalle,
            'arPago' => $arPago,
            'arPagoDetalles' => $arPagoDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/programacion/cargar/soporte/{id}", name="recursohumano_movimiento_nomina_programacion_cargar_soporte")
     */
    public function cargarSoporte(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = $em->getRepository(RhuProgramacion::class)->find($id);
        //$arrBtnActualizar = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Actualizar'];
        $form = $this->createFormBuilder()
            //->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('OpCargar')) {
                $codigoSoporte = $request->request->get('OpCargar');
                $em->getRepository(RhuProgramacion::class)->cargarContratosTurnos($codigoSoporte, $arProgramacion);
            }
        }

        $arSoportes = $em->getRepository(TurSoporte::class)->cargarSoporte();
        return $this->render('recursohumano/movimiento/nomina/programacion/cargarSoporte.html.twig', [
            'arProgramacion' => $arProgramacion,
            'arSoportes' => $arSoportes,
            'form' => $form->createView()
        ]);
    }

    private function generarExcelDetalle($codigoProgramacionPago)
    {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arProgramacionPago = $em->getRepository(RhuProgramacion::class)->find($codigoProgramacionPago);
        $objPHPExcel = new Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);

        // Pago
        $objPHPExcel->getActiveSheet()->setTitle('Pago');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CODIGO EMPLEADO')
            ->setCellValue('B1', 'DOCUMENTO')
            ->setCellValue('C1', 'CONTRATO')
            ->setCellValue('D1', 'FECHA CONTRATO')
            ->setCellValue('E1', 'VIGENTE')
            ->setCellValue('F1', 'NOMBRE')
            ->setCellValue('G1', 'BANCO')
            ->setCellValue('H1', 'CUENTA')
            ->setCellValue('I1', 'DESDE')
            ->setCellValue('J1', 'HASTA')
            ->setCellValue('K1', 'SALARIO')
            ->setCellValue('L1', 'DEVENGADO')
            ->setCellValue('M1', 'DEV_PAC')
            ->setCellValue('N1', 'DEDUCCIONES')
            ->setCellValue('O1', 'NETO')
            ->setCellValue('P1', 'NOMBRE')
            ->setCellValue('Q1', 'GRUPO PAGO');
        $i = 2;

        $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoProgramacionFk' => $codigoProgramacionPago));
        foreach ($arPagos as $arPago) {
            $banco = $arPago->getEmpleadoRel()->getCodigoBancoFk() == null ? '' : $arPago->getEmpleadoRel()->getBancoRel()->getNombre();
            $contratoVigente = $arPago->getEmpleadoRel()->getEstadoContrato() == 1 ? "SI" : "NO";
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPago->getCodigoEmpleadoFk())
                ->setCellValue('B' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('C' . $i, $arPago->getCodigoContratoFk())
                ->setCellValue('D' . $i, $arPago->getContratoRel()->getFechaDesde()->Format('Y-m-d'))
                ->setCellValue('E' . $i, $contratoVigente)
                ->setCellValue('F' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('G' . $i, $banco)
                ->setCellValue('H' . $i, $arPago->getEmpleadoRel()->getCuenta())
                ->setCellValue('I' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
                ->setCellValue('J' . $i, $arPago->getFechaHasta()->format('Y/m/d'))
                ->setCellValue('K' . $i, $arPago->getVrSalarioContrato())
                ->setCellValue('L' . $i, $arPago->getVrDevengado())
                ->setCellValue('M' . $i, $arPago->getContratoRel()->getVrDevengadoPactado())
                ->setCellValue('N' . $i, $arPago->getVrDeduccion())
                ->setCellValue('O' . $i, $arPago->getVrNeto())
                ->setCellValue('P' . $i, $arProgramacionPago->getNombre())
                ->setCellValue('Q' . $i, $arProgramacionPago->getGrupoRel()->getNombre());
            $i++;
        }
//        $objPHPExcel->getActiveSheet()->setTitle('Pago');
//
//        //Pago2
//        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
//        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
//        $objPHPExcel->createSheet(1)->setTitle('Pago2')
//            ->setCellValue('A1', 'CODIGO EMPLEADO')
//            ->setCellValue('B1', 'DOCUMENTO')
//            ->setCellValue('C1', 'FECHA CONTRATO')
//            ->setCellValue('D1', 'NOMBRE')
//            ->setCellValue('E1', 'BANCO')
//            ->setCellValue('F1', 'CUENTA')
//            ->setCellValue('G1', 'DESDE')
//            ->setCellValue('H1', 'HASTA')
//            ->setCellValue('I1', 'SALARIO')
//            ->setCellValue('J1', 'DEVENGADO')
//            ->setCellValue('K1', 'DEV_PAC')
//            ->setCellValue('L1', 'DEDUCCIONES')
//            ->setCellValue('M1', 'NETO')
//            ->setCellValue('O1', 'CLIENTE')
//            ->setCellValue('P1', 'CONTRATO');
//
//        $i = 2;
//
//        $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoProgramacionFk' => $codigoProgramacionPago));
//        foreach ($arPagos as $arPago) {
//            $objPHPExcel->setActiveSheetIndex(1)
//                ->setCellValue('A' . $i, $arPago->getCodigoEmpleadoFk())
//                ->setCellValue('B' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
//                ->setCellValue('C' . $i, $arPago->getContratoRel()->getFechaDesde()->Format('Y-m-d'))
//                ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
//                ->setCellValue('F' . $i, $arPago->getEmpleadoRel()->getCuenta())
//                ->setCellValue('G' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
//                ->setCellValue('H' . $i, $arPago->getFechaHasta()->format('Y/m/d'))
//                ->setCellValue('I' . $i, $arPago->getVrSalarioContrato())
//                ->setCellValue('J' . $i, $arPago->getVrDevengado())
//                ->setCellValue('K' . $i, $arPago->getContratoRel()->getVrDevengadoPactado())
//                ->setCellValue('L' . $i, $arPago->getVrDeduccion())
//                ->setCellValue('M' . $i, $arPago->getVrNeto())
//                ->setCellValue('P' . $i, $arPago->getCodigoContratoFk());
//            if ($arPago->getEmpleadoRel()->getCodigoBancoFk()) {
//                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getBancoRel()->getNombre());
//            }
//            $i++;
//        }

        //Pago Detalle
        $objPHPExcel->createSheet(1)->setTitle('PagosDetalle')
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'COD')
            ->setCellValue('C1', 'DOCUMENTO')
            ->setCellValue('D1', 'CONTRATO')
            ->setCellValue('E1', 'VIGENTE')
            ->setCellValue('F1', 'FECHA CONTRATO')
            ->setCellValue('G1', 'EMPLEADO')
            ->setCellValue('H1', 'COD')
            ->setCellValue('I1', 'CONCEPTO')
            ->setCellValue('J1', 'HORAS')
            ->setCellValue('K1', 'DEVENGADO')
            ->setCellValue('L1', 'DEDUCCION');

        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet(2)->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'F'; $col++) {
            $objPHPExcel->getActiveSheet(2)->getColumnDimension($col)->setAutoSize(true);
        }
        for ($col = 'H'; $col !== 'I'; $col++) {
            $objPHPExcel->getActiveSheet(2)->getStyle($col)->getAlignment()->setHorizontal('left');
            $objPHPExcel->getActiveSheet(2)->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }

        $i = 2;
        $arPagoDetalles = $em->getRepository(RhuPagoDetalle::class)->pagosDetallesProgramacionPago($codigoProgramacionPago);
        foreach ($arPagoDetalles as $arPagoDetalle) {
            $contratoVigente = $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getEstadoContrato() == 1 ? "SI" : "NO";
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('A' . $i, $arPagoDetalle->getCodigoPagoDetallePk())
                ->setCellValue('B' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getCodigoEmpleadoPk())
                ->setCellValue('C' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('D' . $i, $arPagoDetalle->getPagoRel()->getCodigoContratoFk())
                ->setCellValue('E' . $i, $contratoVigente)
                ->setCellValue('F' . $i, $arPagoDetalle->getPagoRel()->getContratoRel()->getFechaDesde()->Format('Y-m-d'))
                ->setCellValue('G' . $i, $arPagoDetalle->getPagoRel()->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('H' . $i, $arPagoDetalle->getCodigoConceptoFk())
                ->setCellValue('I' . $i, $arPagoDetalle->getConceptoRel()->getNombre())
                ->setCellValue('J' . $i, $arPagoDetalle->getHoras());
            if ($arPagoDetalle->getOperacion() == 1) {
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('K' . $i, $arPagoDetalle->getVrPago());
            }
            if ($arPagoDetalle->getOperacion() == -1) {
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('L' . $i, $arPagoDetalle->getVrPago());
            }
            $i++;
        }


//        //Incapacidades
//        $objPHPExcel->createSheet()->setTitle('Incapacidades')
//            ->setCellValue('A1', 'TIPO')
//            ->setCellValue('B1', 'DESDE')
//            ->setCellValue('C1', 'HASTA')
//            ->setCellValue('D1', 'IDENTIFICACION')
//            ->setCellValue('E1', 'EMPLEADO')
//            ->setCellValue('F1', 'DIAS');
//        $objPHPExcel->setActiveSheetIndex(3);
//        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
//        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
//
//        $i = 2;
//        $arIncapacidades = $em->getRepository(RhuIncapacidad::class)->periodo($arProgramacionPago->getFechaDesde(), $arProgramacionPago->getFechaHasta(), "", $arProgramacionPago->getCodigoGrupoFk());
//        foreach ($arIncapacidades as $arIncapacidad) {
//            $objPHPExcel->setActiveSheetIndex(3)
//                ->setCellValue('A' . $i, $arIncapacidad->getIncapacidadTipoRel()->getNombre())
//                ->setCellValue('B' . $i, $arIncapacidad->getFechaDesde()->format('Y/m/d'))
//                ->setCellValue('C' . $i, $arIncapacidad->getFechaHasta()->format('Y/m/d'))
//                ->setCellValue('D' . $i, $arIncapacidad->getEmpleadoRel()->getNumeroIdentificacion())
//                ->setCellValue('E' . $i, $arIncapacidad->getEmpleadoRel()->getNombreCorto())
//                ->setCellValue('F' . $i, $arIncapacidad->getCantidad());
//            $i++;
//        }
//
//        //Cesantias
//
//        $objPHPExcel->createSheet()->setTitle('Cesantias')
//            ->setCellValue('A1', 'TIPO')
//            ->setCellValue('B1', 'DOCUMENTO')
//            ->setCellValue('C1', 'PRIMER APELLIDO')
//            ->setCellValue('D1', 'SEGUNDO APELLIDO')
//            ->setCellValue('E1', 'PRIMER NOMBRE')
//            ->setCellValue('F1', 'SEGUNDO NOMBRE')
//            ->setCellValue('G1', 'SALARIO')
//            ->setCellValue('H1', 'VALOR');
//
//        $objPHPExcel->setActiveSheetIndex(4);
//        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
//        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
//
//        $i = 2;
//        $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoProgramacionFk' => $codigoProgramacionPago));
//        foreach ($arPagos as $arPago) {
//            $objPHPExcel->setActiveSheetIndex(4)
//                ->setCellValue('A' . $i, $arPago->getEmpleadoRel()->getCodigoIdentificacionFk())
//                ->setCellValue('B' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
//                ->setCellValue('C' . $i, $arPago->getEmpleadoRel()->getApellido1())
//                ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getApellido2())
//                ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombre1())
//                ->setCellValue('F' . $i, $arPago->getEmpleadoRel()->getNombre2())
//                ->setCellValue('G' . $i, $arPago->getVrSalarioContrato())
//                ->setCellValue('H' . $i, $arPago->getVrNeto());
//            $i++;
//        }
//
//        //Anticipos
//
//        $objPHPExcel->createSheet()->setTitle('Anticipos')
//            ->setCellValue('A1', 'IDENTIFICACION')
//            ->setCellValue('B1', 'VALOR');
//
//        $objPHPExcel->setActiveSheetIndex(5);
//        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
//        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
//
//        $i = 2;
//        $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoProgramacionFk' => $codigoProgramacionPago));
//        /** @var $arPago RhuPago */
//        foreach ($arPagos as $arPago) {
//            $objPHPExcel->setActiveSheetIndex(5)
//                ->setCellValue('A' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
//                ->setCellValue('B' . $i, $arPago->getVrNeto());
//            $i++;
//        }

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=pagoDetalle.xls");
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        $writer->save('php://output');
        exit;
    }
}