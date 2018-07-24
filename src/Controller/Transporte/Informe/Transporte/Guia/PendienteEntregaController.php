<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class PendienteEntregaController extends Controller
{
   /**
    * @Route("/transporte/informe/transporte/guia/pendiente/entrega", name="transporte_informe_transporte_guia_pendiente_entrega")
    */    
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->pendienteEntrega(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/transporte/guia/pendienteEntrega.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    private function filtrar($form)
    {
        $session = new session;
        //$session->set('filtroTteCodigoConductor', $form->get('txtCodigoConductor')->getData());
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;

        $form = $this->createFormBuilder()

            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }


}

