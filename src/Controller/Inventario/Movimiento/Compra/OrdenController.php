<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMarca;
use App\Entity\Inventario\InvOrden;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvOrdenTipo;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\OrdenType;
use App\Formato\Inventario\OrdenCompra;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use App\General\General;
use App\Formato\Inventario\Orden;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdenController extends AbstractController
{
    protected $class = InvOrden::class;
    protected $claseNombre = "InvOrden";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Compra";
    protected $nombre = "Orden";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/compra/orden/lista", name="inventario_movimiento_compra_orden_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoOrdenPk', TextType::class, array('required' => false))
            ->add('codigoTerceroFk', TextType::class, array('required' => false))
            ->add('codigoOrdenTipoFk', EntityType::class, [
                'class' => InvOrdenTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ot')
                        ->orderBy('ot.codigoOrdenTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvOrden::class)->lista($raw)->getQuery()->execute(), "Orden");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(InvOrden::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_lista'));
            }
        }
        $arOrdenes = $paginator->paginate($em->getRepository(InvOrden::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('inventario/movimiento/compra/orden/lista.html.twig', [
            'arOrdenes' => $arOrdenes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/inventario/movimiento/compra/orden/nuevo/{id}",name="inventario_movimiento_compra_orden_nuevo")
     */
    public function nuevo(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arOrden = new InvOrden();
        if ($id != 0) {
            $arOrden = $em->getRepository(InvOrden::class)->find($id);
        } else {
            $arOrden->setFechaEntrega(new \DateTime('now'));
        }
        $form = $this->createForm(OrdenType::class, $arOrden);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arOrden->setTerceroRel($arTercero);
                        $arOrden->setFecha(new \DateTime('now'));
                        $arOrden->setUsuario($this->getUser()->getUserName());
                        $em->persist($arOrden);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_detalle', ['id' => $arOrden->getCodigoOrdenPk()]));
                    }
                }
            } else {
                Mensajes::error('Debes seleccionar un tercero');
            }
        }
        return $this->render('inventario/movimiento/compra/orden/nuevo.html.twig', [
            'arOrden' => $arOrden,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/compra/orden/detalle/{id}", name="inventario_movimiento_compra_orden_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arOrden = $em->getRepository(InvOrden::class)->find($id);
        $form = Estandares::botonera($arOrden->getEstadoAutorizado(), $arOrden->getEstadoAprobado(), $arOrden->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arOrden->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        }
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvOrdenDetalle::class)->actualizarDetalles($arrControles, $arOrden);
                $em->getRepository(InvOrden::class)->autorizar($arOrden);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvOrden::class)->desautorizar($arOrden);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvOrden::class)->aprobar($arOrden);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_detalle', ['id' => $id]));
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(InvOrdenDetalle::class)->actualizarDetalles($arrControles, $arOrden);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatoOrdenCompra = new OrdenCompra();
                $objFormatoOrdenCompra->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(InvOrden::class)->anular($arOrden);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrDetallesSeleccionados) {
                    $em->getRepository(InvOrdenDetalle::class)->eliminar($arOrden, $arrDetallesSeleccionados);
                    $em->getRepository(InvOrden::class)->liquidar($arOrden);
                    return $this->redirect($this->generateUrl('inventario_movimiento_compra_orden_detalle', ['id' => $id]));
                }
            }
        }
        $arOrdenDetalles = $paginator->paginate($em->getRepository(InvOrdenDetalle::class)->lista($arOrden->getCodigoOrdenPk()), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/movimiento/compra/orden/detalle.html.twig', [
            'form' => $form->createView(),
            'arOrdenDetalles' => $arOrdenDetalles,
            'arOrden' => $arOrden
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/compra/orden/detalle/nuevo/{id}", name="inventario_movimiento_compra_orden_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, PaginatorInterface $paginator,$id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arOrden = $em->getRepository(InvOrden::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemNombre')])
            ->add('txtReferenciaItem', TextType::class, ['label' => 'Referencia: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemReferencia')])
            ->add('cboMarcaItem', EntityType::class, $em->getRepository(InvMarca::class)->llenarCombo())
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
                $arMarca = $form->get('cboMarcaItem')->getData();
                if ($arMarca != '') {
                    $session->set('filtroInvMarcaItem', $form->get('cboMarcaItem')->getData()->getCodigoMarcaPk());
                } else {
                    $session->set('filtroInvMarcaItem', null);
                }
                $session->set('filtroInvBuscarItemReferencia', $form->get('txtReferenciaItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $arOrdenDetalle = new InvOrdenDetalle();
                            $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arOrden->getTerceroRel()->getCodigoPrecioCompraFk(), $codigoItem);
                            $arOrdenDetalle->setVrPrecio($precioVenta);
                            $arOrdenDetalle->setOrdenRel($arOrden);
                            $arOrdenDetalle->setItemRel($arItem);
                            $arOrdenDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                            $arOrdenDetalle->setCantidad($cantidad);
                            $arOrdenDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arOrdenDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvOrden::class)->liquidar($id);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/movimiento/compra/orden/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/compra/orden/solicitud/detalle/nuevo/{id}", name="inventario_movimiento_compra_orden_solicitud_detalle_nuevo")
     */
    public function detalleNuevoSolicitud(Request $request, PaginatorInterface $paginator,$id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => $session->get('filtroInvBucarItemCodigo')])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroInvBuscarItemNombre')])
            ->add('txtCodigoSolicitud', TextType::class, ['label' => 'Codigo solicitud: ', 'required' => false, 'data' => $session->get('filtroInvSolicitudCodigo')])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $respuesta = '';
        $arOrden = $em->getRepository(InvOrden::class)->findOneBy(['codigoOrdenPk' => $id]);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSolicitudDetalles = $request->request->get('itemCantidad');
                if ($arrSolicitudDetalles) {
                    if (count($arrSolicitudDetalles) > 0) {
                        foreach ($arrSolicitudDetalles as $codigoSolicitudDetalle => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0 && $cantidad > 0) {
                                $arSolicitudDetalle = $em->getRepository(InvSolicitudDetalle::class)->find($codigoSolicitudDetalle);
                                if ($cantidad <= $arSolicitudDetalle->getCantidadPendiente()) {
                                    $arItem = $em->getRepository('App:Inventario\InvItem')->find($arSolicitudDetalle->getCodigoItemFk());
                                    $arOrdenDetalle = new InvOrdenDetalle();
                                    $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arOrden->getTerceroRel()->getCodigoPrecioCompraFk(), $arItem->getCodigoItemPk());
                                    $arOrdenDetalle->setVrPrecio($precioVenta);
                                    $arOrdenDetalle->setOrdenRel($arOrden);
                                    $arOrdenDetalle->setItemRel($arItem);
                                    $arOrdenDetalle->setCantidadPendiente($cantidad);
                                    $arOrdenDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                                    $arOrdenDetalle->setCantidad($cantidad);
                                    $arOrdenDetalle->setSolicitudDetalleRel($arSolicitudDetalle);
                                    $arSolicitudDetalle->setCantidadPendiente($arSolicitudDetalle->getCantidadPendiente() - $cantidad);
                                    $em->persist($arSolicitudDetalle);
                                    $em->persist($arOrdenDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada.";
                                }
                            }
                            if ($respuesta != '') {
                                Mensajes::error($respuesta);
                            } else {
                                $em->flush();
                                $em->getRepository(InvOrden::class)->liquidar($id);
                                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                            }
                        }
                    }
                }
            }
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
                $session->set('filtroInvSolicitudCodigo', $form->get('txtCodigoSolicitud')->getData());
            }
        }

        $arSolicitudesDetalles = $paginator->paginate($em->getRepository(InvSolicitudDetalle::class)->listarDetallesPendientes(), $request->query->getInt('page', 1), 10);
        if (count($arSolicitudesDetalles) == 0) {
            Mensajes::error('No hay solicitudes pendientes o no se encuentran aprobadas');
        }
        return $this->render('inventario/movimiento/compra/orden/detalleNuevoSolicitud.html.twig', [
            'form' => $form->createView(),
            'arSolicitudesDetalles' => $arSolicitudesDetalles
        ]);
    }

    public function getFiltros($form)
        {
            $filtro = [
                'numero' => $form->get('numero')->getData(),
                'codigoOrden' => $form->get('codigoOrdenPk')->getData(),
                'codigoTercero' => $form->get('codigoTerceroFk')->getData(),
                'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
                'estadoAprobado' => $form->get('estadoAprobado')->getData(),
                'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            ];

            $arOrdenTipoFk = $form->get('codigoOrdenTipoFk')->getData();

            if (is_object($arOrdenTipoFk)) {
                $filtro['ordenTipo'] = $arOrdenTipoFk->getCodigoOrdenTipoPk();
            } else {
                $filtro['ordenTipoFk'] = $arOrdenTipoFk;
            }

            return $filtro;

        }
}
