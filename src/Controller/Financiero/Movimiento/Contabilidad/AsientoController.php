<?php

namespace App\Controller\Financiero\Movimiento\Contabilidad;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Financiero\AsientoType;
use App\Formato\Financiero\Asiento;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AsientoController extends ControllerListenerGeneral
{
    protected $clase = FinAsiento::class;
    protected $claseNombre = "FinAsiento";
    protected $modulo = "Financiero";
    protected $funcion = "Movimiento";
    protected $grupo = "Contabilidad";
    protected $nombre = "Asiento";





    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/movimiento/contabilidad/asiento/lista", name="financiero_movimiento_contabilidad_asiento_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session=new Session();
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro=$this->getFiltroLista();
        $formFiltro->handleRequest($request);
        $datos=$this->getDatosLista();
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Asientos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }

        if($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                $session->set('FinAsiento_numero',$formFiltro->get('numero')->getData());
                $session->set('FinAsiento_codigoComprobanteFk',$formFiltro->get('codigoComprobanteFk')->getData()!=""?$formFiltro->get('codigoComprobanteFk')->getData()->getCodigoComprobantePk():"");
                $datos=$this->getDatosLista();
            }
        }
        return $this->render('financiero/movimiento/contabilidad/asiento/lista.html.twig', [
            'arrDatosLista' =>$datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro'=> $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/asiento/nuevo/{id}", name="financiero_movimiento_contabilidad_asiento_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $ar = new FinAsiento();
        if ($id != 0) {
            $ar = $em->getRepository($this->clase)->find($id);
        } else {
            $ar->setFecha(new \DateTime('now'));
            $ar->setFechaContable(new \DateTime('now'));
            $ar->setFechaDocumento(new \DateTime('now'));
        }
        $form = $this->createForm(AsientoType::class, $ar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($ar);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_movimiento_contabilidad_asiento_detalle', ['id' => $ar->getCodigoAsientoPk()]));
            }
        }
        return $this->render('financiero/movimiento/contabilidad/asiento/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/asiento/detalle/{id}", name="financiero_movimiento_contabilidad_asiento_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arAsiento = $em->getRepository(FinAsiento::class)->find($id);
        $form = Estandares::botonera($arAsiento->getEstadoAutorizado(), $arAsiento->getEstadoAprobado(), $arAsiento->getEstadoAnulado());
        $form->add('btnActualizarDetalle', SubmitType::class, ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']]);
        $form->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']]);

        if ($arAsiento->getEstadoAutorizado() == 0) {
            $form->add('btnAdicionarDetalle', SubmitType::class, ['label' => 'add', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']]);
            $form->add('txtCodigoTercero', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control input-sm']]);
            $form->add('txtCodigoCuenta', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control input-sm']]);
            $form->add('txtDebito', NumberType::class, ['required' => false, 'data' => 0, 'attr' => ['class' => 'form-control input-sm']]);
            $form->add('txtCredito', NumberType::class, ['required' => false, 'data' => 0, 'attr' => ['class' => 'form-control input-sm']]);
            $form->add('txtBase', NumberType::class, ['required' => false, 'data' => 0, 'attr' => ['class' => 'form-control input-sm']]);
        } else {
            $form->add('btnAdicionarDetalle', SubmitType::class, ['label' => 'add', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']]);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $redireccionar = true;
            if ($form->get('btnAutorizar')->isClicked()) {
                $error = $em->getRepository(FinAsiento::class)->actualizarDetalles($id, $arrControles);
                if ($error == false) {
                    $em->getRepository(FinAsiento::class)->autorizar($arAsiento, $arrControles);
                }
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(FinAsiento::class)->desautorizar($arAsiento);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatopedido = new Asiento();
                $objFormatopedido->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(FinAsiento::class)->aprobar($arAsiento);
            }
            if ($form->get('btnAnular')->isClicked()) {
                //$em->getRepository(FinAsiento::class)->anular($arAsiento);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(FinAsientoDetalle::class)->eliminar($arAsiento, $arrDetallesSeleccionados);
                $em->getRepository(FinAsiento::class)->liquidar($id);
            }
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                $em->getRepository(FinAsiento::class)->actualizarDetalles($id, $arrControles);
            }
            if ($form->get('btnAdicionarDetalle')->isClicked()) {
                if ($arAsiento->getEstadoAutorizado() == 0) {
                    $redireccionar = false;
                    $error = false;
                    $strMensaje = "";
                    $codigoTercero = $form->get('txtCodigoTercero')->getData();
                    $debito = $form->get('txtDebito')->getData();
                    $credito = $form->get('txtCredito')->getData();
                    $codigoCuenta = $form->get('txtCodigoCuenta')->getData();
                    $base = $form->get('txtBase')->getData();
                    $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuenta);

                    if ($arCuenta) {
                        //valida si la cuenta exige movimiento
                        if ($arCuenta->getPermiteMovimiento()) {

                            // solo debe tener debito o credito por cada linea
                            if ($debito > 0 && $credito > 0) {
                                $error = true;
                                $strMensaje = "Por cada linea solo el debito o credito puede tener valor mayor a cero";
                            }
                            // validacion de tercero
                            if ($arCuenta->getExigeTercero()) {
                                if ($codigoTercero == "") {
                                    $strMensaje = "La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " exige tercero";
                                    $error = true;
                                } else {
                                    $arTercero = $em->getRepository(FinTercero::class)->find($codigoTercero);
                                    if (!$arTercero) {
                                        $strMensaje = "El tercero no existe.";
                                        $error = true;
                                    }
                                }
                            } else {
                                $arTercero = null;
                            }

                            $vrBase = 0;
                            // validacion de base
                            if ($arCuenta->getExigeBase() && $base == 0) {
                                $strMensaje = "La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " exige base";
                                $error = true;
                            } else {
                                $vrBase = $base;
                            }
                            if ($error == false) {
                                $arAsientoDetalle = new FinAsientoDetalle();
                                $arAsientoDetalle->setVrBase($vrBase);
                                $arAsientoDetalle->setTerceroRel($arTercero);
                                $arAsientoDetalle->setAsientoRel($arAsiento);
                                $arAsientoDetalle->setCuentaRel($arCuenta);
                                $arAsientoDetalle->setVrDebito($debito);
                                $arAsientoDetalle->setVrCredito($credito);
                                $em->persist($arAsientoDetalle);
                                $em->flush();
                                return $this->redirect($this->generateUrl('financiero_movimiento_contabilidad_asiento_detalle', ['id' => $id]));
                            } else {
                                Mensajes::error($strMensaje);
                            }
                        } else {
                            Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " no permite movimiento");
                        }
                    } else {
                        Mensajes::error("La cuenta " . $codigoCuenta . " no existe");
                    }
                }
            }
            if ($redireccionar == true) {
                return $this->redirect($this->generateUrl('financiero_movimiento_contabilidad_asiento_detalle', ['id' => $id]));
            }
        }
        $arAsientoDetalles = $paginator->paginate($em->getRepository(FinAsientoDetalle::class)->asiento($id), $request->query->getInt('page', 1), 1000);
        return $this->render('financiero/movimiento/contabilidad/asiento/detalle.html.twig', [
            'arAsiento' => $arAsiento,
            'arAsientoDetalles' => $arAsientoDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/financiero/buscar/cuenta/asiento/{campoCodigo}", name="financiero_buscar_cuenta_asiento")
     */
    public function buscarCuenta(Request $request, $campoCodigo)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroFinBuscarCuentaCodigo')])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroFinBuscarCuentaNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBuscarBodegaCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroInvBuscarBodegaNombre', $form->get('txtNombre')->getData());
            }
        }
        $arCuentas = $paginator->paginate($em->getRepository(FinCuenta::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('financiero/movimiento/contabilidad/asiento/buscarCuenta.html.twig', array(
            'arCuentas' => $arCuentas,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/financiero/buscar/asiento/tercero/{campoCodigo}", name="financiero_buscar_tercero_asiento")
     */
    public function buscarTercero(Request $request, $campoCodigo)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroFinBuscarCuentaCodigo')])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroFinBuscarCuentaNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBuscarBodegaCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroInvBuscarBodegaNombre', $form->get('txtNombre')->getData());
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(FinTercero::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('financiero/movimiento/contabilidad/asiento/buscarTercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }
}