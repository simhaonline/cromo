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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaController extends Controller
{
   /**
    * @Route("/tte/mto/comercial/factura/lista", name="tte_mto_comercial_factura_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteFactura::class)->lista();
        $arFacturas = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/comercial/factura/lista.html.twig', ['arFacturas' => $arFacturas]);
    }

    /**
     * @Route("/tte/mto/comercial/factura/detalle/{id}", name="tte_mto_comercial_factura_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($id);
        $form = $this->formularioDetalles($arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Factura();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App:Transporte\TteFactura')->autorizar($arFactura);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App:Transporte\TteFactura')->desAutorizar($arFactura);
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $this->getDoctrine()->getRepository(TteFactura::class)->liquidar($id);
                }
            }
            return $this->redirect($this->generateUrl('tte_mto_comercial_factura_detalle', ['id' => $id]));
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
     * @Route("/tte/mto/comercial/factura/detalle/adicionar/guia/{codigoFactura}", name="tte_mto_comercial_factura_detalle_adicionar_guia")
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
     * @Route("/tte/mto/comercial/factura/nuevo/{id}", name="tte_mto_comercial_factura_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if($id == 0) {
            $aFactura = new TteFactura();
            //$aFactura->setFechaRegistro(new \DateTime('now'));
        }

        $form = $this->createForm(FacturaType::class, $aFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $aFactura = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $aFactura->setClienteRel($arCliente);
                    $em->persist($aFactura);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('tte_mto_comercial_factura_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('tte_mto_comercial_factura_lista'));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/comercial/factura/nuevo.html.twig', ['$aFactura' => $aFactura,'form' => $form->createView()]);
    }

    private function formularioDetalles($arFactura)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arFactura->getEstadoAnulado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($arFactura->getEstadoAprobado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = false;
            $arrBtnAnular['disabled'] = false;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($arFactura->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnAprobar['disabled'] = false;
        } else {
            $arrBtnAutorizar['disabled'] = false;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        }
        return $this
            ->createFormBuilder()
            ->add('btnRetirarGuia', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnAutorizar', SubmitType::class, $arrBtnAutorizar)
            ->add('btnAprobar', SubmitType::class, $arrBtnAprobar)
            ->add('btnDesautorizar', SubmitType::class, $arrBtnDesautorizar)
            ->add('btnImprimir', SubmitType::class, $arrBtnImprimir)
            ->add('btnAnular', SubmitType::class, $arrBtnAnular)
            ->getForm();
    }

}

