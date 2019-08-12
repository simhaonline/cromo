<?php


namespace App\Controller\RecursoHumano\Buscar;


use App\Entity\RecursoHumano\RhuIncapacidad;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class IncapacidadController extends Controller
{
    /**
     * @param Request $request
     * @param $campoCodigo
     * @param $campoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/buscar/prorrooga/{campoCodigo}/lista", name="recursohumano_buscar_incapacidad")
     */
    public function lista(Request $request, $campoCodigo)
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
        $arIncapacidades = $paginator->paginate($em->getRepository(RhuIncapacidad::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('recursohumano/buscar/incapacidad.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }
}