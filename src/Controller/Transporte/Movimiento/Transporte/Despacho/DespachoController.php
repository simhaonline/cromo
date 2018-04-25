<?php

namespace App\Controller\Transporte\Movimiento\Transporte\Despacho;

use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\DespachoType;
use App\Formato\Transporte\Despacho;
use App\Formato\Transporte\Manifiesto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use SoapClient;

class DespachoController extends Controller
{
   /**
    * @Route("/tte/mto/transporte/despacho/lista", name="tte_mto_transporte_despacho_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteDespacho::class)->lista();
        $arDespachos = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/transporte/despacho/lista.html.twig', ['arDespachos' => $arDespachos]);
    }

    /**
     * @Route("/tte/mto/transporte/despacho/nuevo/{codigoDespacho}", name="tte_mto_transporte_despacho_nuevo")
     */
    public function nuevo(Request $request, $codigoDespacho)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = new TteDespacho();
        if($codigoDespacho != 0) {
            $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        }

        $form = $this->createForm(DespachoType::class, $arDespacho);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arDespacho = $form->getData();
            $txtCodigoConductor = $request->request->get('txtCodigoConductor');
            if($txtCodigoConductor != '') {
                $arConductor = $em->getRepository(TteConductor::class)->find($txtCodigoConductor);
                if($arConductor) {
                    $txtCodigoVehiculo = $request->request->get('txtCodigoVehiculo');
                    if($txtCodigoVehiculo != '') {
                        $arVehiculo = $em->getRepository(TteVehiculo::class)->find($txtCodigoVehiculo);
                        if ($arVehiculo) {
                            $arDespacho->setOperacionRel($this->getUser()->getOperacionRel());
                            $arDespacho->setVehiculoRel($arVehiculo);
                            $arDespacho->setConductorRel($arConductor);
                            if($codigoDespacho == 0) {
                                $arDespacho->setFechaRegistro(new \DateTime('now'));
                            }
                            $em->persist($arDespacho);
                            $em->flush();
                            if ($form->get('guardarnuevo')->isClicked()) {
                                return $this->redirect($this->generateUrl('tte_mto_transporte_despacho_nuevo', array('codigoDespacho' => 0)));
                            } else {
                                return $this->redirect($this->generateUrl('tte_mto_transporte_despacho_detalle', array('codigoDespacho' => $arDespacho->getCodigoDespachoPk())));
                            }
                        }
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/transporte/despacho/nuevo.html.twig', ['arDespacho' => $arDespacho,'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/transporte/despacho/detalle/{codigoDespacho}", name="tte_mto_transporte_despacho_detalle")
     */
    public function detalle(Request $request, $codigoDespacho)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        $form = $form = $this->formularioDetalle($arDespacho);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->generar($codigoDespacho);
                return $this->redirect($this->generateUrl('tte_mto_transporte_despacho_detalle', array('codigoDespacho' => $codigoDespacho)));
            }
            if ($form->get('btnCerrar')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->cerrar($codigoDespacho);
                return $this->redirect($this->generateUrl('tte_mto_transporte_despacho_detalle', array('codigoDespacho' => $codigoDespacho)));
            }
            if ($form->get('btnRndc')->isClicked()) {
                $cliente = new SoapClient("http://rndcws.mintransporte.gov.co:8080/ws/svr008w.dll/wsdl/IBPMServices");
                //$respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->reportarRndc($codigoDespacho);
                return $this->redirect($this->generateUrl('tte_mto_transporte_despacho_detalle', array('codigoDespacho' => $codigoDespacho)));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->anular($codigoDespacho);
                return $this->redirect($this->generateUrl('tte_mto_transporte_despacho_detalle', array('codigoDespacho' => $codigoDespacho)));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrDespachoDetalles = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->retirarDetalle($arrDespachoDetalles);
                if($respuesta) {
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('tte_mto_transporte_despacho_detalle', array('codigoDespacho' => $codigoDespacho)));
            }
            if ($form->get('btnImprimirDespacho')->isClicked()) {
                $formato = new Despacho();
                $formato->Generar($em, $codigoDespacho);
            }
            if ($form->get('btnImprimirManifiesto')->isClicked()) {
                $formato = new Manifiesto();
                $formato->Generar($em, $codigoDespacho);
            }
        }

        $arDespachoDetalles = $this->getDoctrine()->getRepository(TteDespachoDetalle::class)->despacho($codigoDespacho);
        return $this->render('transporte/movimiento/transporte/despacho/detalle.html.twig', [
            'arDespacho' => $arDespacho,
            'arDespachoDetalles' => $arDespachoDetalles,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/trasnporte/despacho/detalle/adicionar/guia/{codigoDespacho}", name="tte_mto_transporte_despacho_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoDespacho)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
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
                $this->getDoctrine()->getRepository(TteDespacho::class)->liquidar($codigoDespacho);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->despachoPendiente();
        return $this->render('transporte/movimiento/transporte/despacho/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    private function formularioDetalle($ar)
    {
        $arrBotonRetirarGuia = array('label' => 'Retirar', 'disabled' => false);
        $arrBotonGenerar = array('label' => 'Generar', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => true);
        $arrBotonRndc = array('label' => 'RNDC', 'disabled' => false);
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);
        $arrBotonImprimirManifiesto = array('label' => 'Manifiesto', 'disabled' => false);
        if ($ar->getEstadoGenerado() == 1) {
            $arrBotonRetirarGuia['disabled'] = true;
            $arrBotonGenerar['disabled'] = true;
            if($ar->getEstadoAnulado() == 0) {
                $arrBotonCerrar['disabled'] = false;
                $arrBotonAnular['disabled'] = false;
                if($ar->getEstadoCerrado()) {
                    $arrBotonAnular['disabled'] = true;
                    $arrBotonCerrar['disabled'] = true;
                }
            }
        } else {
            $arrBotonImprimirManifiesto['disabled'] = true;
        }
        $form = $this->createFormBuilder()
            ->add('btnRetirarGuia', SubmitType::class, $arrBotonRetirarGuia)
            ->add('btnGenerar', SubmitType::class, $arrBotonGenerar)
            ->add('btnCerrar', SubmitType::class, $arrBotonCerrar)
            ->add('btnRndc', SubmitType::class, $arrBotonRndc)
            ->add('btnAnular', SubmitType::class, $arrBotonAnular)
            ->add('btnImprimirDespacho', SubmitType::class, array('label' => 'Imprimir orden'))
            ->add('btnImprimirManifiesto', SubmitType::class, $arrBotonImprimirManifiesto)
            ->getForm();
        return $form;

    }

}

