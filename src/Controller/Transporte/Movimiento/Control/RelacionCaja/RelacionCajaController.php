<?php

namespace App\Controller\Transporte\Movimiento\Control\RelacionCaja;

use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRelacionCaja;
use App\Form\Type\Transporte\DespachoType;
use App\Form\Type\Transporte\RelacionCajaType;
use App\Formato\Transporte\RelacionCaja;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RelacionCajaController extends Controller
{
   /**
    * @Route("/tte/mto/control/relacioncaja/lista", name="tte_mto_control_relacioncaja_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteRelacionCaja::class)->lista();
        $arRelacionesCaja = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/control/relacioncaja/lista.html.twig', ['arRelacionesCaja' => $arRelacionesCaja]);
    }

    /**
     * @Route("/tte/mto/control/relacioncaja/nuevo/{codigoRelacionCaja}", name="tte_mto_control_relacioncaja_nuevo")
     */
    public function nuevo(Request $request, $codigoRelacionCaja)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = new TteRelacionCaja();
        if($codigoRelacionCaja == 0) {
            $arRelacionCaja->setFecha(new \DateTime('now'));
        }

        $form = $this->createForm(RelacionCajaType::class, $arRelacionCaja);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRelacionCaja = $form->getData();
            $em->persist($arRelacionCaja);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('tte_mto_control_relacioncaja_nuevo', array('codigoRelacionCaja' => 0)));
            } else {
                return $this->redirect($this->generateUrl('tte_mto_control_relacioncaja_lista'));
            }




        }
        return $this->render('transporte/movimiento/control/relacioncaja/nuevo.html.twig', ['arRelacionCaja' => $arRelacionCaja,'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/control/relacioncaja/detalle/{codigoRelacionCaja}", name="tte_mto_control_relacioncaja_detalle")
     */
    public function detalle(Request $request, $codigoRelacionCaja)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = $em->getRepository(TteRelacionCaja::class)->find($codigoRelacionCaja);
        $form = $this->createFormBuilder()
            ->add('btnRetirarRecibo', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new RelacionCaja();
                $formato->Generar($em, $codigoRelacionCaja);
            }
            if ($form->get('btnRetirarRecibo')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteRelacionCaja::class)->retirarRecibo($arr);
                if($respuesta) {
                    $em->flush();
                    $this->getDoctrine()->getRepository(TteRelacionCaja::class)->liquidar($codigoRelacionCaja);
                }
                return $this->redirect($this->generateUrl('tte_mto_control_relacioncaja_detalle', array('codigoRelacionCaja' => $codigoRelacionCaja)));
            }
        }

        $arRecibos = $this->getDoctrine()->getRepository(TteRecibo::class)->relacionCaja($codigoRelacionCaja);
        return $this->render('transporte/movimiento/control/relacioncaja/detalle.html.twig', [
            'arRelacionCaja' => $arRelacionCaja,
            'arRecibos' => $arRecibos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/control/relacioncaja/detalle/adicionar/recibo/{codigoRelacionCaja}", name="tte_mto_control_relacioncaja_detalle_adicionar_recibo")
     */
    public function detalleAdicionarGuia(Request $request, $codigoRelacionCaja)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = $em->getRepository(TteRelacionCaja::class)->find($codigoRelacionCaja);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $ar = $em->getRepository(TteRecibo::class)->find($codigo);
                    $ar->setRelacionCajaRel($arRelacionCaja);
                    $ar->setEstadoRelacion(1);
                    $em->persist($ar);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteRelacionCaja::class)->liquidar($codigoRelacionCaja);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arRecibos = $this->getDoctrine()->getRepository(TteRecibo::class)->relacionPendiente();
        return $this->render('transporte/movimiento/control/relacioncaja/detalleAdicionarRecibo.html.twig', ['arRecibos' => $arRecibos, 'form' => $form->createView()]);
    }

}

