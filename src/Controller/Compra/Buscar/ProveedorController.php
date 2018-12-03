<?php

namespace App\Controller\Compra\Buscar;

use App\Entity\Compra\ComProveedor;
use App\Entity\Inventario\InvTercero;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProveedorController extends Controller
{
    /**
     * @Route("/compra/buscar/proveedor/{campoCodigo}/{campoNombre}/{tipo}", name="compra_buscar_proveedor")
     */
    public function lista(Request $request, $campoCodigo, $campoNombre, $tipo = null)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroInvTerceroNombre')])
            ->add('TxtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroInvTerceroCodigo')])
            ->add('TxtNit', TextType::class, ['required' => false, 'data' => $session->get('filtroInvTerceroIdentificacion')])
            ->add('BtnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroComProveedorCodigo', $form->get('TxtCodigo')->getData());
                $session->set('filtroComProveedorNombre', $form->get('TxtNombre')->getData());
                $session->set('filtroComProveedorIdentificacion', $form->get('TxtNit')->getData());
            }
        }
        $arProveedores = $paginator->paginate($em->getRepository(ComProveedor::class)->lista($tipo), $request->query->get('page', 1), 20);
        return $this->render('compra/buscar/proveedor.html.twig', array(
            'arProveedores' => $arProveedores,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

