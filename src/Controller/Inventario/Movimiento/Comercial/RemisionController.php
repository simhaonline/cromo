<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvPedidoTipo;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Entity\Inventario\InvRemision;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvRemisionTipo;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\RemisionType;
use App\Formato\Inventario\Pedido;
use App\Formato\Inventario\Remision;
use App\Formato\Inventario\Remision2;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\PedidoType;

class RemisionController extends AbstractController
{
    protected $class = InvRemision::class;
    protected $claseNombre = "InvRemision";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Remision";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/comercial/remision/lista", name="inventario_movimiento_comercial_remision_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoRemisionPk', TextType::class, array('required' => false))
            ->add('codigoTerceroFk', TextType::class, array('required' => false))
            ->add('codigoRemisionTipoFk', EntityType::class, [
                'class' => InvRemisionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.codigoRemisionTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoAsesorFk', EntityType::class, [
                'class' => GenAsesor::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.codigoAsesorPk', 'ASC');
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

        if ($form->isSubmitted() ) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvRemision::class)->lista($raw), "Remisiones");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(InvRemision::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_remision_lista'));
            }
        }
        $arRemisiones = $paginator->paginate($em->getRepository(InvRemision::class)->lista($raw, $this->getUser()), $request->query->getInt('page', 1), 30);

        return $this->render('inventario/movimiento/comercial/remision/lista.html.twig', [
            'arRemisiones' => $arRemisiones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/comercial/remision/nuevo/{id}", name="inventario_movimiento_comercial_remision_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRemision = new InvRemision();
        if ($id != 0) {
            $arRemision = $em->getRepository(InvRemision::class)->find($id);
        }
        $form = $this->createForm(RemisionType::class, $arRemision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arRemision->setTerceroRel($arTercero);
                        $arRemision->setFecha(new \DateTime('now'));
                        if ($id == 0) {
                            $arRemision->setFecha(new \DateTime('now'));
                            $arRemision->setUsuario($this->getUser()->getUserName());
                        }
                        $arRemision->setOperacionInventario($arRemision->getRemisionTipoRel()->getOperacionInventario());
                        $em->persist($arRemision);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_movimiento_comercial_remision_detalle', ['id' => $arRemision->getCodigoRemisionPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('inventario/movimiento/comercial/remision/nuevo.html.twig', [
            'form' => $form->createView(),
            'arRemision' => $arRemision
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
     * @Route("/inventario/movimiento/comercial/remision/detalle/{id}", name="inventario_movimiento_comercial_remision_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRemision = $em->getRepository(InvRemision::class)->find($id);
        $form = Estandares::botonera($arRemision->getEstadoAutorizado(), $arRemision->getEstadoAprobado(), $arRemision->getEstadoAnulado());
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arRemision->getEstadoAutorizado()) {
            $arrBtnActualizarDetalle['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
        }
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvRemision::class)->actualizarDetalles($id, $arrControles);
                $em->getRepository(InvRemision::class)->autorizar($arRemision, $this->getUser()->getUsername());
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvRemision::class)->desautorizar($arRemision);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $codigoRemision = $em->getRepository(InvConfiguracion::class)->find(1)->getCodigoFormatoRemision();
                if ($codigoRemision == 1) {
                    $objFormatoRemision = new Remision();
                    $objFormatoRemision->Generar($em, $id);
                }
                if ($codigoRemision == 2) {
                    $objFormatoRemision = new Remision2();
                    $objFormatoRemision->Generar($em, $id);
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvRemision::class)->aprobar($arRemision);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(InvRemision::class)->anular($arRemision);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvRemisionDetalle::class)->eliminar($arRemision, $arrDetallesSeleccionados);
                $em->getRepository(InvRemision::class)->liquidar($id);
            }
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                $em->getRepository(InvRemision::class)->actualizarDetalles($id, $arrControles);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_comercial_remision_detalle', ['id' => $id]));
        }
        $arRemisionDetalles = $paginator->paginate($em->getRepository(InvRemisionDetalle::class)->remision($id), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/comercial/remision/detalle.html.twig', [
            'form' => $form->createView(),
            'clase' => array('clase' => 'InvRemision', 'codigo' => $id),
            'arRemisionDetalles' => $arRemisionDetalles,
            'arRemision' => $arRemision
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoRemision
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/comercial/remision/detalle/nuevo/{codigoRemision}", name="inventario_movimiento_comercial_remision_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, PaginatorInterface $paginator,$codigoRemision)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arRemision = $em->getRepository(InvRemision::class)->find($codigoRemision);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('txtReferenciaItem', TextType::class, ['label' => 'Referencia: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnGuardarCerrar', SubmitType::class, ['label' => 'Guardar y cerrar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' =>null
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $codigoItem = null;
                if (!is_null($form->get('txtCodigoItem')->getData())){

                    if (is_numeric($form->get('txtCodigoItem')->getData() ) ){
                    $codigoItem = $form->get('txtCodigoItem')->getData();
                    } else {
                        Mensajes::error("El codigo del item debe ser numerico");
                    }
                }

                $raw['filtros'] =[
                    'codigoItem' => $codigoItem,
                    'nombreItem' =>$form->get('txtNombreItem')->getData(),
                    'referenciaItem' => $form->get('txtReferenciaItem')->getData()
                ];
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arRemision->getTerceroRel()->getCodigoPrecioVentaFk(), $codigoItem);
                            $arRemisionDetalle = new InvRemisionDetalle();
                            $arRemisionDetalle->setRemisionRel($arRemision);
                            $arRemisionDetalle->setItemRel($arItem);
                            $arRemisionDetalle->setCantidad($cantidad);
                            $arRemisionDetalle->setCantidadPendiente($cantidad);
                            $arRemisionDetalle->setCantidadOperada($cantidad * $arRemision->getOperacionInventario());
                            $arRemisionDetalle->setVrPrecio($precioVenta);
                            $arRemisionDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                            $arRemisionDetalle->setOperacionInventario($arRemision->getOperacionInventario());
                            $em->persist($arRemisionDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvRemision::class)->liquidar($codigoRemision);
                    echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();</script>";
                }
            }
            if ($form->get('btnGuardarCerrar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arRemision->getTerceroRel()->getCodigoPrecioVentaFk(), $codigoItem);
                            $arRemisionDetalle = new InvRemisionDetalle();
                            $arRemisionDetalle->setRemisionRel($arRemision);
                            $arRemisionDetalle->setItemRel($arItem);
                            $arRemisionDetalle->setCantidad($cantidad);
                            $arRemisionDetalle->setCantidadPendiente($cantidad);
                            $arRemisionDetalle->setCantidadOperada($cantidad * $arRemision->getOperacionInventario());
                            $arRemisionDetalle->setVrPrecio($precioVenta);
                            $arRemisionDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                            $arRemisionDetalle->setOperacionInventario($arRemision->getOperacionInventario());
                            $em->persist($arRemisionDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvRemision::class)->liquidar($codigoRemision);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista($raw), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/comercial/remision/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    /**
     * @Route("/inventario/movimiento/comercial/remision/detalle/pedido/nuevo/{id}", name="inventario_movimiento_comercial_remision_pedido_detalle_nuevo")
     */
    public function detalleNuevoPedido(Request $request, PaginatorInterface $paginator,$id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class, array('required' => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnGuardarCerrar', SubmitType::class, ['label' => 'Guardar y cerrar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $arRemision = $em->getRepository(InvRemision::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvPedidoNumero', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrDetalles = $request->request->get('itemCantidad');
                if ($arrDetalles) {
                    if (count($arrDetalles) > 0) {
                        $respuesta = "";
                        foreach ($arrDetalles as $codigo => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($codigo);
                                if ($cantidad <= $arPedidoDetalle->getCantidadPendiente()) {
                                    $arRemisionDetalle = new InvRemisionDetalle();
                                    $arRemisionDetalle->setRemisionRel($arRemision);
                                    $arRemisionDetalle->setItemRel($arPedidoDetalle->getItemRel());
                                    $arRemisionDetalle->setCantidad($cantidad);
                                    $arRemisionDetalle->setCantidadPendiente($cantidad);
                                    $arRemisionDetalle->setCantidadOperada($cantidad * $arRemision->getOperacionInventario());
                                    $arRemisionDetalle->setVrPrecio($arPedidoDetalle->getVrPrecio());
                                    $arRemisionDetalle->setOperacionInventario($arRemision->getOperacionInventario());
                                    //$arMovimientoDetalle->setPorcentajeDescuento($arPedidoDetalle->getPorcentajeDescuento());
                                    $arRemisionDetalle->setPorcentajeIva($arPedidoDetalle->getPorcentajeIva());
                                    $arRemisionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                    $em->persist($arRemisionDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada en el id " . $codigo;
                                }
                            }
                        }
                        if ($respuesta != '') {
                            Mensajes::error($respuesta);
                        } else {
                            $em->flush();
                            $em->getRepository(InvRemision::class)->liquidar($arRemision);
                            echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();</script>";
                        }
                    }
                }
            }
            if ($form->get('btnGuardarCerrar')->isClicked()) {
                $arrDetalles = $request->request->get('itemCantidad');
                if ($arrDetalles) {
                    if (count($arrDetalles) > 0) {
                        $respuesta = "";
                        foreach ($arrDetalles as $codigo => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arPedidoDetalle = $em->getRepository(InvPedidoDetalle::class)->find($codigo);
                                if ($cantidad <= $arPedidoDetalle->getCantidadPendiente()) {
                                    $arRemisionDetalle = new InvRemisionDetalle();
                                    $arRemisionDetalle->setRemisionRel($arRemision);
                                    $arRemisionDetalle->setItemRel($arPedidoDetalle->getItemRel());
                                    $arRemisionDetalle->setCantidad($cantidad);
                                    $arRemisionDetalle->setCantidadOperada($cantidad * $arRemision->getOperacionInventario());
                                    $arRemisionDetalle->setVrPrecio($arPedidoDetalle->getVrPrecio());
                                    $arRemisionDetalle->setOperacionInventario($arRemision->getOperacionInventario());
                                    //$arMovimientoDetalle->setPorcentajeDescuento($arPedidoDetalle->getPorcentajeDescuento());
                                    $arRemisionDetalle->setPorcentajeIva($arPedidoDetalle->getPorcentajeIva());
                                    $arRemisionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                    $em->persist($arRemisionDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada en el id " . $codigo;
                                }
                            }
                        }
                        if ($respuesta != '') {
                            Mensajes::error($respuesta);
                        } else {
                            $em->flush();
                            $em->getRepository(InvRemision::class)->liquidar($arRemision);
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                        }
                    }
                }
            }
        }
        $arPedidoDetalles = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvPedidoDetalle::class)->listarDetallesPendientes($arRemision->getCodigoTerceroFk()), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/comercial/remision/detalleNuevoPedido.html.twig', [
            'form' => $form->createView(),
            'arPedidoDetalles' => $arPedidoDetalles
        ]);
    }

    /**
     * @Route("/inventario/movimiento/comercial/remision/detalle/remision/nuevo/{id}", name="inventario_movimiento_comercial_remision_remision_detalle_nuevo")
     */
    public function detalleNuevoRemision(Request $request,  PaginatorInterface $paginator,$id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class, array('required' => false))
            ->add('txtLote', TextType::class, array('required' => false))
            ->add('cboBodega', EntityType::class, $em->getRepository(InvBodega::class)->llenarCombo())
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnGuardarCerrar', SubmitType::class, ['label' => 'Guardar y cerrar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => null
        ];
        $arRemision = $em->getRepository(InvRemision::class)->find($id);
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] =[
                    'numero'=>$form->get('txtNumero')->getData(),
                    'lote'=>$form->get('txtLote')->getData()
                ];
                $arBodega = $form->get('cboBodega')->getData();
                if (is_object($arBodega)) {
                    $raw['filtros'] = array_merge($raw['filtros'], ['bodega' => $arBodega->getCodigoBodegaPk()]);
                } else {
                    $raw['filtros'] = array_merge($raw['filtros'], ['bodega' => null]);
                }
            }
            if ($form->get('btnGuardar')->isClicked() || $form->get('btnGuardarCerrar')->isClicked()) {
                $arrDetalles = $request->request->get('itemCantidad');
                if ($arrDetalles) {
                    if ($arrDetalles) {
                        $respuesta = "";
                        foreach ($arrDetalles as $codigo => $cantidad) {
                            if ($cantidad != '' && $cantidad != 0) {
                                $arRemisionDetalleOrigen = $em->getRepository(InvRemisionDetalle::class)->find($codigo);
                                if ($cantidad <= $arRemisionDetalleOrigen->getCantidadPendiente()) {
                                    $arRemisionDetalle = new InvRemisionDetalle();
                                    $arRemisionDetalle->setRemisionRel($arRemision);
                                    $arRemisionDetalle->setItemRel($arRemisionDetalleOrigen->getItemRel());
                                    $arRemisionDetalle->setCantidad($cantidad);
                                    $arRemisionDetalle->setCantidadOperada($cantidad * $arRemision->getOperacionInventario());
                                    $arRemisionDetalle->setCantidadPendiente($cantidad);
                                    $arRemisionDetalle->setVrPrecio($arRemisionDetalleOrigen->getVrPrecio());
                                    $arRemisionDetalle->setOperacionInventario($arRemision->getOperacionInventario());
                                    $arRemisionDetalle->setPorcentajeIva($arRemisionDetalleOrigen->getPorcentajeIva());
                                    $arRemisionDetalle->setRemisionDetalleRel($arRemisionDetalleOrigen);
                                    $arRemisionDetalle->setLoteFk($arRemisionDetalleOrigen->getLoteFk());
                                    $arRemisionDetalle->setFechaVencimiento($arRemisionDetalleOrigen->getFechaVencimiento());
                                    $arRemisionDetalle->setCodigoBodegaFk($arRemisionDetalleOrigen->getCodigoBodegaFk());
                                    $em->persist($arRemisionDetalle);
                                } else {
                                    $respuesta = "Debe ingresar una cantidad menor o igual a la solicitada en el id " . $codigo;
                                }
                            }
                        }
                        if ($respuesta != '') {
                            Mensajes::error($respuesta);
                        } else {
                            $em->flush();
                            $em->getRepository(InvRemision::class)->liquidar($arRemision);
                            echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();</script>";
                            if ($form->get('btnGuardarCerrar')->isClicked()) {
                                echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
                            }
                        }
                    }
                }
            }
        }
        $arRemisionDetalles = $paginator->paginate($this->getDoctrine()->getManager()->getRepository(InvRemisionDetalle::class)->listarDetallesPendientes($raw,$arRemision->getCodigoTerceroFk()), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/comercial/remision/detalleNuevoRemision.html.twig', [
            'form' => $form->createView(),
            'arRemisionDetalles' => $arRemisionDetalles
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'numero' => $form->get('numero')->getData(),
            'codigoRemision' => $form->get('codigoRemisionPk')->getData(),
            'codigoTercero' => $form->get('codigoTerceroFk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arRemisionTipo = $form->get('codigoRemisionTipoFk')->getData();
        $arAsesor = $form->get('codigoAsesorFk')->getData();

        if (is_object($arRemisionTipo)) {
            $filtro['codigoRemisionTipo'] = $arRemisionTipo->getCodigoRemisionTipoPk();
        } else {
            $filtro['codigoRemisionTipo'] = $arRemisionTipo;
        }

        if (is_object($arAsesor)) {
            $filtro['codigoAsesor'] = $arAsesor->getCodigoAsesorPk();
        } else {
            $filtro['codigoAsesor'] = $arAsesor;
        }

        return $filtro;

    }
}
