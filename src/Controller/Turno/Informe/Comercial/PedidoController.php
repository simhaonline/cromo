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

class PedidoController extends  Controller
{
    /**
     * @Route("/turno/informe/comercial/pedido/lista", name="turno_informe_comercial_pedido_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class,['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasFechaDesde') ? date_create($session->get('filtroInvInformeAsesorVentasFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasFechaHasta') ? date_create($session->get('filtroInvInformeAsesorVentasFechaHasta')): null])
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
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                General::get()->setExportar($em->getRepository(TurPedido::class)->lista(), "Pedidos");
            }
        }
        $arPedidos = $paginator->paginate($em->getRepository(TurPedido::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/pedido.html.twig', [
            'arPedidos' => $arPedidos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/informe/comercial/pedido/detalle/{id}", name="turno_informe_comercial_pedido_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->getForm();
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        $arPedido = $em->getRepository(TurPedido::class)->find($id);
        $arPedidoDetalles = $em->getRepository(TurPedidoDetalle::class)->lista($id);
        return $this->render('turno/informe/comercial/detalle.html.twig', [
            'form' => $form->createView(),
            'arPedidoDetalles' => $arPedidoDetalles,
            'arPedido' => $arPedido
        ]);
    }

    /**
     * @Route("/turno/informe/comercial/pedidosSinAprobar/lista", name="turno_informe_comercial_pedidoSinAprobar_lista")
     */
    public function SinAprobar(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class,['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurInformeComercialPedidoClienteSinAprobarFechaDesde') ? date_create($session->get('filtroInvInformeAsesorVentasFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurInformeComercialPedidoClienteSinAprobarFechaHasta') ? date_create($session->get('filtroInvInformeAsesorVentasFechaHasta')): null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCliente = $em->getRepository(TurCliente::class)->find($form->get('txtCodigoCliente')->getData()??0);
                if ($arCliente) {
                    $session->set('filtroTurInformeComercialPedidoSinAprobarClienteCodigo',  $arCliente->getCodigoClientePk());
                }
                $session->set('filtroTurInformeComercialPedidoClienteSinAprobarFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroTurInformeComercialPedidoClienteSinAprobarFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
        }
        $arPedidos = $paginator->paginate($em->getRepository(TurPedido::class)->listaSinAprobar(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/comercial/PedidosinAprobar.html.twig', [
            'arPedidos' => $arPedidos,
            'form' => $form->createView()
        ]);
    }


}