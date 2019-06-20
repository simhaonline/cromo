<?php


namespace App\Controller\Crm\Administracion\Control\Cliente;


use App\Entity\Crm\CrmCliente;
use App\Entity\Crm\CrmNegocio;
use App\Entity\Transporte\TteCliente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class ClienteController extends Controller
{
    /**
     * @Route("/crm/bus/cliente/{campoCodigo}/{campoNombre}", name="crm_cliente")
     */
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroCrmNombreCliente')))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroCrmCodigoCliente')))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCrmCodigoCliente', $form->get('TxtCodigo')->getData());
            $session->set('filtroCrmNombreCliente', $form->get('TxtNombre')->getData());
        }
        $arClientes = $paginator->paginate($em->getRepository(CrmCliente::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('crm/administracion/control/cliente/cliente.html.twig', array(
            'arClientes' => $arClientes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }
}