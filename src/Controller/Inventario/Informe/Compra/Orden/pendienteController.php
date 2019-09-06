<?php

namespace App\Controller\Inventario\Informe\Compra\Orden;

use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Entity\Inventario\InvOrdenCompraTipo;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvOrdenTipo;
use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class pendienteController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/compra/orden/pendiente", name="inventario_informe_inventario_compra_orden_pendiente")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumero', NumberType::class, ['required' => false, 'data' => $session->get('filtroInvOrdenNumero')])
            ->add('ordenTipoRel',EntityType::class,$em->getRepository(InvOrdenTipo::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel','attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arOrdenCompraTipo = $form->get('ordenTipoRel')->getData();
                if($arOrdenCompraTipo){
                    $arOrdenCompraTipo = $arOrdenCompraTipo->getCodigoOrdenCompraTipoPk();
                }
                $session->set('filtroInvCodigoOrdenTipo', $arOrdenCompraTipo);
                $session->set('filtroInvOrdenNumero', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvOrdenDetalle::class)->informeOrdenCompraPendientes()->getQuery()->getResult(), "Informe ordenes de compra pendientes");
            }
        }
        $arOrdenCompraDetalles = $paginator->paginate($em->getRepository(InvOrdenDetalle::class)->informeOrdenCompraPendientes(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/compra/pendiente.html.twig', [
            'arOrdenCompraDetalles' => $arOrdenCompraDetalles,
            'form' => $form->createView()
        ]);
    }
}

