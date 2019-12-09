<?php

namespace App\Controller\RecursoHumano\Buscar;

use App\Entity\Inventario\InvTercero;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use App\Entity\RecursoHumano\RhuEmpleado;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JuzgadoController extends Controller
{
    /**
     * @param Request $request
     * @param $campoCodigo
     * @param $campoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/buscar/juzgado/{campoCodigo}/{campoNombre}/lista", name="recursohumano_buscar_juzgado")
     */
    public function lista(Request $request, $campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuJuzgadoNombre')])
            ->add('txtCodigo', TextType::class, ['required'  => false,'data' => $session->get('filtroRhuJuzgadoCodigo')])
            ->add('btnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        $raw=[];
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros']=[
                     'nombre' => $form->get('txtNombre')->getData(),
                    'codigoEmbargoJuzgado' => $form->get('txtCodigo')->getData()
                ];
            }
        }
        $arJuzgados = $paginator->paginate($em->getRepository(RhuEmbargoJuzgado::class)->lista($raw), $request->query->get('page', 1), 20);
        return $this->render('recursohumano/buscar/juzgado.html.twig', array(
            'arJuzgados' => $arJuzgados,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

