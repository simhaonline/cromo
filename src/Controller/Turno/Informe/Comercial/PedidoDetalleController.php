<?php


namespace App\Controller\Turno\Informe\Comercial;

use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PedidoDetalleController extends  Controller
{
    /**
     * @Route("/turno/informe/comercial/pedidoDetalle/lista", name="turno_informe_comercial_pedidoDetalle_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
           ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCliente = $em->getRepository(TurCliente::class)->find($form->get('txtCodigoCliente')->getData()??0);
                if ($arCliente) {
                    $session->set('filtroTurInformeComercialPedidoClienteCodigo',  $arCliente->getCodigoClientePk());
                }
                $session->set('filtroTurInformeComercialPedidoClienteCodigoFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroTurInformeComercialPedidoClienteCodigoFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
        }
        $arPedidosDetalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/pedidoDetalle.html.twig', [
            'arPedidosDetalles' => $arPedidosDetalles,
            'form' => $form->createView()
        ]);
    }
}