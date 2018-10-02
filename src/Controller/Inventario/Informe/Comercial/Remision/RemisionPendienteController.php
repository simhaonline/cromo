<?php

namespace App\Controller\Inventario\Informe\Comercial\Remision;

use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvRemisionTipo;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RemisionPendienteController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/comercial/remision/pendiente", name="inventario_informe_inventario_comercial_remision_pendiente")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('remisionTipoRel', EntityType::class, $em->getRepository(InvRemisionTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arRemisionTipo = $form->get('remisionTipoRel')->getData();
                if ($arRemisionTipo) {
                    $arRemisionTipo = $arRemisionTipo->getCodigoRemisionTipoPk();
                }
                $session->set('filtroInvRemisionTipo', $arRemisionTipo);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvRemisionDetalle::class)->pendientes()->execute(), "Informe remisiones pendientes");
            }
        }
        $arRemisionDetalles = $paginator->paginate($em->getRepository(InvRemisionDetalle::class)->pendientes(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/comercial/remision/remisionesPendientes.twig', [
            'arRemisionDetalles' => $arRemisionDetalles,
            'form' => $form->createView()
        ]);
    }
}