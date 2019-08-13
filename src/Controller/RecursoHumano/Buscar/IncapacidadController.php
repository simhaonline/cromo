<?php


namespace App\Controller\RecursoHumano\Buscar;


use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuIncapacidadBuscar;
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
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuIncapacidadBuscarNombre')])
            ->add('txtCodigo', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuIncapacidadBuscarCodigo')])
            ->add('txtIdentificacion', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuIncapacidadBuscarIdentificacion')])
            ->add('btnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuIncapacidadBuscarCodigo',$form->get('txtCodigo')->getData());
                $session->set('filtroRhuIncapacidadBuscarNombre',$form->get('txtNombre')->getData());
                $session->set('filtroRhuIncapacidadBuscarIdentificacion',$form->get('txtIdentificacion')->getData());
            }
        }
        $arIncapacidades = $paginator->paginate($em->getRepository(RhuIncapacidad::class)->buscar(), $request->query->get('page', 1), 20);
        return $this->render('recursohumano/buscar/incapacidad.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }
}