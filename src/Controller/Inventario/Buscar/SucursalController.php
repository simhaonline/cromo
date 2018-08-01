<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvSucursal;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SucursalController extends Controller
{
   /**
    * @Route("/inventario/buscar/sucursal/lista/{campoCodigo}/{campoDireccion}/{codigoTercero}", name="inventario_buscar_sucursal_lista", defaults={"codigoTercero":0})
    */    
    public function lista(Request $request, $campoCodigo, $campoDireccion, $codigoTercero)
    {
        $session = new Session();
        $session->set('filtroInvBuscarSucursalCodigoTercero',$codigoTercero);
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtDireccion', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarSucursalDireccion')])
            ->add('txtContacto', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarSucursalContacto')])
            ->add('btnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroInvBuscarSucursalDireccion',$form->get('TxtDireccion')->getData());
                $session->set('filtroInvBuscarSucursalContacto',$form->get('TxtContacto')->getData());
            }
        }
        $arSucursales = $paginator->paginate($em->getRepository(InvSucursal::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('inventario/buscar/sucursal.html.twig', array(
            'arSucursales' => $arSucursales,
            'campoCodigo' => $campoCodigo,
            'campoDireccion' => $campoDireccion,
            'form' => $form->createView()
        ));
    }

}

