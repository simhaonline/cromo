<?php

namespace App\Controller\Tesoreria\Movimiento;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenBanco;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesEgreso;
use App\Entity\Tesoreria\TesEgresoDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Tesoreria\EgresoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class EgresoController extends BaseController
{
    protected $clase = TesEgreso::class;
    protected $claseNombre = "TesEgreso";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "Egreso";
    protected $nombre = "Egreso";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/movimiento/egreso/egreso/lista", name="tesoreria_movimiento_egreso_egreso_lista")
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
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Egresos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TesEgreso::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_lista'));
            }
        }
        return $this->render('tesoreria/movimiento/egreso/egreso/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/tesoreria/movimiento/egreso/egreso/nuevo/{id}", name="tesoreria_movimiento_egreso_egreso_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = new TesEgreso();
        if ($id != 0) {
            $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
            if (!$arEgreso) {
                return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_lista'));
            }
        }
        $form = $this->createForm(EgresoType::class, $arEgreso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(TesTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arEgreso->setTerceroRel($arTercero);
                        if ($id == 0) {
                            $arEgreso->setFecha(new \DateTime('now'));
                        }
                        $em->persist($arEgreso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_detalle', ['id' => $arEgreso->getCodigoEgresoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('tesoreria/movimiento/egreso/egreso/nuevo.html.twig', [
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/tesoreria/movimiento/egreso/egreso/detalle/{id}", name="tesoreria_movimiento_egreso_egreso_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
        $paginator = $this->get('knp_paginator');
        $form = Estandares::botonera($arEgreso->getEstadoAutorizado(), $arEgreso->getEstadoAprobado(), $arEgreso->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arEgreso->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        }
        $form
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TesEgresoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(TesEgreso::class)->autorizar($arEgreso);
                return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arEgreso->getEstadoAutorizado() == 1 && $arEgreso->getEstadoImpreso() == 0) {
                    $em->getRepository(TesEgreso::class)->desAutorizar($arEgreso);
                    return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El egreso debe estar autorizado y no puede estar impreso");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TesEgreso::class)->aprobar($arEgreso);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Egreso();
                $formato->Generar($em, $id);
                $arEgreso->setEstadoImpreso(1);
                $em->persist($arEgreso);
                $em->flush();
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(TesEgreso::class)->anular($arEgreso);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TesEgresoDetalle::class)->actualizar($arrControles, $id);
                return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TesEgresoDetalle::class)->eliminar($arEgreso, $arrDetallesSeleccionados);
            }
            return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
        }
        $arEgresoDetalles = $paginator->paginate($em->getRepository(TesEgresoDetalle::class)->lista($arEgreso->getCodigoEgresoPk()), $request->query->getInt('page', 1), 30);
        return $this->render('tesoreria/movimiento/egreso/egreso/detalle.html.twig', [
            'arEgresoDetalles' => $arEgresoDetalles,
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/tesoreria/movimiento/egreso/egreso/detalle/nuevo/{id}", name="tesoreria_movimiento_egreso_egreso_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('cboCuentaPagarTipo', EntityType::class, $em->getRepository(TesCuentaPagarTipo::class)->llenarCombo())
            ->add('cboBanco', EntityType::class, $em->getRepository(GenBanco::class)->llenarCombo())
            ->add('txtCodigoCuentaPagar', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => $session->get('')])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesFechaDesde') ? date_create($session->get('filtroTesFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesFechaHasta') ? date_create($session->get('filtroTesFechaHasta')) : null])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCuentaPagarTipo = $form->get('cboCuentaPagarTipo')->getData();
                if ($arCuentaPagarTipo) {
                    $session->set('filtroTesCuentaPagarTipo', $arCuentaPagarTipo->getCodigoCuentaPagarTipoPk());
                } else {
                    $session->set('filtroTesCuentaPagarTipo', null);
                }
                $arBanco = $form->get('cboBanco')->getData();
                if ($arBanco) {
                    $session->set('filtroGenBanco', $arBanco->getCodigoBancoPk());
                } else {
                    $session->set('filtroGenBanco', null);
                }
                $session->set('filtroTesCuentaPagarCodigoPendiente', $form->get('txtCodigoCuentaPagar')->getData());
                $session->set('filtroTesFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroTesFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrCuentasPagar = $request->request->get('ChkSeleccionar');
                if ($arrCuentasPagar) {
                    foreach ($arrCuentasPagar as $codigoCuentaPagar) {
                        $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($codigoCuentaPagar);
                        $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
                        $arEgresoDetalle = new TesEgresoDetalle();
                        $arEgresoDetalle->setEgresoRel($arEgreso);
                        $arEgresoDetalle->setNumero($arCuentaPagar->getNumeroDocumento());
                        $arEgresoDetalle->setCuentaPagarRel($arCuentaPagar);
                        $arEgresoDetalle->setVrPagoAfectar($arCuentaPagar->getVrSaldo());
                        $em->persist($arEgresoDetalle);

                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arCuentasPagar = $paginator->paginate($em->getRepository(TesCuentaPagar::class)->pendiente(), $request->query->getInt('page', 1), 10);
        return $this->render('tesoreria/movimiento/egreso/egreso/detalleNuevo.html.twig', [
            'arCuentasPagar' => $arCuentasPagar,
            'form' => $form->createView()
        ]);
    }


}