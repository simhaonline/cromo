<?php

namespace App\Controller\Transporte\Informe\Financiero;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Formato\Transporte\Rentabilidad;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RentabilidadController extends Controller
{
   /**
    * @Route("/transporte/informe/financiero/despacho/rentabilidad", name="transporte_informe_financiero_despacho_rentabilidad")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text'])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $arDespachos = null;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnPdf')->isClicked()) {
                    if($form->get('fechaDesde')->getData() && $form->get('fechaHasta')->getData()) {
                        $arDespachos = $this->getDoctrine()->getRepository(TteDespacho::class)->rentabilidad(
                            $form->get('fechaDesde')->getData()->format('Y-m-d'),
                            $form->get('fechaHasta')->getData()->format('Y-m-d'))->getQuery()->getResult();
                    }
                }
                if ($form->get('btnPdf')->isClicked()) {
                    $formato = new Rentabilidad();
                    $formato->Generar($em, $form->get('fechaDesde')->getData()->format('Y-m-d'),$form->get('fechaHasta')->getData()->format('Y-m-d') );
                }
            }
        }

        return $this->render('transporte/informe/financiero/rentabilidad.html.twig', [
            'arDespachos' => $arDespachos,
            'form' => $form->createView()]);
    }

}

