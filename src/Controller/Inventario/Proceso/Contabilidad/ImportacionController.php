<?php

namespace App\Controller\Inventario\Proceso\Contabilidad;

use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvImportacion;
use App\Entity\Inventario\InvImportacionTipo;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
class ImportacionController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/proceso/contabilidad/importacion/lista", name="inventario_proceso_contabilidad_importacion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('cboImportacionTipo', EntityType::class, $em->getRepository(InvImportacionTipo::class)->llenarCombo())
            ->add('numero', TextType::class, array('required' => false, 'data' => $session->get('filtroInvImportacionImportacionNumero')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnContabilizar', SubmitType::class, array('label' => 'Contabilizar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvImportacionImportacionNumero', $form->get('numero')->getData());
                $session->set('filtroInvCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $importacionTipo = $form->get('cboImportacionTipo')->getData();
                if ($importacionTipo != '') {
                    $session->set('filtroInvImportacionTipo', $form->get('cboImportacionTipo')->getData()->getCodigoImportacionTipoPk());
                } else {
                    $session->set('filtroInvImportacionTipo', null);
                }
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(InvImportacion::class)->contabilizar($arr);
            }
        }
        $arImportaciones = $paginator->paginate($em->getRepository(InvImportacion::class)->listaContabilizar(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/proceso/contabilidad/importacion/lista.html.twig',
            ['arImportaciones' => $arImportaciones,
            'form' => $form->createView()]);
    }

}

