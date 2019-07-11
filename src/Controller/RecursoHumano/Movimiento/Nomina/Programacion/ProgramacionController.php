<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Programacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Form\Type\RecursoHumano\ProgramacionType;
use App\Formato\RecursoHumano\Programacion;
use App\Formato\RecursoHumano\ResumenConceptos;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
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
                General::get()->setExportar($em->createQuery($em->getRepository(RhuProgramacionDetalle::class)->exportar($id))->execute(), "ProgramacionDetalle");
            }
        }
        $arProgramacionDetalles = $paginator->paginate($em->getRepository(RhuProgramacionDetalle::class)->lista($arProgramacion->getCodigoProgramacionPk()), $request->query->get('page', 1), 1000);
        return $this->render('recursohumano/movimiento/nomina/programacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arProgramacion' => $arProgramacion,
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
}