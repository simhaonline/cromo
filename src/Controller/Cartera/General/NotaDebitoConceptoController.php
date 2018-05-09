<?php

namespace App\Controller\Cartera\General;

use App\Controller\General\FuncionesGeneralesController;
use App\Entity\Cartera\CarNotaDebitoConcepto;
use App\Form\Type\Cartera\NotaDebitoConceptoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NotaDebitoConceptoController extends Controller
{
    /**
     * @Route("/car/adm/gen/nota/debito/concepto/lista", name="car_adm_general_nota_debito_concepto_lista")
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
                $respuesta = $em->getRepository('App:Cartera\CarNotaDebitoConcepto')->eliminar($arrSeleccionados);
                if ($respuesta != '') {
                    $objMensaje->Mensaje('error', $respuesta);
                }
                return $this->redirect($this->generateUrl('car_adm_general_nota_debito_concepto_lista'));
            }
        }
        $query = $em->getRepository(CarNotaDebitoConcepto::class)->lista();
        $arNotaDebitoConcepto = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('cartera/general/notaDebitoConcepto/lista.html.twig', [
            'form' => $form->createView(), 'arNotaDebitoConcepto' => $arNotaDebitoConcepto
        ]);
    }

    /**
     * @Route("/car/adm/gen/nota/debito/concepto/nuevo/{codigoNotaDebitoConcepto}", name="car_adm_general_nota_debito_concepto_nuevo")
     */
    public function nuevo(Request $request, $codigoNotaDebitoConcepto)
    {
        $em = $this->getDoctrine()->getManager();
        $arNotaDebitoConcepto = new CarNotaDebitoConcepto();
        if ($codigoNotaDebitoConcepto != 0) {
            $arNotaDebitoConcepto = $em->getRepository('App:Cartera\CarNotaDebitoConcepto')->find($codigoNotaDebitoConcepto);
            if (!$arNotaDebitoConcepto) {
                return $this->redirect($this->generateUrl('car_adm_general_nota_debito_concepto_lista'));
            }
        }
        $form = $this->createForm(NotaDebitoConceptoType::class, $arNotaDebitoConcepto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arNotaDebitoConcepto = $form->getData();
                $em->persist($arNotaDebitoConcepto);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_nota_debito_concepto_lista'));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $arNotaDebitoConcepto = $form->getData();
                $em->persist($arNotaDebitoConcepto);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_nota_debito_concepto_nuevo', ['codigoNotaDebitoConcepto' => 0]));
            }
        }
        return $this->render('cartera/general/notaDebitoConcepto/nuevo.html.twig',
            ['arNotaDebitoConcepto' => $arNotaDebitoConcepto, 'form' => $form->createView()]);
    }

    private function formularioFiltro()
    {
        return $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->getForm();
    }
}