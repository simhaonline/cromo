<?php

namespace App\Controller\Transporte\Movimiento\Recogida\Recogida;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Entity\Transporte\TteRecogida;
use App\Form\Type\Transporte\RecogidaType;
use App\Formato\Transporte\Recogida;
use App\Utilidades\Estandares;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecogidaController extends Controller
{
   /**
    * @Route("/transporte/movimiento/recogida/recogida/lista", name="transporte_movimiento_recogida_recogida_lista")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroTteRecogidaCodigo')])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('chkEstadoProgramado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteRecogidaEstadoProgramado'), 'required' => false])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                    $session->set('filtroTteRecogidaEstadoProgramado', $form->get('chkEstadoProgramado')->getData());
                    $session->set('filtroTteRecogidaCodigo', $form->get('txtCodigo')->getData());
                }
                if($form->get('btnEliminar')->isClicked()){
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository(TteRecogida::class)->eliminar($arrSeleccionados);
                }
            }
        }
        $query = $this->getDoctrine()->getRepository(TteRecogida::class)->lista();
        $arRecogidas = $paginator->paginate($query, $request->query->getInt('page', 1),20);
        return $this->render('transporte/movimiento/recogida/recogida/lista.html.twig', [
            'arRecogidas' => $arRecogidas ,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/recogida/detalle/{id}", name="transporte_movimiento_recogida_recogida_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = $em->getRepository(TteRecogida::class)->find($id);
        $form = Estandares::botonera($arRecogida->getEstadoAutorizado(),$arRecogida->getEstadoAprobado(),$arRecogida->getEstadoAnulado());
        $form->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Recogida();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteRecogida::class)->autorizar($arRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteRecaudoDevolucion::class)->desAutorizar($arRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteRecaudoDevolucion::class)->Aprobar($arRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_detalle', ['id' => $id]));
            }
        }

        return $this->render('transporte/movimiento/recogida/recogida/detalle.html.twig', [
            'arRecogida' => $arRecogida,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/recogida/nuevo/{id}", name="transporte_movimiento_recogida_recogida_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = new TteRecogida();
        if ($id != 0) {
            $arRecogida = $em->getRepository(TteRecogida::class)->find($id);
        } else {
            $arRecogida->setFechaRegistro(new \DateTime('now'));
            $arRecogida->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RecogidaType::class, $arRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRecogida = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arRecogida->setClienteRel($arCliente);
                    $arRecogida->setOperacionRel($this->getUser()->getOperacionRel());
                    $arRecogida->setAnunciante($arCliente->getNombreCorto());
                    $arRecogida->setDireccion($arCliente->getDireccion());
                    $arRecogida->setTelefono($arCliente->getTelefono());
                    $em->persist($arRecogida);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_nuevo', array('id' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_lista'));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/recogida/recogida/nuevo.html.twig', ['arRecogida' => $arRecogida,'form' => $form->createView()]);
    }

}

