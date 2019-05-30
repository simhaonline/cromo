<?php

namespace App\Controller\Turno\Buscar;

use App\Entity\Turno\TurCliente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends Controller
{
    /**
     * @param Request $request
     * @param $campoCodigo
     * @param $campoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/turno/buscar/cliente/{campoCodigo}/{campoNombre}", name="turno_buscar_cliente")
     */
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $formFiltro = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteNombre')))
            ->add('txtCodigo', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteCodigo')))
            ->add('txtNit', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteIdentificacion')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurClienteCodigo', $formFiltro->get('txtCodigo')->getData());
                $session->set('filtroTurClienteNombre', $formFiltro->get('txtNombre')->getData());
                $session->set('filtroTurClienteIdentificacion', $formFiltro->get('txtNit')->getData());
            }
        }
        $arClientes = $paginator->paginate($em->getRepository(TurCliente::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('turno/buscar/cliente.html.twig', [
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $formFiltro->createView()
        ]);
    }
}

