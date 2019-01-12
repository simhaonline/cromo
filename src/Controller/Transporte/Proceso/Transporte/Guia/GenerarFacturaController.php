<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteOperacion;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class GenerarFacturaController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/generarfactura", name="transporte_proceso_transporte_guia_generarfactura")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtDespachoCodigo', TextType::class, array('required' => false,'data' => $session->get('filtroTteDespachoCodigo')))
            ->add('cboGuiaTipoRel', EntityType::class, $em->getRepository(TteGuiaTipo::class)->llenarCombo())
            ->add('cboOperacionIngresoRel', EntityType::class, $em->getRepository(TteOperacion::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                $session = new session;
                $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $arGuiaTipo = $form->get('cboGuiaTipoRel')->getData();
                if ($arGuiaTipo) {
                    $session->set('filtroTteGuiaCodigoGuiaTipo', $arGuiaTipo->getCodigoGuiaTipoPk());
                } else {
                    $session->set('filtroTteGuiaCodigoGuiaTipo', null);
                }
                $arGuiaTipo = $form->get('cboOperacionIngresoRel')->getData();
                if ($arGuiaTipo) {
                    $session->set('filtroTteGuiaCodigoOperacionIngreso', $arGuiaTipo->getCodigoOperacionPk());
                } else {
                    $session->set('filtroTteGuiaCodigoOperacionIngreso', null);
                }
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteGuia::class)->generarFactura($arrGuias, $this->getUser()->getUsername());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->listaGenerarFactura()->getQuery()->getResult(), "PendienteGenrarFactura");
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaGenerarFactura(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/proceso/transporte/guia/generarFactura.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }
}

