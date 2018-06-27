<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Formato\Inventario\Pedido;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use http\Env\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\pedidoType;

class PedidoController extends Controller
{
    var $query = '';

    /**
     * @Route("/inv/mto/comercial/pedido/lista", name="inv_mto_comercial_pedido_lista")
     */
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                }
                if ($form->get('btnExcel')->isClicked()) {
                    $this->filtrar($form);
                    $query = $this->getDoctrine()->getRepository(TteGuia::class)->lista();
                    General::get()->setExportar($query, "Guias");
                }
            }
        }
        $query = $this->getDoctrine()->getRepository(InvPedido::class)->lista();
        $arPedidos = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('inventario/movimiento/comercial/pedido/lista.html.twig', [
            'arPedidos' => $arPedidos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/inv/mto/comercial/pedido/nuevo/{id}", name="inv_mto_comercial_pedido_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPedido = new InvPedido();
        if ($id != 0) {
            $arPedido = $em->getRepository('App:Inventario\Invpedido')->find($id);
        }
        $form = $this->createForm(PedidoType::class, $arPedido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arPedido->setFecha(new \DateTime('now'));
                if($id == 0) {
                    $arPedido->setFecha(new \DateTime('now'));
                    $arPedido->setUsuario($this->getUser()->getUserName());
                }
                $em->persist($arPedido);
                $em->flush($arPedido);
                return $this->redirect($this->generateUrl('inv_mto_comercial_pedido_detalle', ['id' => $arPedido->getCodigoPedidoPk()]));
            }
        }
        return $this->render('inventario/movimiento/comercial/pedido/nuevo.html.twig', [
            'form' => $form->createView(),
            'arPedido' => $arPedido
        ]);
    }

    /**
     * @Route("/inv/mto/comercial/pedido/detalle/{id}", name="inv_mto_comercial_pedido_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $objFormatopedido = new pedido();
        $em = $this->getDoctrine()->getManager();
        $arpedido = $em->getRepository('App:Inventario\Invpedido')->find($id);
        $arpedidoDetalles = $em->getRepository('App:Inventario\InvpedidoDetalle')->findBy(['codigopedidoFk' => $id]);
        $form = $this->formularioDetalles($arpedido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App:Inventario\Invpedido')->autorizar($arpedido);
                return $this->redirect($this->generateUrl('inv_mov_comercial_pedido_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App:Inventario\Invpedido')->desautorizar($arpedido);
                return $this->redirect($this->generateUrl('inv_mov_comercial_pedido_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatopedido->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository('App:Inventario\Invpedido')->aprobar($arpedido);
                return $this->redirect($this->generateUrl('inv_mov_comercial_pedido_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\Invpedido')->anular($arpedido);
                if($respuesta != ''){
                    MensajesController::error($respuesta);
                }
                return $this->redirect($this->generateUrl('inv_mov_comercial_pedido_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvpedidoDetalle')->eliminar($arpedido, $arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('inv_mov_comercial_pedido_detalle', ['id' => $id]));
            }
        }
        return $this->render('inventario/movimiento/compra/pedido/detalle.html.twig', [
            'form' => $form->createView(),
            'arpedidoDetalles' => $arpedidoDetalles,
            'arpedido' => $arpedido
        ]);
    }

    /**
     * @Route("/inv/mto/comercial/pedido/detalle/nuevo/{id}", name="inv_mto_comercial_pedido_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arpedido = $em->getRepository('App:Inventario\Invpedido')->find($id);
        $form = $this->formularioFiltroItems();
        $form->handleRequest($request);
        $this->listaItems($em, $form);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $this->listaItems($em, $form);
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository('App:Inventario\InvItem')->find($codigoItem);
                            $arpedidoDetalle = new InvpedidoDetalle();
                            $arpedidoDetalle->setpedidoRel($arpedido);
                            $arpedidoDetalle->setItemRel($arItem);
                            $arpedidoDetalle->setCantidadSolicitada($cantidad);
                            $arpedidoDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arpedidoDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/pedido/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }


    private function filtrar($form)
    {
        /*$session = new session;
        $arRuta = $form->get('guiaTipoRel')->getData();
        if ($arRuta) {
            $session->set('filtroTteCodigoGuiaTipo', $arRuta->getCodigoGuiaTipoPk());
        } else {
            $session->set('filtroTteCodigoGuiaTipo', null);
        }
        $arServicio = $form->get('servicioRel')->getData();
        if ($arServicio) {
            $session->set('filtroTteCodigoServicio', $arServicio->getCodigoServicioPk());
        } else {
            $session->set('filtroTteCodigoServicio', null);
        }
        $session->set('filtroTteDocumento', $form->get('documento')->getData());
        $session->set('filtroTteNumeroGuia', $form->get('numero')->getData());*/
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;

        $form = $this->createFormBuilder()
            ->add('numero', TextType::class, array('data' => $session->get('filtroInvNumeroPedido')))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }

}
