<?php

namespace App\Controller\Compra\Proceso\Contabilizar;

use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoTipo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class EgresoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/compra/proceso/contabilidad/egreso/lista", name="compra_proceso_contabilidad_egreso_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroCcomEgresoFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroComEgresoFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroComEgresoFechaHasta'))])
            ->add('txtCodigoProveedor', TextType::class, ['required' => false, 'data' => $session->get('filtroComCodigoProveedor'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroComNombreProveedor'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('cboEgresoTipoRel', EntityType::class, $em->getRepository(ComEgresoTipo::class)->llenarCombo())
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroComEgresoFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroComEgresoFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroCcomEgresoFiltroFecha', $form->get('filtrarFecha')->getData());
                if ($form->get('txtCodigoProveedor')->getData() != '') {
                    $session->set('filtroComCodigoProveedor', $form->get('txtCodigoProveedor')->getData());
                    $session->set('filtroComNombreProveedor', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroComCodigoProveedor', null);
                    $session->set('filtroComNombreProveedor', null);
                }
                $arReciboTipo = $form->get('cboEgresoTipoRel')->getData();
//                if ($arReciboTipo) {
//                    $session->set('filtroCarReciboCodigoReciboTipo', $arReciboTipo->getCodigoReciboTipoPk());
//                } else {
//                    $session->set('filtroCarReciboCodigoReciboTipo', null);
//                }
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(ComEgreso::class)->contabilizar($arr);
            }
        }
        $arEgresos = $paginator->paginate($em->getRepository(ComEgreso::class)->listaContabilizar(), $request->query->getInt('page', 1), 100);
        return $this->render('compra/proceso/contabilizar/egreso/lista.html.twig',
            ['arEgresos' => $arEgresos,
                'form' => $form->createView()]);
    }
}
