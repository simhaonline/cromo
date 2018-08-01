<?php

namespace App\Controller\Cartera\Informe\CuentaCobrar\CuentaCobrar;

use App\Entity\Cartera\CarCuentaCobrar;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CuentaCobrarController extends Controller
{
   /**
    * @Route("/Cartera/informe/cuentaCobrar/CuentaCobrar/lista", name="cartera_informe_cuentaCobrar_cuentaCobrar_lista")
    */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $fecha = new \DateTime('now');
        if($session->get('filtroFechaDesde') == "") {
            $session->set('filtroFechaDesde', $fecha->format('Y-m-d'));
        }
        if($session->get('filtroFechaHasta') == "") {
            $session->set('filtroFechaHasta', $fecha->format('Y-m-d'));
        }
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroCarNombreCliente', $form->get('txtNombreCorto')->getData());
        }
//        if ($form->get('btnExcel')->isClicked()) {
//            General::get()->setExportar($em->createQuery($em->getRepository(CarCuentaCobrar::class)->lista())->execute(), "Novedades");
//        }
        $query = $this->getDoctrine()->getRepository(CarCuentaCobrar::class)->lista();
        $arCuentasCobrar = $paginator->paginate($query, $request->query->getInt('page', 1),100);
        return $this->render('cartera/informe/cuentaCobrar.html.twig', [
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView()]);
    }
}

