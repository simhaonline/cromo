<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Form\Type\Transporte\CumplidoType;
use App\Formato\Transporte\Cumplido;
use App\Formato\Transporte\CumplidoEntrega;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CumplidoController extends Controller
{
   /**
    * @Route("/transporte/movimiento/transporte/cumplido/lista", name="transporte_movimiento_transporte_cumplido_lista")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                if ($form->get('txtCodigoCliente')->getData() != '') {
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroTteCodigoCliente', null);
                    $session->set('filtroTteNombreCliente', null);
                }
            }
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteFactura::class)->eliminar($arrSeleccionados);
            }
        }
        $arCumplidos = $paginator->paginate($em->getRepository(TteCumplido::class)->lista(), $request->query->getInt('page', 1),40);
        return $this->render('transporte/movimiento/transporte/cumplido/lista.html.twig', [
            'arCumplidos' => $arCumplidos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/cumplido/detalle/{id}", name="transporte_movimiento_transporte_cumplido_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($id);
        $paginator  = $this->get('knp_paginator');
        $form = Estandares::botonera($arCumplido->getEstadoAutorizado(),$arCumplido->getEstadoAprobado(),$arCumplido->getEstadoAnulado());
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arCumplido->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                if($arCumplido->getCumplidoTipoRel()->getEntregaMercancia()) {
                    $formato = new CumplidoEntrega();
                    $formato->Generar($em, $id);
                } else {
                    $formato = new Cumplido();
                    $formato->Generar($em, $id);
                }

            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteCumplido::class)->autorizar($arCumplido);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteCumplido::class)->desAutorizar($arCumplido);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteCumplido::class)->Aprobar($arCumplido);
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteCumplido::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(TteCumplido::class)->liquidar($id);
                }
            }
            return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_detalle', ['id' => $id]));
        }

        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->cumplido($id);
        return $this->render('transporte/movimiento/transporte/cumplido/detalle.html.twig', [
            'arCumplido' => $arCumplido,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/cumplido/detalle/adicionar/guia/{codigoCumplido}", name="transporte_movimiento_transporte_cumplido_detalle_adicionar_guia")
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
        return $this->render('transporte/movimiento/transporte/cumplido/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/cumplido/nuevo/{id}", name="transporte_movimiento_transporte_cumplido_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arCumplido = new TteCumplido();
        if($id != 0) {
            $arCumplido = $em->getRepository(TteCumplido::class)->find($id);
        }
        $form = $this->createForm(CumplidoType::class, $arCumplido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arCumplido->setClienteRel($arCliente);
                    $fecha = new \DateTime('now');
                    $arCumplido->setFecha($fecha);
                    $em->persist($arCumplido);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_detalle', ['id' => $arCumplido->getCodigoCumplidoPk()]));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/transporte/cumplido/nuevo.html.twig', [
            'arCumplido' => $arCumplido,
            'form' => $form->createView()]);
    }


}

