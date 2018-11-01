<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Entity\Inventario\InvTercero;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\PedidoType;

class PedidoController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/movimiento/comercial/pedido/lista", name="inventario_movimiento_comercial_pedido_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('cboPedidoTipo', EntityType::class, $em->getRepository(InvPedidoTipo::class)->llenarCombo())
            ->add('numero', TextType::class, array('data' => $session->get('filtroInvPedidoPedidoNumero')))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                    $session->set('filtroInvPedidoPedidoNumero', $form->get('numero')->getData());
                    $session->set('filtroInvCodigoTercero', $form->get('txtCodigoTercero')->getData());
                    $pedidoTipo = $form->get('cboPedidoTipo')->getData();
                    if($pedidoTipo != ''){
                        $session->set('filtroInvPedidoTipo', $form->get('cboPedidoTipo')->getData()->getCodigoPedidoTipoPk());
                    } else {
                        $session->set('filtroInvPedidoTipo', null);
                    }
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->createQuery($em->getRepository(InvPedido::class)->lista())->execute(), "Pedidos");
                }
            }
        }
        $arPedidos = $paginator->paginate($this->getDoctrine()->getRepository(InvPedido::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/comercial/pedido/lista.html.twig', [
            'arPedidos' => $arPedidos,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/comercial/pedido/nuevo/{id}", name="inventario_movimiento_comercial_pedido_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPedido = new InvPedido();
        if ($id != 0) {
            $arPedido = $em->getRepository(InvPedido::class)->find($id);
        }
        $form = $this->createForm(PedidoType::class, $arPedido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arPedido->setTerceroRel($arTercero);
                        $arPedido->setFecha(new \DateTime('now'));
                        if ($id == 0) {
                            $arPedido->setFecha(new \DateTime('now'));
                            $arPedido->setUsuario($this->getUser()->getUserName());
                        }
                        $em->persist($arPedido);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_movimiento_comercial_pedido_detalle', ['id' => $arPedido->getCodigoPedidoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('inventario/movimiento/comercial/pedido/nuevo.html.twig', [
            'form' => $form->createView(),
            'arPedido' => $arPedido
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/comercial/pedido/detalle/{id}", name="inventario_movimiento_comercial_pedido_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPedido = $em->getRepository(InvPedido::class)->find($id);
        $form = Estandares::botonera($arPedido->getEstadoAutorizado(), $arPedido->getEstadoAprobado(), $arPedido->getEstadoAnulado());
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arPedido->getEstadoAutorizado()) {
            $arrBtnActualizarDetalle['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
        }
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvPedido::class)->actualizarDetalles($id, $arrControles);
                $em->getRepository(InvPedido::class)->autorizar($arPedido);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvPedido::class)->desautorizar($arPedido);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatopedido = new Pedido();
                $objFormatopedido->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvPedido::class)->aprobar($arPedido);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(InvPedido::class)->anular($arPedido);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvPedidoDetalle::class)->eliminar($arPedido, $arrDetallesSeleccionados);
                $em->getRepository(InvPedido::class)->liquidar($id);
            }
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                $em->getRepository(InvPedido::class)->actualizarDetalles($id, $arrControles);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_comercial_pedido_detalle', ['id' => $id]));
        }
        $arPedidoDetalles = $paginator->paginate($em->getRepository(InvPedidoDetalle::class)->pedido($id), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/comercial/pedido/detalle.html.twig', [
            'form' => $form->createView(),
            'arPedidoDetalles' => $arPedidoDetalles,
            'arPedido' => $arPedido
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoPedido
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/comercial/pedido/detalle/nuevo/{codigoPedido}", name="inventario_movimiento_comercial_pedido_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoPedido)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arPedido = $em->getRepository(InvPedido::class)->find($codigoPedido);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('txtReferenciaItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
                $session->set('filtroInvBuscarReferenciaNombre', $form->get('txtReferenciaItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arPedido->getTerceroRel()->getCodigoPrecioVentaFk(), $codigoItem);
                            $arPedidoDetalle = new InvPedidoDetalle();
                            $arPedidoDetalle->setPedidoRel($arPedido);
                            $arPedidoDetalle->setItemRel($arItem);
                            $arPedidoDetalle->setCantidad($cantidad);
                            $arPedidoDetalle->setCantidadPendiente($cantidad);
                            $arPedidoDetalle->setVrPrecio(is_array($precioVenta) ? $precioVenta['precio'] : 0);
                            $arPedidoDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                            $em->persist($arPedidoDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvPedido::class)->liquidar($codigoPedido);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/comercial/pedido/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

}
