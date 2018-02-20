<?php

namespace App\Controller\Movimiento\Recogida\Despacho;

use App\Entity\DespachoRecogida;
use App\Form\Type\DespachoRecogidaType;
use App\Entity\DespachoRecogidaAuxiliar;
use App\Entity\Recogida;
use App\Entity\Auxiliar;
use App\Entity\Monitoreo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DespachoRecogidaController extends Controller
{


    /**
     * @Route("/mto/recogida/despacho/nuevo/{codigoDespachoRecogida}", name="mto_recogida_despacho_nuevo")
     */
    public function nuevo(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        if($codigoDespachoRecogida == 0) {
            $arDespachoRecogida = new DespachoRecogida();
        }

        $form = $this->createForm(DespachoRecogidaType::class, $arDespachoRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRecogida = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(Cliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arRecogida->setClienteRel($arCliente);
                    $arRecogida->setOperacionRel($this->getUser()->getOperacionRel());
                    $em->persist($arRecogida);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('mto_recogida_recogida_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('mto_recogida_recogida_lista'));
                    }
                }
            }
        }
        return $this->render('movimiento/recogida/despacho/nuevo.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'form' => $form->createView()]);
    }

   /**
    * @Route("/mto/recogida/despacho/lista", name="mto_recogida_despacho_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(DespachoRecogida::class)->lista();
        $arDespachosRecogida = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('movimiento/recogida/despacho/lista.html.twig', ['arDespachosRecogida' => $arDespachosRecogida]);
    }

    /**
     * @Route("/mto/recogida/despacho/detalle/{codigoDespachoRecogida}", name="mto_recogida_despacho_detalle")
     */
    public function detalle(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(DespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnRetirarRecogida', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnRetirarAuxiliar', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->add('btnMonitoreo', SubmitType::class, array('label' => 'Monitoreo'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new \App\Formato\Despacho();
                $formato->Generar($em, $codigoDespachoRecogida);
            }
            if ($form->get('btnMonitoreo')->isClicked()) {
                if(!$arDespachoRecogida->getEstadoMonitoreo()) {
                    $respuesta = $this->getDoctrine()->getRepository(Monitoreo::class)->generar(
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
                $respuesta = $this->getDoctrine()->getRepository(DespachoRecogida::class)->retirarRecogida($arrRecogidas);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(DespachoRecogida::class)->liquidar($codigoDespachoRecogida);
                }
            }
        }
        $arRecogidas = $this->getDoctrine()->getRepository(Recogida::class)->despacho($codigoDespachoRecogida);
        $arDespachoRecogidaAuxiliares = $this->getDoctrine()->getRepository(DespachoRecogidaAuxiliar::class)->despacho($codigoDespachoRecogida);
        return $this->render('movimiento/recogida/despacho/detalle.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'arRecogidas' => $arRecogidas,
            'arDespachoRecogidaAuxiliares' => $arDespachoRecogidaAuxiliares,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/mto/recogida/despacho/detalle/adicionar/recogida/{codigoDespachoRecogida}", name="mto_recogida_despacho_detalle_adicionar_recogida")
     */
    public function detalleAdicionarRecogida(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(DespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arRecogida = $em->getRepository(Recogida::class)->find($codigo);
                    $arRecogida->setDespachoRecogidaRel($arDespachoRecogida);
                    $arRecogida->setEstadoProgramado(1);
                    $em->persist($arRecogida);
                }
                $em->flush();
                $em->getRepository(DespachoRecogida::class)->liquidar($codigoDespachoRecogida);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arRecogidas = $this->getDoctrine()->getRepository(Recogida::class)->despachoPendiente();
        return $this->render('movimiento/recogida/despacho/detalleAdicionarRecogida.html.twig', ['arRecogidas' => $arRecogidas, 'form' => $form->createView()]);
    }

    /**
     * @Route("/mto/recogida/despacho/detalle/adicionar/auxiliar/{codigoDespachoRecogida}", name="mto_recogida_despacho_detalle_adicionar_auxiliar")
     */
    public function detalleAdicionarAuxiliar(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(DespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arAuxiliar = $em->getRepository(Auxiliar::class)->find($codigo);
                    $arDespachoRecogidaAuxiliar = new DespachoRecogidaAuxiliar();
                    $arDespachoRecogidaAuxiliar->setAuxiliarRel($arAuxiliar);
                    $arDespachoRecogidaAuxiliar->setDespachoRecogidaRel($arDespachoRecogida);
                    $em->persist($arDespachoRecogidaAuxiliar);
                }
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arAuxiliares = $this->getDoctrine()->getRepository(Auxiliar::class)->findAll();
        return $this->render('movimiento/recogida/despacho/detalleAdicionarAuxiliar.html.twig', ['arAuxiliares' => $arAuxiliares, 'form' => $form->createView()]);
    }

    /**
     * @Route("/mto/recogida/despacho/descargar/{codigoDespachoRecogida}", name="mto_recogida_despacho_descargar")
     */
    public function descargar(Request $request, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespachoRecogida = $em->getRepository(DespachoRecogida::class)->find($codigoDespachoRecogida);
        $form = $this->createFormBuilder()
            ->add('btnDescargarRecogida', SubmitType::class, array('label' => 'Descargar'))
            ->add('btnActualizarRecogida', SubmitType::class, array('label' => 'Actualizar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnDescargarRecogida')->isClicked()) {
                $arrRecogidas = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(DespachoRecogida::class)->descargarRecogida($arrRecogidas);
                if($respuesta) {
                    $em->flush();
                }
            }
            if ($form->get('btnActualizarRecogida')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoDespachoRecogida);
                $em->getRepository(DespachoRecogida::class)->liquidar($codigoDespachoRecogida);
            }
        }
        $arRecogidas = $this->getDoctrine()->getRepository(Recogida::class)->despachoSinDescargar($codigoDespachoRecogida);
        return $this->render('movimiento/recogida/despacho/descargar.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()]);
    }

    private function actualizarDetalle($arrControles, $codigoDespachoRecogida)
    {
        $em = $this->getDoctrine()->getManager();
        if (isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $codigo) {
                $arRecogida = $em->getRepository(Recogida::class)->find($codigo);
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

