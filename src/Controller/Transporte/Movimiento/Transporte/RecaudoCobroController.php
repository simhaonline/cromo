<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Entity\Transporte\TteRecaudoCobro;
use App\Form\Type\Transporte\RecaudoCobroType;
use App\Form\Type\Transporte\RecaudoType;
use App\Formato\Transporte\Recaudo;
use App\Formato\Transporte\RecaudoCobro;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecaudoCobroController extends Controller
{
   /**
    * @Route("/transporte/movimiento/transporte/recaudoCobro/lista", name="transporte_movimiento_transporte_recaudo_cobro_lista")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
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
                $em->getRepository(TteRecaudoCobro::class)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(TteRecaudoCobro::class)->lista())->execute(), "Guias");
            }
        }
        $arRecaudoCobros = $paginator->paginate($em->getRepository(TteRecaudoCobro::class)->lista(), $request->query->getInt('page', 1),40);
        return $this->render('transporte/movimiento/transporte/recaudoCobro/lista.html.twig', [
            'arRecaudoCobros' => $arRecaudoCobros,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudoCobro/detalle/{id}", name="transporte_movimiento_transporte_recaudo_cobro_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecaudoCobro = $em->getRepository(TteRecaudoCobro::class)->find($id);
        $paginator  = $this->get('knp_paginator');
        $form = Estandares::botonera($arRecaudoCobro->getEstadoAutorizado(),$arRecaudoCobro->getEstadoAprobado(),$arRecaudoCobro->getEstadoAnulado());
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arRecaudoCobro->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {

            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteRecaudoCobro::class)->autorizar($arRecaudoCobro);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_cobro_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteRecaudoCobro::class)->desAutorizar($arRecaudoCobro);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_cobro_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteRecaudoCobro::class)->Aprobar($arRecaudoCobro);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_cobro_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteRecaudoCobro::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(TteRecaudoCobro::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_cobro_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new RecaudoCobro();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->recaudoCobro($id), "Recaudos $id");
            }
        }

        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->recaudoCobro($id);
        return $this->render('transporte/movimiento/transporte/recaudoCobro/detalle.html.twig', [
            'arRecaudoCobro' => $arRecaudoCobro,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudoCobro/detalle/adicionar/guia/{id}", name="transporte_movimiento_transporte_recaudo_cobro_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecaudoCobro = $em->getRepository(TteRecaudoCobro::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setRecaudoCobroRel($arRecaudoCobro);
                    $arGuia->setEstadoRecaudoCobro(1);
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteRecaudoCobro::class)->liquidar($id);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->recaudoCobroPendiente();
        return $this->render('transporte/movimiento/transporte/recaudoCobro/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudoCobro/nuevo/{id}", name="transporte_movimiento_transporte_recaudo_cobro_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecaudoCobro = new TteRecaudoCobro();
        if($id != 0) {
            $arRecaudoCobro = $em->getRepository(TteRecaudoCobro::class)->find($id);
        }
        $form = $this->createForm(RecaudoCobroType::class, $arRecaudoCobro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fecha = new \DateTime('now');
            $arRecaudoCobro->setFecha($fecha);
            $em->persist($arRecaudoCobro);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_cobro_nuevo', array('codigoRecaudoCobro' => 0)));
            } else {
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudo_cobro_detalle', ['id' => $arRecaudoCobro->getCodigoRecaudoCobroPk()]));
            }
        }
        return $this->render('transporte/movimiento/transporte/recaudoCobro/nuevo.html.twig', [
            'arRecaudoCobro' => $arRecaudoCobro,
            'form' => $form->createView()]);
    }


}

