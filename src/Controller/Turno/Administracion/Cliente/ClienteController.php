<?php

namespace App\Controller\Turno\Administracion\Cliente;

use App\Entity\Turno\TurCliente;
use App\Form\Type\Turno\ClienteType;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends Controller
{
//    protected $class= TurCliente::class;
//    protected $claseNombre = "TurCliente";
//    protected $modulo = "Turno";
//    protected $funcion = "Administracion";
//    protected $grupo = "Cliente";
//    protected $nombre = "Cliente";

    /**
     * @param Request $request
     * @return Response
     * @Route("/turno/administracion/cliente/lista", name="turno_administracion_cliente_cliente_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            if ($form->get('txtCodigoCliente')->getData() != '') {
                $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            } else {
                $session->set('filtroTteCodigoCliente', null);
                $session->set('filtroTteNombreCliente', null);
            }
        }
        $arCliente = $paginator->paginate($em->getRepository(TurCliente::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('turno/administracion/cliente/lista.html.twig',
            ['arCliente' => $arCliente,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/turno/administracion/cliente/nuevo/{id}", name="turno_administracion_cliente_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new TurCliente();
        if ($id != '0') {
            $arCliente = $em->getRepository(TurCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('turno_administracion_cliente_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCliente);
                $em->flush();
//                return $this->redirect($this->generateUrl('transporte_administracion_comercial_cliente_detalle', ['id' => $arCliente->getCodigoClientePk()]));
            }
        }
        return $this->render('turno/administracion/cliente/nuevo.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/cliente/detalle/{id}", name="turno_administracion_cliente_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(TurCliente::class)->find($id);
        return $this->render('turno/administracion/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente
        ));
    }

}

