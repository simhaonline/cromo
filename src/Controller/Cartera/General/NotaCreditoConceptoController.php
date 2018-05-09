<?php

namespace App\Controller\Cartera\General;

use App\Controller\General\FuncionesGeneralesController;
use App\Entity\Cartera\CarNotaCreditoConcepto;
use App\Form\Type\Cartera\NotaCreditoConceptoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NotaCreditoConceptoController extends Controller
{
    /**
     * @Route("/car/adm/gen/nota/credito/concepto/lista", name="car_adm_general_nota_credito_concepto_lista")
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
                $respuesta = $em->getRepository('App:Cartera\CarNotaCreditoConcepto')->eliminar($arrSeleccionados);
                if ($respuesta != '') {
                    $objMensaje->Mensaje('error', $respuesta);
                }
                return $this->redirect($this->generateUrl('car_adm_general_nota_credito_concepto_lista'));
            }
        }
        $query = $em->getRepository(CarNotaCreditoConcepto::class)->lista();
        $arNotaCreditoConcepto = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('cartera/general/notaCreditoConcepto/lista.html.twig', [
            'form' => $form->createView(), 'arNotaCreditoConcepto' => $arNotaCreditoConcepto
        ]);
    }

    /**
     * @Route("/car/adm/gen/nota/credito/concepto/nuevo/{codigoNotaCreditoConcepto}", name="car_adm_general_nota_credito_concepto_nuevo")
     */
    public function nuevo(Request $request, $codigoNotaCreditoConcepto)
    {
        $em = $this->getDoctrine()->getManager();
        $arNotaCreditoConcepto = new CarNotaCreditoConcepto();
        if ($codigoNotaCreditoConcepto != 0) {
            $arNotaCreditoConcepto = $em->getRepository('App:Cartera\CarNotaCreditoConcepto')->find($codigoNotaCreditoConcepto);
            if (!$arNotaCreditoConcepto) {
                return $this->redirect($this->generateUrl('car_adm_general_nota_credito_concepto_lista'));
            }
        }
        $form = $this->createForm(NotaCreditoConceptoType::class, $arNotaCreditoConcepto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arNotaCreditoConcepto = $form->getData();
                $em->persist($arNotaCreditoConcepto);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_nota_credito_concepto_lista'));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $arNotaCreditoConcepto = $form->getData();
                $em->persist($arNotaCreditoConcepto);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_nota_credito_concepto_nuevo', ['codigoNotaCreditoConcepto' => 0]));
            }
        }
        return $this->render('cartera/general/notaCreditoConcepto/nuevo.html.twig',
            ['arNotaCreditoConcepto' => $arNotaCreditoConcepto, 'form' => $form->createView()]);
    }

    private function formularioFiltro()
    {
        return $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->getForm();
    }
}