<?php

namespace App\Controller\Inventario\Informe\Compras\OrdenCompra;

use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Entity\Inventario\InvOrdenCompraTipo;
use App\General\General;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class OrdenCompraPendienteController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/compras/ordenCompraPendiente", name="inventario_informe_inventario_compras_ordenCompraPendientes")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumeroOrdenCompra', NumberType::class, ['required' => false, 'data' => $session->get('filtroInvOrdenCompraNumero')])
            ->add('ordenCompraTipoRel',EntityType::class,$em->getRepository(InvOrdenCompraTipo::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel','attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arOrdenCompraTipo = $form->get('ordenCompraTipoRel')->getData();
                if($arOrdenCompraTipo){
                    $arOrdenCompraTipo = $arOrdenCompraTipo->getCodigoOrdenCompraTipoPk();
                }
                $session->set('filtroInvCodigoOrdenCompraTipo', $arOrdenCompraTipo);
                $session->set('filtroInvOrdenCompraNumero', $form->get('txtNumeroOrdenCompra')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvOrdenCompraDetalle::class)->informeOrdenCompraPendientes()->getQuery()->getResult(), "Informe ordenes de compra pendientes");
            }
        }
        $arOrdenCompraDetalles = $paginator->paginate($em->getRepository(InvOrdenCompraDetalle::class)->informeOrdenCompraPendientes(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/ordenCompra/ordenCompraPendientes.html.twig', [
            'arOrdenCompraDetalles' => $arOrdenCompraDetalles,
            'form' => $form->createView()
        ]);
    }
}

