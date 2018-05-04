<?php

namespace App\Controller\Cartera\Administracion\Cliente;

use App\Controller\General\FuncionesGeneralesController;
use App\Controller\General\Mensajes;
use App\Entity\Cartera\CarCliente;
use App\Form\Type\Cartera\ClienteType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends Controller
{
    /**
     * @Route("/car/adm/cliente/lista", name="car_adm_cliente_lista")
     */
    public function lista(Request $request)
    {
        $objFunciones = new FuncionesGeneralesController();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:Cartera\CarCliente')->eliminar($arrSeleccionados);
                if ($respuesta != '') {
                    $objFunciones->Mensaje('error', $respuesta);
                }
                return $this->redirect($this->generateUrl('car_adm_cliente_lista'));
            }
//            if($form->get('BtnExcel')->isClicked()){
//                $objFunciones->generarExcel();
//            }
        }
        $query = $em->getRepository(CarCliente::class)->lista();
        $arCliente = $paginator->paginate($query, $request->query->getInt('page', 1), 10);
        return $this->render('cartera/administracion/cliente/lista.html.twig', ['arCliente' => $arCliente,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/car/adm/cliente/nuevo/{codigoCliente}", name="car_adm_cliente_nuevo")
     */
    public function nuevo(Request $request, $codigoCliente)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new CarCliente();
        if ($codigoCliente != 0) {
            $arCliente = $em->getRepository('App:Cartera\CarCliente')->find($codigoCliente);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('car_adm_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente = $form->getData();
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_cliente_lista'));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $arCliente = $form->getData();
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_cliente_nuevo', ['codigoCliente' => 0]));
            }
        }
        return $this->render('cartera/administracion/cliente/nuevo.html.twig',
            ['arCliente' => $arCliente, 'form' => $form->createView()]);
    }

    private function formularioFiltro()
    {
        return $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
            ->getForm();
    }

}