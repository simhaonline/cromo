<?php

namespace App\Controller\Cartera\Administracion\Cliente;

use App\Controller\General\FuncionesGeneralesController;
use App\Controller\General\Mensajes;
use App\Entity\Cartera\CarCliente;
use App\Form\Type\Cartera\ClienteType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends Controller
{
    var $query = '';

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
        $this->listado($em);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:Cartera\CarCliente')->eliminar($arrSeleccionados);
                if ($respuesta != '') {
                    $objFunciones->Mensaje('error', $respuesta);
                }
                return $this->redirect($this->generateUrl('car_adm_cliente_lista'));
            }
            if($form->get('btnExcel')->isClicked()){
                $objFunciones->generarExcel($this->query,'Clientes');
            }
        }
        $arCliente = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('cartera/administracion/cliente/lista.html.twig', ['arCliente' => $arCliente,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/car/adm/cliente/nuevo/{id}", name="car_adm_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new CarCliente();
        if ($id != 0) {
            $arCliente = $em->getRepository('App:Cartera\CarCliente')->find($id);
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

    /**
     * @param $em EntityManager
     */
    public function listado($em){
        $this->query= $em->getRepository('App:Cartera\CarCliente')->lista();
    }

    private function formularioFiltro()
    {
        return $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar', 'attr'=> ['class'=>'btn btn-sm btn-danger']))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel','attr'=> ['class'=>'btn btn-sm btn-default']))
            ->getForm();
    }

}