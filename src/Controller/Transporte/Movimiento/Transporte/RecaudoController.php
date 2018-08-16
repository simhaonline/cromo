<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecaudo;
use App\Form\Type\Transporte\RecaudoType;
use App\Formato\Transporte\Recaudo;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecaudoController extends Controller
{
   /**
    * @Route("/transporte/movimiento/transporte/recaudo/lista", name="transporte_movimiento_transporte_recaudo_lista")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
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
                $em->getRepository(TteRecaudo::class)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(TteRecaudo::class)->lista())->execute(), "Guias");
            }
        }
        $arRecaudos = $paginator->paginate($em->getRepository(TteRecaudo::class)->lista(), $request->query->getInt('page', 1),40);
        return $this->render('transporte/movimiento/transporte/recaudo/lista.html.twig', [
            'arRecaudos' => $arRecaudos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudo/detalle/{id}", name="transporte_movimiento_transporte_recaudo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecaudo = $em->getRepository(TteRecaudo::class)->find($id);
        $paginator  = $this->get('knp_paginator');
        $form = Estandares::botonera($arRecaudo->getEstadoAutorizado(),$arRecaudo->getEstadoAprobado(),$arRecaudo->getEstadoAnulado());
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arRecaudo->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {

            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteRecaudo::class)->autorizar($arRecaudo);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteRecaudo::class)->desAutorizar($arRecaudo);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteRecaudo::class)->Aprobar($arRecaudo);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteRecaudo::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(TteRecaudo::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Recaudo();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->recaudo($id), "Recaudos $id");
            }
        }

        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->recaudo($id);
        return $this->render('transporte/movimiento/transporte/recaudo/detalle.html.twig', [
            'arRecaudo' => $arRecaudo,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudo/detalle/adicionar/guia/{codigoRecaudo}", name="transporte_movimiento_transporte_recaudo_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoRecaudo)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecaudo = $em->getRepository(TteRecaudo::class)->find($codigoRecaudo);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setRecaudoRel($arRecaudo);
                    $arGuia->setEstadoRecaudoDevolucion(1);
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteRecaudo::class)->liquidar($codigoRecaudo);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->recaudoPendiente($arRecaudo->getCodigoClienteFk());
        return $this->render('transporte/movimiento/transporte/cumplido/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudo/nuevo/{id}", name="transporte_movimiento_transporte_recaudo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arRecaudo = new TteRecaudo();
        if($id != 0) {
            $arRecaudo = $em->getRepository(TteRecaudo::class)->find($id);
        }
        $form = $this->createForm(RecaudoType::class, $arRecaudo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arRecaudo->setClienteRel($arCliente);
                    $fecha = new \DateTime('now');
                    $arRecaudo->setFecha($fecha);
                    $em->persist($arRecaudo);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_nuevo', array('codigoRecaudo' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_detalle', ['id' => $arRecaudo->getCodigoRecaudoPk()]));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/transporte/recaudo/nuevo.html.twig', [
            'arRecaudo' => $arRecaudo,
            'form' => $form->createView()]);
    }


}

