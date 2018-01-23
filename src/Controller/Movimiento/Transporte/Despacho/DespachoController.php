<?php

namespace App\Controller\Movimiento\Transporte\Despacho;

use App\Entity\Despacho;
use App\Entity\Guia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DespachoController extends Controller
{
   /**
    * @Route("/mto/transporte/despacho/lista", name="mto_transporte_despacho_lista")
    */    
    public function lista()
    {
        $arDespachos = $this->getDoctrine()->getRepository(Despacho::class)->lista();
        return $this->render('movimiento/transporte/despacho/lista.html.twig', ['arDespachos' => $arDespachos]);
    }

    /**
     * @Route("/mto/transporte/despacho/detalle/{codigoDespacho}", name="mto_transporte_despacho_detalle")
     */
    public function detalle(Request $request, $codigoDespacho)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(Despacho::class)->find($codigoDespacho);
        $form = $this->createFormBuilder()
            ->add('btnRetirarGuia', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new \App\Formato\Despacho();
                $formato->Generar($em, $codigoDespacho);
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(Despacho::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                }
            }
        }

        $arGuias = $this->getDoctrine()->getRepository(Guia::class)->guiasDespacho($codigoDespacho);
        return $this->render('movimiento/transporte/despacho/detalle.html.twig', [
            'arDespacho' => $arDespacho,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/mto/trasnporte/despacho/detalle/adicionar/guia/{codigoDespacho}", name="mto_transporte_despacho_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoDespacho)
    {
        $em = $this->getDoctrine()->getManager();
        $arDespacho = $em->getRepository(Despacho::class)->find($codigoDespacho);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(Guia::class)->find($codigo);
                    $arGuia->setDespachoRel($arDespacho);
                    $arGuia->setEstadoDespachado(1);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(Guia::class)->despachoPendiente();
        return $this->render('movimiento/despacho/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }
}

