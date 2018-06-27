<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Controller\Estructura\MensajesController;
use App\Entity\Inventario\Invpedido;
use App\Entity\Inventario\InvpedidoDetalle;
use App\Formato\Inventario\pedido;
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
        $query = $this->getDoctrine()->getRepository(TteDespacho::class)->lista();
        $arDespachos = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/transporte/despacho/lista.html.twig', ['arDespachos' => $arDespachos]);
    }

    /**
     * @Route("/inv/mto/comercial/pedido/nuevo/{id}", name="inv_mto_comercial_pedido_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arpedido = new Invpedido();
        if ($id != 0) {
            $arpedido = $em->getRepository('App:Inventario\Invpedido')->find($id);
            if (!$arpedido) {
                return $this->redirect($this->generateUrl('admin_lista',['modulo' =>'inventario','entidad' => 'pedido']));
            }
        }
        $arpedido->setFecha(new \DateTime('now'));
        $arpedido->setUsuario($this->getUser()->getUserName());
        $form = $this->createForm(pedidoType::class, $arpedido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arpedido->setFecha(new \DateTime('now'));
                $em->persist($arpedido);
                $em->flush($arpedido);
                return $this->redirect($this->generateUrl('listado', ['entidad' => 'Invpedido']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arpedido);
                $em->flush($arpedido);
                return $this->redirect($this->generateUrl('inv_mto_pedido_nuevo', ['codigopedido' => 0]));
            }
        }
        return $this->render('inventario/movimiento/pedido/nuevo.html.twig', [
            'form' => $form->createView(), 'arpedido' => $arpedido
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

    /**
     * @param $arpedido Invpedido
     * @return \Symfony\Component\Form\FormInterface
     */
    private function formularioDetalles($arpedido)
    {
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobar = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnImprimir = ['label' => 'Imprimir', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAnular = ['label' => 'Anular', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arpedido->getEstadoAnulado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($arpedido->getEstadoAprobado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = false;
            $arrBtnAnular['disabled'] = false;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = true;
        } elseif ($arpedido->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobar['disabled'] = false;
        } else {
            $arrBtnAutorizar['disabled'] = false;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnImprimir['disabled'] = true;
            $arrBtnAnular['disabled'] = true;
            $arrBtnEliminar['disabled'] = false;
            $arrBtnAprobar['disabled'] = true;
        }
        return $this
            ->createFormBuilder()
            ->add('btnAutorizar', SubmitType::class, $arrBtnAutorizar)
            ->add('btnAprobar', SubmitType::class, $arrBtnAprobar)
            ->add('btnDesautorizar', SubmitType::class, $arrBtnDesautorizar)
            ->add('btnImprimir', SubmitType::class, $arrBtnImprimir)
            ->add('btnAnular', SubmitType::class, $arrBtnAnular)
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->getForm();
    }

    private function formularioFiltroItems()
    {
        return $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
    }


}
