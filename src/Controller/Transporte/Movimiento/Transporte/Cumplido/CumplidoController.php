<?php

namespace App\Controller\Transporte\Movimiento\Transporte\Cumplido;

use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CumplidoController extends Controller
{
   /**
    * @Route("/tte/mto/transporte/cumplido/lista", name="tte_mto_transporte_cumplido_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteCumplido::class)->lista();
        $arCumplidos = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('movimiento/transporte/cumplido/lista.html.twig', ['arCumplidos' => $arCumplidos]);
    }

    /**
     * @Route("/tte/mto/transporte/cumplido/detalle/{codigoCumplido}", name="tte_mto_transporte_cumplido_detalle")
     */
    public function detalle(Request $request, $codigoCumplido)
    {
        $em = $this->getDoctrine()->getManager();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $form = $this->createFormBuilder()
            ->add('btnRetirarGuia', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {

            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteCumplido::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $this->getDoctrine()->getRepository(TteCumplido::class)->liquidar($codigoCumplido);
                }
            }
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->cumplido($codigoCumplido);
        return $this->render('movimiento/transporte/cumplido/detalle.html.twig', [
            'arCumplido' => $arCumplido,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/trasnporte/cumplido/detalle/adicionar/guia/{codigoCumplido}", name="tte_mto_transporte_cumplido_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoCumplido)
    {
        $em = $this->getDoctrine()->getManager();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setCumplidoRel($arCumplido);
                    $arGuia->setEstadoCumplido(1);
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteCumplido::class)->liquidar($codigoCumplido);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->cumplidoPendiente($arCumplido->getCodigoClienteFk());
        return $this->render('movimiento/transporte/despacho/detalleAdicionarRecibo.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

}

