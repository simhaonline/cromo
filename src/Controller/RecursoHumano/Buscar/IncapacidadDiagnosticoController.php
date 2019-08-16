<?php


namespace App\Controller\RecursoHumano\Buscar;


use App\Entity\RecursoHumano\RhuIncapacidadDiagnostico;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IncapacidadDiagnosticoController extends Controller
{
    /**
     * @param Request $request
     * @param $campoCodigo
     * @param $campoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/buscar/incapacidad/diagnostico/{campoCodigo}/{campoNombre}/lista", name="recursohumano_buscar_incapacidad_diagnostico")
     */
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required'  => false,'data' => $session->get('RhuIncapacidadDiagnosticoCodigo')])
            ->add('txtCodigoEps', TextType::class, ['required'  => false,'data' => $session->get('RhuIncapacidadDiagnosticoCodigoEps')])
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('RhuIncapacidadDiagnosticoNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnFiltrar')->isClicked()) {
                $session->set('RhuIncapacidadDiagnosticoCodigo',$form->get('txtCodigo')->getData());
                $session->set('RhuIncapacidadDiagnosticoCodigoEps',$form->get('txtCodigoEps')->getData());
                $session->set('RhuIncapacidadDiagnosticoNombre',$form->get('txtNombre')->getData());
            }
        }
        $arIncapacidadesDiagnosticos = $paginator->paginate($em->getRepository(RhuIncapacidadDiagnostico::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('recursohumano/buscar/incapacidadDiagnostico.html.twig', array(
            'arIncapacidadesDiagnosticos' => $arIncapacidadesDiagnosticos,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }
}