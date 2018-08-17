<?php

namespace App\Controller\Cartera\Proceso\Contabilidad;

use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ReciboController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/proceso/contabilidad/recibo/lista", name="cartera_proceso_contabilidad_recibo_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroCarReciboFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroCarReciboFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroCarReciboFechaHasta'))])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroCarCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('cboReciboTipoRel', EntityType::class, $em->getRepository(CarReciboTipo::class)->llenarCombo())
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroCarReciboFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroCarReciboFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroCarReciboFiltroFecha', $form->get('filtrarFecha')->getData());
                if ($form->get('txtCodigoCliente')->getData() != '') {
                    $session->set('filtroCarCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroCarNombreCliente', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroCarCodigoCliente', null);
                    $session->set('filtroCarNombreCliente', null);
                }
                $arReciboTipo = $form->get('cboReciboTipoRel')->getData();
                if ($arReciboTipo) {
                    $session->set('filtroCarReciboCodigoReciboTipo', $arReciboTipo->getCodigoReciboTipoPk());
                } else {
                    $session->set('filtroCarReciboCodigoReciboTipo', null);
                }
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(CarRecibo::class)->contabilizar($arr);
            }
        }
        $arRecibos = $paginator->paginate($em->getRepository(CarRecibo::class)->listaContabilizar(), $request->query->getInt('page', 1),100);
        return $this->render('cartera/proceso/contabilidad/recibo/lista.html.twig',
            ['arRecibos' => $arRecibos,
            'form' => $form->createView()]);
    }

}

