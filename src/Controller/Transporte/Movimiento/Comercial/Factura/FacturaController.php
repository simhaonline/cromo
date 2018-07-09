<?php

namespace App\Controller\Transporte\Movimiento\Comercial\Factura;

use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaOtro;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Form\Type\Transporte\FacturaType;
use App\Formato\Transporte\Factura;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaController extends Controller
{
   /**
    * @Route("/transporte/movimiento/comercial/factura/lista", name="transporte_movimiento_comercial_factura_lista")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFacturas = $paginator->paginate($em->getRepository(TteFactura::class)->lista(), $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/comercial/factura/lista.html.twig', ['arFacturas' => $arFacturas]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/{id}", name="transporte_movimiento_comercial_factura_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($id);
        $paginator  = $this->get('knp_paginator');
        $form = Estandares::botonera($arFactura->getEstadoAutorizado(),$arFactura->getEstadoAprobado(),$arFactura->getEstadoAnulado());
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arFactura->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Factura();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteFactura::class)->autorizar($arFactura);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteFactura::class)->desAutorizar($arFactura);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteFactura::class)->Aprobar($arFactura);
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(TteFactura::class)->liquidar($id);
                }
            }
            return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
        }
        $query = $this->getDoctrine()->getRepository(TteFacturaPlanilla::class)->listaFacturaDetalle($id);
        $arFacturaPlanillas = $paginator->paginate($query, $request->query->getInt('page', 1),10);

        $query = $this->getDoctrine()->getRepository(TteFacturaOtro::class)->listaFacturaDetalle($id);
        $arFacturaOtros = $paginator->paginate($query, $request->query->getInt('page', 1),10);

        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->factura($id);
        return $this->render('transporte/movimiento/comercial/factura/detalle.html.twig', [
            'arFactura' => $arFactura,
            'arGuias' => $arGuias,
            'arFacturaPlanillas' => $arFacturaPlanillas,
            'arFacturaOtros' => $arFacturaOtros,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/{codigoFactura}", name="transporte_movimiento_comercial_factura_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setFacturaRel($arFactura);
                    $arGuia->setEstadoFacturado(1);
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteFactura::class)->liquidar($codigoFactura);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->facturaPendiente($arFactura->getCodigoClienteFk());
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/nuevo/{id}", name="transporte_movimiento_comercial_factura_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if($id == 0) {
            $arFactura = new TteFactura();
        }
        $form = $this->createForm(FacturaType::class, $arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arFactura = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arFactura->setClienteRel($arCliente);
                    if ($arFactura->getPlazoPago() <= 0) {
                        $arFactura->setPlazoPago($arFactura->getClienteRel()->getPlazoPago());
                    }
                    $em->persist($arFactura);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_lista'));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/comercial/factura/nuevo.html.twig', ['$arFactura' => $arFactura,'form' => $form->createView()]);
    }


}

