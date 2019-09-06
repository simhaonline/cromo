<?php

namespace App\Controller\Inventario\Informe\Compra\Orden;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvRemisionTipo;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrdenDetalleController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/compra/orden/detalle", name="inventario_informe_inventario_compra_orden_detalle")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeOrdenDetalleFechaDesde') ? date_create($session->get('filtroInvInformeOrdenDetalleFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeOrdenDetalleFechaHasta') ? date_create($session->get('filtroIInvInformeOrdenDetalleFechaHasta')) : null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvInformeOrdenDetalleCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroInvInformeOrdenDetalleFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroInvInformeOrdenDetalleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvOrdenDetalle::class)->informeDetalles()->getQuery()->getResult(), "Informe orden detalles");
            }
        }
        $arOrdenDetalles = $paginator->paginate($em->getRepository(InvOrdenDetalle::class)->informeDetalles(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/compra/ordenDetalles.twig', [
            'arOrdenDetalles' => $arOrdenDetalles,
            'form' => $form->createView()
        ]);
    }
}