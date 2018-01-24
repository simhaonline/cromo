<?php

namespace App\Controller\Movimiento\Recogida\Despacho;

use App\Entity\DespachoRecogida;
use App\Entity\DespachoRecogidaAuxiliar;
use App\Entity\Recogida;
use App\Entity\Auxiliar;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DespachoRecogidaController extends Controller
{
   /**
    * @Route("/mto/recogida/despacho/lista", name="mto_recogida_despacho_lista")
    */    
    public function lista()
    {
        $arDespachosRecogida = $this->getDoctrine()->getRepository(DespachoRecogida::class)->lista();
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
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new \App\Formato\Despacho();
                $formato->Generar($em, $codigoDespachoRecogida);
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

}

