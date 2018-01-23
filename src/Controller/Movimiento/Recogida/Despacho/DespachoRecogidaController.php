<?php

namespace App\Controller\Movimiento\Recogida\Despacho;

use App\Entity\DespachoRecogida;
use App\Entity\Recogida;
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
        $arDespachosRecogida = $this->getDoctrine()
            ->getRepository(DespachoRecogida::class)
            ->listaMovimiento();

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
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new \App\Formato\Despacho();
                $formato->Generar($em, $codigoDespachoRecogida);
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(Despacho::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                }
            }
        }
        $arRecogidas = $this->getDoctrine()->getRepository(Recogida::class)->despacho($codigoDespachoRecogida);
        return $this->render('movimiento/recogida/despacho/detalle.html.twig', [
            'arDespachoRecogida' => $arDespachoRecogida,
            'arRecogidas' => $arRecogidas,
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

}

