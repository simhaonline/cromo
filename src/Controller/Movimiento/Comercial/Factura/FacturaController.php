<?php

namespace App\Controller\Movimiento\Comercial\Factura;

use App\Entity\Factura;
use App\Entity\Guia;
use App\Entity\Cliente;
use App\Form\Type\FacturaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaController extends Controller
{
   /**
    * @Route("/mto/comercial/factura/lista", name="mto_comercial_factura_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(Factura::class)->lista();
        $arFacturas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('movimiento/comercial/factura/lista.html.twig', ['arFacturas' => $arFacturas]);
    }

    /**
     * @Route("/mto/comercial/factura/detalle/{codigoFactura}", name="mto_comercial_factura_detalle")
     */
    public function detalle(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(Factura::class)->find($codigoFactura);
        $form = $this->createFormBuilder()
            ->add('btnRetirarGuia', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new \App\Formato\Factura();
                $formato->Generar($em, $codigoFactura);
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(Factura::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $this->getDoctrine()->getRepository(Factura::class)->liquidar($codigoFactura);
                }
            }
        }
        $arGuias = $this->getDoctrine()->getRepository(Guia::class)->factura($codigoFactura);
        return $this->render('movimiento/comercial/factura/detalle.html.twig', [
            'arFactura' => $arFactura,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/mto/comercial/factura/detalle/adicionar/guia/{codigoFactura}", name="mto_comercial_factura_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(Factura::class)->find($codigoFactura);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(Guia::class)->find($codigo);
                    $arGuia->setFacturaRel($arFactura);
                    $arGuia->setEstadoFacturado(1);
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(Factura::class)->liquidar($codigoFactura);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(Guia::class)->facturaPendiente($arFactura->getCodigoClienteFk());
        return $this->render('movimiento/comercial/factura/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/mto/comercial/factura/nuevo/{codigoFactura}", name="mto_comercial_factura_nuevo")
     */
    public function nuevo(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        if($codigoFactura == 0) {
            $aFactura = new Factura();
            //$aFactura->setFechaRegistro(new \DateTime('now'));
        }

        $form = $this->createForm(FacturaType::class, $aFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $aFactura = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(Cliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $aFactura->setClienteRel($arCliente);
                    $em->persist($aFactura);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('mto_comercial_factura_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('mto_comercial_factura_lista'));
                    }
                }
            }
        }
        return $this->render('movimiento/comercial/factura/nuevo.html.twig', ['$aFactura' => $aFactura,'form' => $form->createView()]);
    }

}

