<?php

namespace App\Controller\RecursoHumano\Buscar;

use App\Entity\Inventario\InvTercero;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmpleadoController extends Controller
{
    /**
     * @param Request $request
     * @param $campoCodigo
     * @param $campoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/buscar/empleado/{campoCodigo}/{campoNombre}/lista", name="recursohumano_buscar_empleado")
     */
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuEmpleadoNombre')])
            ->add('txtCodigo', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuEmpleadoCodigo')])
            ->add('txtIdentificacion', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuEmpleadoIdentificacion')])
            ->add('chkEstadoContrato', CheckboxType::class, ['label' => ' ','required'  => false,'data' => $session->get('filtroRhuEmpleadoEstadoContrato')])
            ->add('btnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuEmpleadoCodigo',$form->get('txtCodigo')->getData());
                $session->set('filtroRhuEmpleadoNombre',$form->get('txtNombre')->getData());
                $session->set('filtroRhuEmpleadoIdentificacion',$form->get('txtIdentificacion')->getData());
                $session->set('filtroRhuEmpleadoEstadoContrato',$form->get('chkEstadoContrato')->getData());
            }
        }
        $arEmpleados = $paginator->paginate($em->getRepository(RhuEmpleado::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('recursoHumano/buscar/empleado.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

