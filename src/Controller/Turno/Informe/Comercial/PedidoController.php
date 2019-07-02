<?php


namespace App\Controller\Turno\Informe\Comercial;


use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use App\Entity\Turno\TurPedido;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PedidoController extends  Controller
{
    /**
    * @Route("/turno/informe/comerical/pedido", name="turno_informe_comercial_pedido")
    */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('pedidoTipoRel', EntityType::class, $em->getRepository(InvPedidoTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arPedidoTipo = $form->get('pedidoTipoRel')->getData();
                if ($arPedidoTipo) {
                    /** @var  $arPedidoTipo InvPedidoTipo */
                    $arPedidoTipo = $arPedidoTipo->getCodigoPedidoTipoPk();
                }
                $session->set('filtroInvPedidoTipo', $arPedidoTipo);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvPedidoDetalle::class)->pendientes()->execute(), "Informe pedidos pendientes");
            }
        }
        $arPedidoDetalles = $paginator->paginate($em->getRepository(TurPedido::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/pedido.html.twig', [
            'arPedidoDetalles' => $arPedidoDetalles,
            'form' => $form->createView()
        ]);
    }
}