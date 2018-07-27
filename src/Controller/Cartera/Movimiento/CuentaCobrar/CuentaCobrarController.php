<?php

namespace App\Controller\Cartera\Movimiento\CuentaCobrar;

use App\Entity\Cartera\CarCuentaCobrar;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CuentaCobrarController extends Controller
{
    /**
     * @Route("/cartera/movimiento/cuenta/cobrar/lista", name="cartera_movimiento_cuenta_cobrar_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroNumero')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroNumero', $form->get('txtNumero')->getData());
        }
        $arCuentaCobrar = $paginator->paginate($em->getRepository(CarCuentaCobrar::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('cartera/movimiento/cuentaCobrar/lista.html.twig',
            ['arCuentaCobrar' => $arCuentaCobrar,
            'form' => $form->createView()]);
    }
}

