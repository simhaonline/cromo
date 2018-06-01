<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Form\Type\Transporte\GuiaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GuiaController extends Controller
{
   /**
    * @Route("/tte/mto/transporte/guia/lista", name="tte_mto_transporte_guia_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteGuia::class)->lista();
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', ['arGuias' => $arGuias]);
    }

    /**
     * @Route("/tte/mto/transporte/guia/nuevo/{codigoGuia}", name="tte_mto_transporte_guia_nuevo")
     */
    public function nuevo(Request $request, $codigoGuia)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = new TteGuia();
        if($codigoGuia == 0) {
            $arGuia->setFechaIngreso(new \DateTime('now'));
        }

        $form = $this->createForm(GuiaType::class, $arGuia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arGuia = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if ($arCliente) {
                    $error = "";
                    if($arGuia->getGuiaTipoRel()->getExigeNumero()) {
                        if($arGuia->getNumero() == "") {
                            $error = "Debe diligenciar el numero de la guia";
                        }
                    } else {
                        $arGuia->setNumero(NULL);
                    }
                    if($error == "") {
                        $arGuia->setClienteRel($arCliente);
                        $arGuia->setOperacionIngresoRel($this->getUser()->getOperacionRel());
                        $arGuia->setOperacionCargoRel($this->getUser()->getOperacionRel());
                        $arGuia->setFactura($arGuia->getGuiaTipoRel()->getFactura());
                        $arGuia->setCiudadOrigenRel($this->getUser()->getOperacionRel()->getCiudadRel());
                        $em->persist($arGuia);
                        $em->flush();
                        if ($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('tte_mto_transporte_guia_nuevo', array('codigoGuia' => 0)));
                        } else {
                            return $this->redirect($this->generateUrl('tte_mto_transporte_guia_lista'));
                        }
                    }
                }
            }


        }
        return $this->render('transporte/movimiento/transporte/guia/nuevo.html.twig', ['arGuia' => $arGuia,'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/transporte/guia/detalle/{codigoGuia}", name="tte_mto_transporte_guia_detalle")
     */
    public function detalle(Request $request, $codigoGuia)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $form = $this->createFormBuilder()
            ->add('btnRetirarNovedad', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $respuesta = $em->getRepository(TteGuia::class)->imprimir($codigoGuia);
                if($respuesta) {
                    $em->flush();
                    return $this->redirect($this->generateUrl('tte_mto_transporte_guia_detalle', array('codigoGuia' => $codigoGuia)));
                    //$formato = new \App\Formato\TteDespacho();
                    //$formato->Generar($em, $codigoGuia);
                }

            }
        }
        $arNovedades = $this->getDoctrine()->getRepository(TteNovedad::class)->guia($codigoGuia);
        return $this->render('transporte/movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'arNovedades' => $arNovedades,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/trasnporte/guia/detalle/adicionar/novedad/{codigoGuia}", name="tte_mto_transporte_guia_detalle_adicionar_novedad")
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

}

