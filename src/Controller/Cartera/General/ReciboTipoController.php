<?php

namespace App\Controller\Cartera\General;

use App\Controller\General\FuncionesGeneralesController;
use App\Entity\Cartera\CarReciboTipo;
use App\Form\Type\Cartera\ReciboTipoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboTipoController extends Controller
{
    /**
     * @Route("/car/adm/gen/recibo/tipo/lista", name="car_adm_general_recibo_tipo_lista")
     */
    public function lista(Request $request)
    {
        $objMensaje = new FuncionesGeneralesController();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:Cartera\CarReciboTipo')->eliminar($arrSeleccionados);
                if ($respuesta != '') {
                    $objMensaje->Mensaje('error', $respuesta);
                }
                return $this->redirect($this->generateUrl('car_adm_general_recibo_tipo_lista'));
            }
        }
        $query = $em->getRepository(CarReciboTipo::class)->lista();
        $arReciboTipo = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('cartera/general/reciboTipo/lista.html.twig', [
            'form' => $form->createView(), 'arReciboTipo' => $arReciboTipo
        ]);
    }

    /**
     * @Route("/car/adm/gen/recibo/tipo/nuevo/{codigoReciboTipo}", name="car_adm_general_recibo_tipo_nuevo")
     */
    public function nuevo(Request $request, $codigoReciboTipo)
    {
        $em = $this->getDoctrine()->getManager();
        $arReciboTipo = new CarReciboTipo();
        if ($codigoReciboTipo != 0) {
            $arReciboTipo = $em->getRepository('App:Cartera\CarReciboTipo')->find($codigoReciboTipo);
            if (!$arReciboTipo) {
                return $this->redirect($this->generateUrl('car_adm_general_recibo_tipo_lista'));
            }
        }
        $form = $this->createForm(ReciboTipoType::class, $arReciboTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arReciboTipo = $form->getData();
                $em->persist($arReciboTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_recibo_tipo_lista'));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $arReciboTipo = $form->getData();
                $em->persist($arReciboTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_recibo_tipo_nuevo', ['codigoReciboTipo' => 0]));
            }
        }
        return $this->render('cartera/general/reciboTipo/nuevo.html.twig',
            ['arReciboTipo' => $arReciboTipo, 'form' => $form->createView()]);
    }

    private function formularioFiltro()
    {
        return $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->getForm();
    }
}