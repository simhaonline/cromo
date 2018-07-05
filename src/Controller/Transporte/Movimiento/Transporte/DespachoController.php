<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\DespachoType;
use App\Formato\Transporte\Despacho;
use App\Formato\Transporte\Manifiesto;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use SoapClient;

class DespachoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/movimiento/transporte/despacho/lista", name="transporte_movimiento_transporte_despacho_lista")
     */
    public function lista(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('txtVehiculo', TextType::class, ['required' => false, 'data' => $session->get('filtroTteDespachoCodigoVehiculo')])
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroTteDespachoNumero')])
            ->add('cboCiudadOrigenRel', EntityType::class, $em->getRepository(TteCiudad::class)->llenarCombo('origen'))
            ->add('cboCiudadDestinoRel', EntityType::class, $em->getRepository(TteCiudad::class)->llenarCombo('destino'))
            ->add('txtCodigoConductor', TextType::class, ['required' => false, 'data' => $session->get('filtroTteDespachoCodigoVehiculo'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteDespachoNombreConductor'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTteDespachoCodigoVehiculo', $form->get('txtVehiculo')->getData());
            $session->set('filtroTteDespachoNumero', $form->get('txtNumero')->getData());
            if ($form->get('cboCiudadOrigenRel')->getData() != '') {
                $session->set('filtroTteDespachoCodigoCiudadOrigen', $form->get('cboCiudadOrigenRel')->getData()->getCodigoCiudadPk());
            } else {
                $session->set('filtroTteDespachoCodigoCiudadOrigen', null);
            }
            if ($form->get('cboCiudadDestinoRel')->getData() != '') {
                $session->set('filtroTteDespachoCodigoCiudadDestino', $form->get('cboCiudadDestinoRel')->getData()->getCodigoCiudadPk());
            } else {
                $session->set('filtroTteDespachoCodigoCiudadDestino', null);
            }
            if ($form->get('txtCodigoConductor')->getData() != '') {
                $session->set('filtroTteDespachoCodigoConductor', $form->get('txtCodigoConductor')->getData());
                $session->set('filtroTteDespachoNombreConductor', $form->get('txtNombreCorto')->getData());
            } else {
                $session->set('filtroTteDespachoCodigoConductor', null);
                $session->set('filtroTteDespachoNombreConductor', null);
            }
        }
        $arDespachos = $paginator->paginate($this->getDoctrine()->getRepository(TteDespacho::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/transporte/despacho/lista.html.twig', ['arDespachos' => $arDespachos, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/transporte/movimiento/transporte/despacho/nuevo/{id}", name="transporte_movimiento_transporte_despacho_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = new TteDespacho();
        if ($id != 0) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        }
        $form = $this->createForm(DespachoType::class, $arDespacho);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btn')){

            }
            $arDespacho = $form->getData();
            $txtCodigoConductor = $request->request->get('txtCodigoConductor');
            if ($txtCodigoConductor != '') {
                $arConductor = $em->getRepository(TteConductor::class)->find($txtCodigoConductor);
                if ($arConductor) {
                    $txtCodigoVehiculo = $request->request->get('txtCodigoVehiculo');
                    if ($txtCodigoVehiculo != '') {
                        $arVehiculo = $em->getRepository(TteVehiculo::class)->find($txtCodigoVehiculo);
                        if ($arVehiculo) {
                            $arDespacho->setOperacionRel($this->getUser()->getOperacionRel());
                            $arDespacho->setVehiculoRel($arVehiculo);
                            $arDespacho->setConductorRel($arConductor);
                            if ($id == 0) {
                                $arDespacho->setFechaRegistro(new \DateTime('now'));
                            }
                            $em->persist($arDespacho);
                            $em->flush();
                            if ($form->get('guardarnuevo')->isClicked()) {
                                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_nuevo', array('id' => 0)));
                            } else {
                                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('id' => $arDespacho->getCodigoDespachoPk())));
                            }
                        } else {
                            Mensajes::error('No se ha encontrado un vehiculo con el codigo ingresado');
                        }
                    } else {
                        Mensajes::error('Debe de seleccionar un vehiculo');
                    }
                } else {
                    Mensajes::error('No se ha encontrado un conductor con el codigo ingresado');
                }
            } else {
                Mensajes::error('Debe seleccionar un coductor');
            }
        }
        return $this->render('transporte/movimiento/transporte/despacho/nuevo.html.twig', ['arDespacho' => $arDespacho, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/transporte/movimiento/transporte/despacho/detalle/{id}", name="transporte_movimiento_transporte_despacho_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => true);
        $arrBotonRetirarGuia = array('label' => 'Retirar', 'disabled' => false);
        $arrBotonRndc = array('label' => 'RNDC', 'disabled' => false);
        $arrBotonImprimirManifiesto = array('label' => 'Manifiesto', 'disabled' => false);
        if ($arDespacho->getEstadoAprobado()) {
            $arrBotonRetirarGuia['disabled'] = true;
            if (!$arDespacho->getEstadoAnulado()) {
                $arrBotonCerrar['disabled'] = false;
                if ($arDespacho->getEstadoCerrado()) {
                    $arrBotonCerrar['disabled'] = true;
                }
            }
        } else {
            $arrBotonImprimirManifiesto['disabled'] = true;
        }

        $form = Estandares::botonera($arDespacho->getEstadoAutorizado(), $arDespacho->getEstadoAprobado(), $arDespacho->getEstadoAnulado());
        $form
            ->add('btnCerrar', SubmitType::class, $arrBotonCerrar)
            ->add('btnRndc', SubmitType::class, $arrBotonRndc)
            ->add('btnRetirarGuia', SubmitType::class, $arrBotonRetirarGuia)
            ->add('btnImprimirManifiesto', SubmitType::class, $arrBotonImprimirManifiesto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAprobar')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->generar($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('codigoDespacho' => $id)));
            }
            if ($form->get('btnCerrar')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->cerrar($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('codigoDespacho' => $id)));
            }
            if ($form->get('btnRndc')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->reportarRndc($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('codigoDespacho' => $id)));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->anular($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('codigoDespacho' => $id)));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrDespachoDetalles = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->retirarDetalle($arrDespachoDetalles);
                if ($respuesta) {
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_despacho_detalle', array('codigoDespacho' => $id)));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Despacho();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnImprimirManifiesto')->isClicked()) {
                $formato = new Manifiesto();
                $formato->Generar($em, $id);
            }
        }

        $arDespachoDetalles = $this->getDoctrine()->getRepository(TteDespachoDetalle::class)->despacho($id);
        return $this->render('transporte/movimiento/transporte/despacho/detalle.html.twig', [
            'arDespacho' => $arDespacho,
            'arDespachoDetalles' => $arDespachoDetalles,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/transporte/movimiento/trasnporte/despacho/detalle/adicionar/guia/{id}", name="transporte_movimiento_transporte_despacho_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setDespachoRel($arDespacho);
                    $arGuia->setEstadoEmbarcado(1);
                    $em->persist($arGuia);
                    $arDespachoDetalle = new TteDespachoDetalle();
                    $arDespachoDetalle->setDespachoRel($arDespacho);
                    $arDespachoDetalle->setGuiaRel($arGuia);
                    $arDespachoDetalle->setVrDeclara($arGuia->getVrDeclara());
                    $arDespachoDetalle->setVrFlete($arGuia->getVrFlete());
                    $arDespachoDetalle->setVrManejo($arGuia->getVrManejo());
                    $arDespachoDetalle->setVrRecaudo($arGuia->getVrRecaudo());
                    $arDespachoDetalle->setUnidades($arGuia->getUnidades());
                    $arDespachoDetalle->setPesoReal($arGuia->getPesoReal());
                    $arDespachoDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                    $em->persist($arDespachoDetalle);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteDespacho::class)->liquidar($id);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->despachoPendiente();
        return $this->render('transporte/movimiento/transporte/despacho/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }
}

