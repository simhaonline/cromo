<?php

namespace App\Controller\General\Administracion\Calidad;

use App\Entity\General\GenCalidadFormato;
use App\Form\Type\General\CalidadFormatoType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Transporte\TtePrecio;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Form\Type\Transporte\PrecioDetalleType;
use App\Form\Type\Transporte\PrecioType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormatoController extends ControllerListenerGeneral
{
    protected $class = GenCalidadFormato::class;
    protected $claseNombre = "GenCalidadFormato";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "Calidad";
    protected $nombre = "Formato";

    /**
     * @Route("/general/administracion/caldiad/formato/lista", name="general_administracion_calidad_formato_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroGenNombreCalidadFormato')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroGenNombreCalidadFormato', $form->get('txtNombre')->getData());
        }
        $arCalidadFormato = $paginator->paginate($em->getRepository(GenCalidadFormato::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('general/administracion/calidad/lista.html.twig',
            ['arFormatos' => $arCalidadFormato,
                'form' => $form->createView()]);
    }

    /**
     * @Route("/general/administracion/caldiad/formato/nuevo/{id}", name="general_administracion_calidad_formato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCalidadFormato = new GenCalidadFormato();
        if ($id != '0') {
            $arCalidadFormato = $em->getRepository(GenCalidadFormato::class)->find($id);
            if (!$arCalidadFormato) {
                return $this->redirect($this->generateUrl('general_administracion_calidad_formato_lista'));
            }
        }
        $form = $this->createForm(CalidadFormatoType::class, $arCalidadFormato);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCalidadFormato);
                $em->flush();
                return $this->redirect($this->generateUrl('general_administracion_calidad_formato_lista'));
            }
        }
        return $this->render('general/administracion/calidad/nuevo.html.twig', [
            'arCalidadFormato' => $arCalidadFormato,
            'form' => $form->createView()
        ]);
    }

}

