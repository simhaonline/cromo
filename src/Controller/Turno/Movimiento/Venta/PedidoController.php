<?php

namespace App\Controller\Turno\Movimiento\Venta;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoTipo;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PedidoController extends AbstractController
{
    protected $clase = TurPedido::class;
    protected $claseNombre = "TurPedido";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Venta";
    protected $nombre = "Pedido";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/venta/pedido/lista", name="turno_movimiento_venta_pedido_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoPedidoPk', TextType::class, array('required' => false))
            ->add('codigoPedidoTipoFk', EntityType::class, [
                'class' => TurPedidoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.codigoPedidoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
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
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                $arPedidos=$em->getRepository(TurPedido::class)->listaPedido($raw);
                $this->exportarExcelPersonalizado($arPedidos);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurPedido::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_venta_pedido_lista'));
            }
        }
        $arPedidos = $paginator->paginate($em->getRepository(TurPedido::class)->listaPedido($raw), $request->query->getInt('page', 1), 30);
        return $this->render('turno/movimiento/venta/pedido/lista.html.twig', [
            'arPedidos' => $arPedidos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/movimiento/comercial/pedido/nuevo/{id}", name="turno_movimiento_venta_pedido_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPedido = new TurPedido();
        if ($id != '0') {
            $arPedido = $em->getRepository(TurPedido::class)->find($id);
            if (!$arPedido) {
                return $this->redirect($this->generateUrl('turno_movimiento_venta_pedido_lista'));
            }
        } else {
            $arrConfiguracion = $em->getRepository(TurConfiguracion::class)->comercialNuevo();
            $arPedido->setVrSalarioBase($arrConfiguracion['vrSalarioMinimo']);
            $arPedido->setFecha(new \DateTime('now'));
            $arPedido->setUsuario($this->getUser()->getUserName());
            $arPedido->setEstrato(6);
            $nuevafecha = date('Y/m/', strtotime('-1 month', strtotime(date('Y/m/j'))));
            $dateFechaGeneracion = date_create($nuevafecha . '01');
        }
        $form = $this->createForm(PedidoType::class, $arPedido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TurCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arPedido->setClienteRel($arCliente);
                        $em->persist($arPedido);
                        $em->flush();
                        return $this->redirect($this->generateUrl('turno_movimiento_venta_pedido_detalle', ['id' => $arPedido->getCodigoPedidoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }

            }
        }
        return $this->render('turno/movimiento/venta/pedido/nuevo.html.twig', [
            'arPedido' => $arPedido,
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
     * @Route("/turno/movimiento/venta/pedido/detalle/{id}", name="turno_movimiento_venta_pedido_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
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
            return $this->redirect($this->generateUrl('turno_movimiento_venta_pedido_detalle', ['id' => $id]));
        }
        $arPedidoDetalles = $paginator->paginate($em->getRepository(TurPedidoDetalle::class)->lista($id), $request->query->getInt('page', 1), 1000);
        return $this->render('turno/movimiento/venta/pedido/detalle.html.twig', [
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
     * @Route("/turno/movimiento/venta/pedido/detalle/nuevo/{codigoPedido}/{id}", name="turno_movimiento_venta_pedido_detalle_nuevo")
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
            $arPedidoDetalle->setVrSalarioBase($arPedido->getVrSalarioBase());
        }
        $form = $this->createForm(PedidoDetalleType::class, $arPedidoDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arPedidoDetalle->setPorcentajeIva($arPedidoDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentaje());
                    $arPedidoDetalle->setPorcentajeBaseIva($arPedidoDetalle->getItemRel()->getImpuestoIvaVentaRel()->getPorcentajeBase());
                }
                $arPedidoDetalle->setAnio($arPedido->getFecha()->format('Y'));
                $arPedidoDetalle->setMes($arPedido->getFecha()->format('n'));
                if($arPedidoDetalle->getPeriodo() == 'M') {
                    $diaFinalMes = date("d", (mktime(0, 0, 0, $arPedido->getFecha()->format('n') + 1, 1, $arPedido->getFecha()->format('Y')) - 1));
                    $arPedidoDetalle->setDiaDesde(1);
                    $arPedidoDetalle->setDiaHasta($diaFinalMes);
                }

                $em->persist($arPedidoDetalle);
                $em->flush();
                $em->getRepository(TurPedido::class)->liquidar($arPedido);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/movimiento/venta/pedido/detalleNuevo.html.twig', [
            'arPedido' => $arPedido,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/movimiento/venta/pedido/detalle/compuesto/{codigoPedidoDetalle}", name="turno_movimiento_venta_pedido_detalle_compuesto")
     */
    public function detalleCompuesto(Request $request,PaginatorInterface $paginator,$codigoPedidoDetalle){
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
        $arPedido = $em->getRepository(TurPedido::class)->find($arPedidoDetalle->getCodigoPedidoFk());
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnActualizar', SubmitType::class, array('label' => 'Actualizar'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get('btnActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalleCompuesto($arrControles, $codigoPedidoDetalle, $arPedidoDetalle->getCodigoPedidoFk());
                return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_detalle_compuesto', array('codigoPedidoDetalle' => $codigoPedidoDetalle)));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurPedidoDetalleCompuesto::class)->eliminar($arrSeleccionados);
                $em->getRepository(TurPedidoDetalle::class)->liquidar($codigoPedidoDetalle);
                $em->getRepository(TurPedido::class)->liquidar($arPedido);
                return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_detalle_compuesto', array('codigoPedidoDetalle' => $codigoPedidoDetalle)));
            }
        }
        $arPedidoDetallesCompuestos = $paginator->paginate($em->getRepository(TurPedidoDetalleCompuesto::class)->lista($codigoPedidoDetalle), $request->query->getInt('page', 1), 30);
        return $this->render('turno/movimiento/juridico/contrato/contratoCompuesto.html.twig', [
            'arPedido' => $arPedido,
            'arPedidoDetalle' => $arPedidoDetalle,
            'arPedidoDetallesCompuestos' => $arPedidoDetallesCompuestos,
            'form' => $form->createView(),
        ]);
    }

    public function exportarExcelPersonalizado($arPedidos){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arPedidos) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('Pedidos');
            $j = 0;
            $arrColumnas=['ID','TIPO','NÚMERO','FECHA','CLIENTE','SECTOR','H','HD','HN','TOTAL','USUARIO','AUT','APR','ANU'];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setBold(true);;
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arPedidos as $arPedido) {
                $hoja->setCellValue('A' . $j, $arPedido['codigoPedidoPk']);
                $hoja->setCellValue('B' . $j, $arPedido['pedidoTipoNombre']);
                $hoja->setCellValue('C' . $j, $arPedido['numero']);
                $hoja->setCellValue('D' . $j, $arPedido['fecha']->format('Y/m/d'));
                $hoja->setCellValue('E' . $j, $arPedido['clienteNombreCorto']);
                $hoja->setCellValue('F' . $j, $arPedido['sectorNombre']);
                $hoja->setCellValue('G' . $j, $arPedido['horas']);
                $hoja->setCellValue('H' . $j, $arPedido['horasDiurnas']);
                $hoja->setCellValue('I' . $j, $arPedido['horasNocturnas']);
                $hoja->setCellValue('J' . $j, number_format($arPedido['vrTotal'], 0,'.', ','));
                $hoja->setCellValue('K' . $j, $arPedido['usuario']);
                $hoja->setCellValue('L' . $j, $arPedido['estadoAutorizado']?"SI":"NO");
                $hoja->setCellValue('M' . $j, $arPedido['estadoAprobado']?"SI":"NO");
                $hoja->setCellValue('N' . $j, $arPedido['estadoAnulado']?"SI":"NO");
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=soportes.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }

    public function getFiltros($form)
    {
        $fitro = [
            'codigoClienteFk' => $form->get('codigoClienteFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoPedidoPk' => $form->get('codigoPedidoPk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ?$form->get('fechaHasta')->getData()->format('Y-m-d'): null,

        ];
        $arPedidoTipo = $form->get('codigoPedidoTipoFk')->getData();

        if ( is_object($arPedidoTipo)) {
            $fitro['codigoPedidoTipoFk'] = $arPedidoTipo->getCodigoPedidoTipoPk();
        } else {
            $fitro['codigoPedidoTipoFk'] = $arPedidoTipo;
        }
        return $fitro;

    }

}
