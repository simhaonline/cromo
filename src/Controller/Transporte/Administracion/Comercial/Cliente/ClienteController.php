<?php

namespace App\Controller\Transporte\Administracion\Comercial\Cliente;

use App\Entity\Transporte\TteCliente;
use App\Form\Type\Transporte\ClienteType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/administracion/comercial/cliente/lista", name="transporte_administracion_comercial_cliente_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arCliente = $paginator->paginate($em->getRepository(TteCliente::class)->lista(), $request->query->getInt('page', 1),10);
        return $this->render('transporte/administracion/comercial/cliente/lista.html.twig', ['arCliente' => $arCliente]);
    }

    /**
     * @Route("/transporte/administracion/comercial/cliente/nuevo/{id}", name="transporte_administracion_comercial_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new TteCliente();
        if ($id != '0') {
            $arCliente = $em->getRepository(TteCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente->setNombreCorto($arCliente->getNombre1() . " " . $arCliente->getNombre2() . " " . $arCliente->getApellido1() . " " . $arCliente->getApellido2());
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_detalle', ['modulo' => 'transporte','entidad' => 'cliente','id'=> $arCliente->getCodigoClientePk()]));
            }
        }
        return $this->render('transporte/administracion/comercial/cliente/nuevo.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/administracion/comercial/cliente/detalle/{id}", name="transporte_administracion_comercial_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(TteCliente::class)->find($id);

        return $this->render('transporte/administracion/comercial/cliente/detalle.html.twig', array(
            'arCliente' => $arCliente,
        ));
    }

}

