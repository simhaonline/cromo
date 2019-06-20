<?php

namespace App\Controller\Turno\Movimiento\Comercial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\FacturaType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class FacturaController extends ControllerListenerGeneral
{
    protected $clase = TurFactura::class;
    protected $claseNombre = "TurFactura";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Factura";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/comercial/factura/lista", name="turno_movimiento_comercial_factura_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Pedidos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_comercial_pedido_lista'));
            }
        }
        return $this->render('turno/movimiento/comercial/factura/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/comercial/factura/nuevo/{id}", name="turno_movimiento_comercial_factura_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = new TurFactura();
        if ($id != 0) {
            $arFactura = $em->getRepository(TurPedido::class)->find($id);
        } else {
            $arFactura->setUsuario($this->getUser()->getUserName());
            $arFactura->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(FacturaType::class, $arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TurCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arFactura->setClienteRel($arCliente);
                        $arFactura->setFecha(new \DateTime('now'));
                        if ($id == 0) {
                            $arFactura->setUsuario($this->getUser()->getUserName());
                        }
                        $em->persist($arFactura);
                        $em->flush();
                        return $this->redirect($this->generateUrl('turno_movimiento_comercial_factura_detalle', ['id' => $arFactura->getCodigoFacturaPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }
            }
        }
        return $this->render('turno/movimiento/comercial/factura/nuevo.html.twig', [
            'arFactura' => $arFactura,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/turno/movimiento/comercial/factura/detalle/{id}", name="turno_movimiento_comercial_factura_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPedido = $em->getRepository(TurPedido::class)->find($id);
        $form = Estandares::botonera($arPedido->getEstadoAutorizado(), $arPedido->getEstadoAprobado(), $arPedido->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arPedido->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobado['disabled'] = false;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arPedido->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
        }

        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TurPedido::class)->autorizar($arPedido);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurPedido::class)->desautorizar($arPedido);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TurPedido::class)->aprobar($arPedido);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurPedidoDetalle::class)->eliminar($arPedido, $arrDetallesSeleccionados);
                $em->getRepository(TurPedido::class)->liquidar($arPedido);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurPedidoDetalle::class)->actualizarDetalles($arrControles, $form, $arPedido);
            }
            return $this->redirect($this->generateUrl('turno_movimiento_comercial_pedido_detalle', ['id' => $id]));
        }
        $arPedidoDetalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->lista($id), $request->query->getInt('page', 1), 10);
        return $this->render('turno/movimiento/comercial/pedido/detalle.html.twig', [
            'form' => $form->createView(),
            'arPedidoDetalles' => $arPedidoDetalles,
            'arPedido' => $arPedido
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoPedido
     * @param $codigoPedidoDetalle
     * @return Response
     * @throws \Exception
     * @Route("/turno/movimiento/comercial/pedido/detalle/nuevo/{codigoPedido}/{id}", name="turno_movimiento_comercial_pedido_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoPedido, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = new TurPedidoDetalle();
        $arPedido = $em->getRepository(TurPedido::class)->find($codigoPedido);
        if ($id != '0') {
            $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($id);
        } else {
            $arPedidoDetalle->setPedidoRel($arPedido);
            $arPedidoDetalle->setLunes(true);
            $arPedidoDetalle->setMartes(true);
            $arPedidoDetalle->setMiercoles(true);
            $arPedidoDetalle->setJueves(true);
            $arPedidoDetalle->setViernes(true);
            $arPedidoDetalle->setSabado(true);
            $arPedidoDetalle->setDomingo(true);
            $arPedidoDetalle->setFestivo(true);
            $arPedidoDetalle->setCantidad(1);
            $arPedidoDetalle->setFechaDesde(new \DateTime('now'));
            $arPedidoDetalle->setFechaHasta(new \DateTime('now'));
            $arPedidoDetalle->setVrSalarioBase($arPedido->getVrSalarioBase());
            $arPedidoDetalle->setPeriodo('M');
        }
        $form = $this->createForm(PedidoDetalleType::class, $arPedidoDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arPedidoDetalle->setPorcentajeIva($arPedidoDetalle->getConceptoRel()->getPorcentajeIva());
                    $arPedidoDetalle->setPorcentajeBaseIva(100);
                }
                $em->persist($arPedidoDetalle);
                $em->flush();
                $em->getRepository(TurPedido::class)->liquidar($arPedido);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/movimiento/comercial/pedido/detalleNuevo.html.twig', [
            'arPedido' => $arPedido,
            'form' => $form->createView()
        ]);
    }

}
