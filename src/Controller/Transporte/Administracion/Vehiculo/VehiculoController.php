<?php

namespace App\Controller\Transporte\Administracion\Vehiculo;

use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\VehiculoType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/transporte/administracion/vehiculo/lista", name="transporte_administracion_transporte_vehiculo_lista")
     */
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $query = $this->getDoctrine()->getRepository(TteVehiculo::class)->lista();
        $arVehiculo = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/administracion/vehiculo/lista.html.twig', ['arVehiculo' => $arVehiculo]);
    }

    /**
     * @Route("/transporte/administracion/vehiculo/nuevo/{id}", name="transporte_administracion_transporte_vehiculo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVehiculo = new TteVehiculo();
        if ($id != '0') {
            $arVehiculo = $em->getRepository(TteVehiculo::class)->find($id);
            if (!$arVehiculo) {
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
            }
        }
        $form = $this->createForm(VehiculoType::class, $arVehiculo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arVehiculo);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_detalle', ['modulo' => 'transporte','entidad' => 'vehiculo','id'=> $arVehiculo->getCodigoVehiculoPk()]));
            }
        }
        return $this->render('transporte/administracion/vehiculo/nuevo.html.twig', [
            'arVehiculo' => $arVehiculo,
            'form' => $form->createView()
        ]);
    }

}

