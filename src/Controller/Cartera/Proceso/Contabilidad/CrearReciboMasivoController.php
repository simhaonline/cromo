<?php

namespace App\Controller\Cartera\Proceso\Contabilidad;

use App\Entity\Cartera\CarCuentaCobrarTipo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CrearReciboMasivoController extends Controller
{
    /**
     * @Route("/cartera/proceso/contabilidad/crearrecibomasivo/lista", name="cartera_proceso_contabilidad_crearrecibomasivo_lista")
     */
    public function lista(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $session=new Session();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('cboCuentaCobrarTipoRel',EntityType::class,$em->getRepository(CarCuentaCobrarTipo::class)->llenarCombo())
            ->add('btnFiltrar',SubmitType::class,['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCuentaCobrarTipo = $form->get('cboCuentaCobrarTipoRel')->getData();
                if ($arCuentaCobrarTipo) {
                    $session->set('filtroCarCuentaCobrarTipo', $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk());
                } else {
                    $session->set('filtroCarCuentaCobrarTipo', null);
                }
            }
        }
        $arCrearReciboMasivos=$paginator->paginate($em->getRepository('App:Cartera\CarCuentaCobrar')->crearReciboMasivoLista(), $request->query->getInt('page', 1),100);
        return $this->render('cartera/proceso/contabilidad/crearrecibomasivo/lista.html.twig', [
            'arCrearReciboMasivos' => $arCrearReciboMasivos,
            'form'                 => $form->createView()
        ]);
    }
}
