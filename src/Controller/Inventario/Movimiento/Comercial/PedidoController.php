<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
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

class PedidoController extends AbstractController
{
    protected $class = InvPedido::class;
    protected $claseNombre = "InvPedido";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Pedido";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/comercial/pedido/lista", name="inventario_movimiento_comercial_pedido_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoPedidoPk', TextType::class, array('required' => false))
            ->add('codigoTerceroFk', TextType::class, array('required' => false))
            ->add('codigoPedidoTipoFk', EntityType::class, [
                'class' => InvPedidoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.codigoPedidoTipoPk', 'ASC');
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
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(InvPedido::class)->lista($raw)->getQuery()->execute(), "Pedidos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(InvPedido::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_pedido_lista'));
            }
        }
        $arPedidos = $paginator->paginate($em->getRepository(InvPedido::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('inventario/movimiento/comercial/pedido/lista.html.twig', [
            'arPedidos' => $arPedidos,
            'form' => $form->createView(),
        ]);
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
    public function detalle(Request $request,PaginatorInterface $paginator ,$id)
    {
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
    public function detalleNuevo(Request $request, PaginatorInterface $paginator,$codigoPedido)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arPedido = $em->getRepository(InvPedido::class)->find($codigoPedido);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('txtReferenciaItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => null
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = [
                    'codigoItem' => $form->get('txtCodigoItem')->getData(),
                    'nombreItem' => $form->get('txtNombreItem')->getData(),
                    'referenciaItem' => $form->get('txtReferenciaItem')->getData()
                ];
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
                            $arPedidoDetalle->setVrPrecio($precioVenta);
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
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista($raw), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/comercial/pedido/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'numero' => $form->get('numero')->getData(),
            'codigoPedido' => $form->get('codigoPedidoPk')->getData(),
            'codigoTercero' => $form->get('codigoTerceroFk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arPedidoTipo = $form->get('codigoPedidoTipoFk')->getData();

        if (is_object($arPedidoTipo)) {
            $filtro['pedidoTipo'] = $arPedidoTipo->getCodigoPedidoTipoPk();
        } else {
            $filtro['pedidoTipo'] = $arPedidoTipo;
        }

        return $filtro;

    }
}
