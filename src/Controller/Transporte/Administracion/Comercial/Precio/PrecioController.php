<?php

namespace App\Controller\Transporte\Administracion\Comercial\Precio;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TtePrecio;
use App\Form\Type\Transporte\PrecioType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PrecioController extends Controller
{
   /**
    * @Route("/tte/adm/comercial/precio/lista", name="tte_adm_comercial_precio_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TtePrecio::class)->lista();
        $arPrecios = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/administracion/comercial/precio/lista.html.twig', ['arPrecios' => $arPrecios]);
    }

    /**
     * @Route("/tte/adm/comercial/precio/nuevo/{codigoPrecio}", name="tte_adm_comercial_precio_nuevo")
     */
    public function nuevo(Request $request, $codigoPrecio)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecio = new TtePrecio();
        if($codigoPrecio == 0) {

        }
        $form = $this->createForm(PrecioType::class, $arPrecio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arPrecio = $form->getData();
            $em->persist($arPrecio);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('tte_adm_comercial_precio_nuevo', array('codigoPrecio' => 0)));
            } else {
                return $this->redirect($this->generateUrl('tte_adm_comercial_precio_lista'));
            }

        }
        return $this->render('transporte/administracion/comercial/precio/nuevo.html.twig', ['arPrecio' => $arPrecio,'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/adm/comercial/precio/detalle/{codigoPrecio}", name="tte_adm_comercial_precio_detalle")
     */
    public function detalle(Request $request, $codigoPrecio)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $form = $this->createFormBuilder()
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

        return $this->render('transporte/movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'form' => $form->createView()]);
    }

}

