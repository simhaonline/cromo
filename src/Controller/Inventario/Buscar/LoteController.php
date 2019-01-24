<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvLote;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoteController extends Controller
{
    /**
     * @Route("/inventario/buscar/lote/lista/{campoCodigo}/{campoBodega}/{codigoItem}", name="inventario_buscar_lote_lista")
     */
    public function lista(Request $request, $campoCodigo, $campoBodega, $codigoItem, $tipoFactura = false)
    {
        $session = new Session();
        $session->set('filtroInvBuscarLoteItem', $codigoItem);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtBodega', TextType::class, ['required' => false, 'data' => $session->get('filtroInvBuscarBodegaLote')])
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroInvBuscarLoteCodigo')])
            ->add('txtTodos', CheckboxType::class, array('label' => ' ', 'required' => false, 'data' => $session->get('filtroInvBuscarLoteTodos')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBuscarLoteCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroInvBuscarBodegaLote', $form->get('txtBodega')->getData());
                $session->set('filtroInvBuscarLoteTodos', $form->get('txtTodos')->getData());
            }
        }
        $arLotes = $paginator->paginate($em->getRepository(InvLote::class)->lista($tipoFactura), $request->query->get('page', 1), 50);
        return $this->render('inventario/buscar/lote.html.twig', array(
            'arLotes' => $arLotes,
            'campoCodigo' => $campoCodigo,
            'campoBodega' => $campoBodega,
            'form' => $form->createView()
        ));
    }

}

