<?php

namespace App\Controller\Turno\Movimiento\Venta;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenImpuesto;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurFacturaDetalle;
use App\Entity\Turno\TurItem;
use App\Form\Type\Turno\FacturaType;
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
    protected $grupo = "Venta";
    protected $nombre = "Factura";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/comercial/factura/lista", name="turno_movimiento_venta_factura_lista")
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
                return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_lista'));
            }
        }
        return $this->render('turno/movimiento/comercial/factura/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/comercial/factura/nuevo/{id}", name="turno_movimiento_venta_factura_nuevo")
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
                        return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $arFactura->getCodigoFacturaPk()]));
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
     * @Route("/turno/movimiento/comercial/factura/detalle/{id}", name="turno_movimiento_venta_factura_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TurFactura::class)->find($id);
        $form = Estandares::botonera($arFactura->getEstadoAutorizado(), $arFactura->getEstadoAprobado(), $arFactura->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arFactura->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobado['disabled'] = false;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arFactura->getEstadoAprobado()) {
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
                $em->getRepository(TurFactura::class)->autorizar($arFactura);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurFactura::class)->desautorizar($arFactura);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TurFactura::class)->aprobar($arFactura);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurFacturaDetalle::class)->eliminar($arFactura, $arrDetallesSeleccionados);
                $em->getRepository(TurFactura::class)->liquidar($arFactura);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurFacturaDetalle::class)->actualizarDetalles($arrControles, $form, $arFactura);
            }
            return $this->redirect($this->generateUrl('turno_movimiento_venta_factura_detalle', ['id' => $id]));
        }
        $arImpuestosIva = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'I'));
        $arImpuestosRetencion = $em->getRepository(GenImpuesto::class)->findBy(array('codigoImpuestoTipoFk' => 'R'));
        $arFacturaDetalles = $paginator->paginate($em->getRepository(TurFacturaDetalle::class)->lista($id), $request->query->getInt('page', 1), 10);
        return $this->render('turno/movimiento/comercial/factura/detalle.html.twig', [
            'form' => $form->createView(),
            'arFacturaDetalles' => $arFacturaDetalles,
            'arFactura' => $arFactura,
            'arImpuestosIva' => $arImpuestosIva,
            'arImpuestosRetencion' => $arImpuestosRetencion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/turno/movimiento/comercial/factura/detalle/nuevo/{id}", name="turno_movimiento_venta_factura_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        /**
         * @var $arItem TurItem
         */
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $respuesta = '';
        $arFactura = $em->getRepository(TurFactura::class)->find($id);
        $form = $this->createFormBuilder()
//            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
//            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemNombre')])
//            ->add('txtReferenciaItem', TextType::class, ['label' => 'Referencia: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemReferencia')])
//            ->add('itemConExistencia', CheckboxType::class, array('label' => ' ', 'required' => false, 'data' => $session->get('itemConExistencia')))
//            ->add('itemConDisponibilidad', CheckboxType::class, array('label' => ' ', 'required' => false, 'data' => $session->get('filtroItemConDisponibilidad')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        $arItem = $em->getRepository(TurItem::class)->find($codigoItem);
                        if ($cantidad != '' && $cantidad != 0) {
                            $arFacturaDetalle = New TurFacturaDetalle();
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setItemRel($arItem);
                            $arFacturaDetalle->setCantidad($cantidad);
                            $arFacturaDetalle->setCodigoImpuestoRetencionFk($arItem->getCodigoImpuestoRetencionFk());
                            $arFacturaDetalle->setCodigoImpuestoIvaFk($arItem->getCodigoImpuestoIvaVentaFk());
                            $arFacturaDetalle->setPorcentajeIva($arItem->getImpuestoIvaVentaRel()->getPorcentaje());
                            $em->persist($arFacturaDetalle);
                        }
                    }
                    if ($respuesta == '') {
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    } else {
                        Mensajes::error($respuesta);
                    }
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(TurItem::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('turno/movimiento/comercial/factura/detalleNuevo.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }
}

