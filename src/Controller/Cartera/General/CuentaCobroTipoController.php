<?php

namespace App\Controller\Cartera\General;

use App\Controller\General\FuncionesGeneralesController;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Form\Type\Cartera\CuentaCobrarTipoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuentaCobroTipoController extends Controller
{
    /**
     * @Route("/car/adm/gen/cuenta/cobrar/tipo/lista", name="car_adm_general_cuenta_cobrar_tipo_lista")
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
                $respuesta = $em->getRepository('App:Cartera\CarCuentaCobrarTipo')->eliminar($arrSeleccionados);
                if ($respuesta != '') {
                    $objMensaje->Mensaje('error', $respuesta);
                }
                return $this->redirect($this->generateUrl('car_adm_general_cuenta_cobrar_tipo_lista'));
            }
        }
        $query = $em->getRepository(CarCuentaCobrarTipo::class)->lista();
        $arCuentaCobrarTipo = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('cartera/general/cuentaCobroTipo/lista.html.twig', [
            'form' => $form->createView(), 'arCuentaCobrarTipo' => $arCuentaCobrarTipo
        ]);
    }

    /**
     * @Route("/car/adm/gen/cuenta/cobrar/tipo/nuevo/{codigoCuentaCobrarTipo}", name="car_adm_general_cuenta_cobrar_tipo_nuevo")
     */
    public function nuevo(Request $request, $codigoCuentaCobrarTipo)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuentaCobrarTipo = new CarCuentaCobrarTipo();
        if ($codigoCuentaCobrarTipo != 0) {
            $arCuentaCobrarTipo = $em->getRepository('App:Cartera\CarCuentaCobrarTipo')->find($codigoCuentaCobrarTipo);
            if (!$arCuentaCobrarTipo) {
                return $this->redirect($this->generateUrl('car_adm_general_cuenta_cobrar_tipo_lista'));
            }
        }
        $form = $this->createForm(CuentaCobrarTipoType::class, $arCuentaCobrarTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCuentaCobrarTipo = $form->getData();
                $em->persist($arCuentaCobrarTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_cuenta_cobrar_tipo_lista'));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $arCuentaCobrarTipo = $form->getData();
                $em->persist($arCuentaCobrarTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_general_cuenta_cobrar_tipo_nuevo', ['codigoCuentaCobrarTipo' => 0]));
            }
        }
        return $this->render('cartera/general/cuentaCobroTipo/nuevo.html.twig',
            ['arCuentaCobrarTipo' => $arCuentaCobrarTipo, 'form' => $form->createView()]);
    }

    private function formularioFiltro()
    {
        return $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->getForm();
    }
}