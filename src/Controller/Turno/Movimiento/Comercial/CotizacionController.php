<?php

namespace App\Controller\Turno\Movimiento\Comercial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurCotizacion;
use App\Entity\Turno\TurCotizacionDetalle;
use App\Entity\Turno\TurCotizacionTipo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\CotizacionDetalleType;
use App\Form\Type\Turno\CotizacionType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Turno\Cotizacion;
use App\Formato\Inventario\Pedido;
use App\Formato\RecursoHumano\Programacion;
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CotizacionController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "TurCotizacion";

    protected $clase = TurCotizacion::class;
    protected $claseNombre = "TurCotizacion";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Cotizacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/comercial/cotizacion/lista", name="turno_movimiento_comercial_cotizacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroTurCotizacion')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoCotizacionPk', TextType::class, array('required' => false, 'data'=>$raw['filtros']['codigoCotizacionPk']))
            ->add('numero', TextType::class, array('required' => false, 'data'=>$raw['filtros']['numero']))
            ->add('codigoCotizacionTipoFk', EntityType::class, [
                'class' => TurCotizacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoCotizacionTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'data'=>  $raw['filtros']['codigoPedidoTipoFk'] ? $em->getReference(TurCotizacionTipo::class, $raw['filtros']['codigoCotizacionTipoFk']) : null
            ])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAutorizado'] ])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAprobado'] ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAnulado'] ])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurCotizacion::class)->lista($raw)->getQuery()->execute(), "Pedidos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurCotizacion::class)->eliminar($arrSeleccionados);
            }
        }
        $arCotizaciones = $paginator->paginate($em->getRepository(TurCotizacion::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('turno/movimiento/comercial/cotizacion/lista.html.twig', [
            'arCotizaciones' => $arCotizaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/comercial/cotizacion/nuevo/{id}", name="turno_movimiento_comercial_cotizacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCotizacion = new TurCotizacion();
        if ($id != '0') {
            $arCotizacion = $em->getRepository(TurCotizacion::class)->find($id);
            if (!$arCotizacion) {
                return $this->redirect($this->generateUrl('turno_movimiento_comercial_cotizacion_lista'));
            }
        } else {
            $arrConfiguracion = $em->getRepository(TurConfiguracion::class)->comercialNuevo();
            $arCotizacion->setVrSalarioBase($arrConfiguracion['vrSalarioMinimo']);
            $arCotizacion->setUsuario($this->getUser()->getUserName());
            $arCotizacion->setEstrato(6);
            $arCotizacion->setFechaVence(new \DateTime('now'));
        }
        $form = $this->createForm(CotizacionType::class, $arCotizacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TurCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arCotizacion->setClienteRel($arCliente);
                        $arCotizacion->setFecha(new \DateTime('now'));
                        if ($id == 0) {
                            $nuevafecha = date('Y/m/', strtotime('-1 month', strtotime(date('Y/m/j'))));
                            $dateFechaGeneracion = date_create($nuevafecha . '01');
                            $arCotizacion->setFechaGeneracion($dateFechaGeneracion);
                            $arCotizacion->setUsuario($this->getUser()->getUserName());
                        }
                        $em->persist($arCotizacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('turno_movimiento_comercial_cotizacion_detalle', ['id' => $arCotizacion->getCodigoCotizacionPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }

            }
        }
        return $this->render('turno/movimiento/comercial/cotizacion/nuevo.html.twig', [
            'arCotizacion' => $arCotizacion,
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
     * @Route("/turno/movimiento/comercial/cotizacion/detalle/{id}", name="turno_movimiento_comercial_cotizacion_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCotizacion = $em->getRepository(TurCotizacion::class)->find($id);
        $form = Estandares::botonera($arCotizacion->getEstadoAutorizado(), $arCotizacion->getEstadoAprobado(), $arCotizacion->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arCotizacion->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobado['disabled'] = false;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arCotizacion->getEstadoAprobado()) {
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
                $em->getRepository(TurCotizacion::class)->autorizar($arCotizacion);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurCotizacion::class)->desautorizar($arCotizacion);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TurCotizacion::class)->aprobar($arCotizacion);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurCotizacionDetalle::class)->eliminar($arCotizacion, $arrDetallesSeleccionados);
                $em->getRepository(TurCotizacion::class)->liquidar($arCotizacion);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurCotizacionDetalle::class)->actualizarDetalles($arrControles, $form, $arCotizacion);
            }
            if ($form->get('btnImprimir')->isClicked()){
                $objFormato = new Cotizacion();
                $objFormato->Generar($em, $id);
            }
            return $this->redirect($this->generateUrl('turno_movimiento_comercial_cotizacion_detalle', ['id' => $id]));
        }
        $arCotizacionDetalles = $paginator->paginate($em->getRepository(TurCotizacionDetalle::class)->lista($id), $request->query->getInt('page', 1), 10);
        return $this->render('turno/movimiento/comercial/cotizacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arCotizacionDetalles' => $arCotizacionDetalles,
            'arCotizacion' => $arCotizacion
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoPedido
     * @param $codigoPedidoDetalle
     * @return Response
     * @throws \Exception
     * @Route("/turno/movimiento/comercial/cotizacion/detalle/nuevo/{codigoCotizacion}/{id}", name="turno_movimiento_comercial_cotizacion_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoCotizacion, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arCotizacionDetalle = new TurCotizacionDetalle();
        $arCotizacion = $em->getRepository(TurCotizacion::class)->find($codigoCotizacion);
        if ($id != '0') {
            $arCotizacionDetalle = $em->getRepository(TurCotizacionDetalle::class)->find($id);
        } else {
            $arCotizacionDetalle->setCotizacionRel($arCotizacion);
            $arCotizacionDetalle->setLunes(true);
            $arCotizacionDetalle->setMartes(true);
            $arCotizacionDetalle->setMiercoles(true);
            $arCotizacionDetalle->setJueves(true);
            $arCotizacionDetalle->setViernes(true);
            $arCotizacionDetalle->setSabado(true);
            $arCotizacionDetalle->setDomingo(true);
            $arCotizacionDetalle->setFestivo(true);
            $arCotizacionDetalle->setCantidad(1);
            $arCotizacionDetalle->setFechaDesde(new \DateTime('now'));
            $arCotizacionDetalle->setFechaHasta(new \DateTime('now'));
            $arCotizacionDetalle->setVrSalarioBase($arCotizacion->getVrSalarioBase());
            $arCotizacionDetalle->setPeriodo('M');
        }
        $form = $this->createForm(CotizacionDetalleType::class, $arCotizacionDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($id == 0) {
                    $arCotizacionDetalle->setPorcentajeIva($arCotizacionDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentaje());
                    $arCotizacionDetalle->setPorcentajeBaseIva($arCotizacionDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentajeBase());
                }
                $em->persist($arCotizacionDetalle);
                $em->flush();
                $em->getRepository(TurCotizacion::class)->liquidar($arCotizacion);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/movimiento/comercial/cotizacion/detalleNuevo.html.twig', [
            'arCotizacion' => $arCotizacion,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoCotizacionPk' => $form->get('codigoCotizacionPk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arCotizacionTipo = $form->get('codigoCotizacionTipoFk')->getData();
        if (is_object($arCotizacionTipo)) {
            $filtro['codigoCotizacionTipoFk'] = $arCotizacionTipo->getCodigoCotizacionTipoPk();
        } else {
            $filtro['codigoCotizacionTipoFk'] = $arCotizacionTipo;
        }

        $session->set('filtroTurCotizacion', $filtro);
        return $filtro;
    }
}
