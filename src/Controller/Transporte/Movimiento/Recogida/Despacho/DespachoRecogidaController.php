<?php

namespace App\Controller\Transporte\Movimiento\Recogida\Despacho;

use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\DespachoRecogidaType;
use App\Entity\Transporte\TteDespachoRecogidaAuxiliar;
use App\Entity\Transporte\TteRecogida;
use App\Entity\Transporte\TteAuxiliar;
use App\Entity\Transporte\TteMonitoreo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DespachoRecogidaController extends Controller
{


    /**
     * @Route("/transporte/movimiento/recogida/despacho/nuevo/{codigoDespachoRecogida}", name="transporte_movimiento_recogida_despacho_nuevo")
     */
    public function nuevo(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        if($codigoDespachoRecogida == 0) {
            $arDespachoRecogida = new TteDespachoRecogida();
            $arDespachoRecogida->setFecha(new \DateTime('now'));
        }

        $form = $this->createForm(DespachoRecogidaType::class, $arDespachoRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arDespachoRecogida = $form->getData();
            $txtCodigoVehiculo = $request->request->get('txtCodigoVehiculo');
            if($txtCodigoVehiculo != '') {
                $arVehiculo = $em->getRepository(TteVehiculo::class)->find($txtCodigoVehiculo);
                if($arVehiculo) {
                    $arDespachoRecogida->setVehiculoRel($arVehiculo);
                    $arDespachoRecogida->setOperacionRel($this->getUser()->getOperacionRel());
                    $em->persist($arDespachoRecogida);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despacho_nuevo', array('codigoDespachoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despacho_lista'));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/recogida/despacho/nuevo.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'form' => $form->createView()]);
    }

   /**
    * @Route("/transporte/movimiento/recogida/despacho/lista", name="transporte_movimiento_recogida_despacho_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteDespachoRecogida::class)->lista();
        $arDespachosRecogida = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/recogida/despacho/lista.html.twig', ['arDespachosRecogida' => $arDespachosRecogida]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/despacho/detalle/{codigoDespachoRecogida}", name="transporte_movimiento_recogida_despacho_detalle")
     */
    public function detalle(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnRetirarRecogida', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnRetirarAuxiliar', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->add('btnMonitoreo', SubmitType::class, array('label' => 'TteMonitoreo'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new \App\Formato\Despacho();
                $formato->Generar($em, $codigoDespachoRecogida);
            }
            if ($form->get('btnMonitoreo')->isClicked()) {
                if(!$arDespachoRecogida->getEstadoMonitoreo()) {
                    $respuesta = $this->getDoctrine()->getRepository(TteMonitoreo::class)->generar(
                        $arDespachoRecogida->getCodigoVehiculoFk());
                    $arDespachoRecogida->setEstadoMonitoreo(1);
                    if($respuesta) {
                        $em->persist($arDespachoRecogida);
                        $em->flush();
                    }
                }
            }
            if ($form->get('btnRetirarRecogida')->isClicked()) {
                $arrRecogidas = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespachoRecogida::class)->retirarRecogida($arrRecogidas);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(TteDespachoRecogida::class)->liquidar($codigoDespachoRecogida);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_despacho_detalle', array('codigoDespachoRecogida' => $codigoDespachoRecogida)));
            }
        }
        $arRecogidas = $this->getDoctrine()->getRepository(TteRecogida::class)->despacho($codigoDespachoRecogida);
        $arDespachoRecogidaAuxiliares = $this->getDoctrine()->getRepository(TteDespachoRecogidaAuxiliar::class)->despacho($codigoDespachoRecogida);
        return $this->render('transporte/movimiento/recogida/despacho/detalle.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'arRecogidas' => $arRecogidas,
            'arDespachoRecogidaAuxiliares' => $arDespachoRecogidaAuxiliares,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/despacho/detalle/adicionar/recogida/{codigoDespachoRecogida}", name="transporte_movimiento_recogida_despacho_detalle_adicionar_recogida")
     */
    public function detalleAdicionarRecogida(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($arrSeleccionados) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                    $arRecogida->setDespachoRecogidaRel($arDespachoRecogida);
                    $arRecogida->setEstadoProgramado(1);
                    $em->persist($arRecogida);
                }
                $em->flush();
            }
            $em->getRepository(TteDespachoRecogida::class)->liquidar($codigoDespachoRecogida);
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arRecogidas = $this->getDoctrine()->getRepository(TteRecogida::class)->despachoPendiente($arDespachoRecogida->getFecha()->format('Y-m-d'));
        return $this->render('transporte/movimiento/recogida/despacho/detalleAdicionarRecogida.html.twig', ['arRecogidas' => $arRecogidas, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/despacho/detalle/adicionar/auxiliar/{codigoDespachoRecogida}", name="transporte_movimiento_recogida_despacho_detalle_adicionar_auxiliar")
     */
    public function detalleAdicionarAuxiliar(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arAuxiliar = $em->getRepository(TteAuxiliar::class)->find($codigo);
                    $arDespachoRecogidaAuxiliar = new TteDespachoRecogidaAuxiliar();
                    $arDespachoRecogidaAuxiliar->setAuxiliarRel($arAuxiliar);
                    $arDespachoRecogidaAuxiliar->setDespachoRecogidaRel($arDespachoRecogida);
                    $em->persist($arDespachoRecogidaAuxiliar);
                }
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arAuxiliares = $this->getDoctrine()->getRepository(TteAuxiliar::class)->findAll();
        return $this->render('transporte/movimiento/recogida/despacho/detalleAdicionarAuxiliar.html.twig', ['arAuxiliares' => $arAuxiliares, 'form' => $form->createView()]);
    }

    private function actualizarDetalle($arrControles, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $codigo) {
                $arRecogida = $em->getRepository(TteRecogida::class)->find($codigo);
                if ($arrControles['TxtUnidades' . $codigo] != '') {
                    $arRecogida->setUnidades($arrControles['TxtUnidades' . $codigo]);
                }
                if ($arrControles['TxtPesoReal' . $codigo] != '') {
                    $arRecogida->setPesoReal($arrControles['TxtPesoReal' . $codigo]);
                }
                if ($arrControles['TxtPesoVolumen' . $codigo] != '') {
                    $arRecogida->setPesoVolumen($arrControles['TxtPesoVolumen' . $codigo]);
                }
                $em->persist($arRecogida);
            }
            $em->flush();
        }
    }

}

