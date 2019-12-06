<?php

namespace App\Controller\Tesoreria\Movimiento;

use App\Entity\Financiero\FinCuenta;
use App\Entity\General\GenBanco;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoClase;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Entity\Tesoreria\TesMovimientoTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Tesoreria\MovimientoCompraType;
use App\Form\Type\Tesoreria\MovimientoType;
use App\Formato\Tesoreria\Egreso;
use App\Formato\Tesoreria\Movimiento;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class MovimientoController extends AbstractController
{
    protected $clase = TesMovimiento::class;
    protected $claseNombre = "TesMovimiento";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "Movimiento";
    protected $nombre = "Movimiento";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/movimiento/movimiento/movimiento/lista/{clase}", name="tesoreria_movimiento_movimiento_movimiento_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator, $clase)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoMovimientoTipoFk', EntityType::class, [
                'class' => TesMovimientoTipo::class,
                'query_builder' => function (EntityRepository $er) use ($clase) {
                    return $er->createQueryBuilder('mt')
                        ->where("mt.codigoMovimientoClaseFk ='" . $clase . "'")
                        ->orderBy('mt.codigoMovimientoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoMovimientoPk', TextType::class, array('required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('txtCodigoTercero', TextType::class, ['required' => false])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoContabilizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData(),
            'codigoMovimientoClase' => $clase
        ];
        if ($form->isSubmitted()) {

            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked() || $form->get('btnContabilizar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form, $clase);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TesMovimiento::class)->lista($raw), "Movimientos");
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TesMovimiento::class)->contabilizar($arr);
            }
            if($form->get('btnEliminar')->isClicked()){
	            $arrSeleccionados = $request->request->get('ChkSeleccionar');
	            $em->getRepository(TesMovimiento::class)->eliminar($arrSeleccionados);
	            return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_lista', ['clase'=>$clase]));
            }
        }
        $arMovimientos = $paginator->paginate($em->getRepository(TesMovimiento::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('tesoreria/movimiento/movimiento/lista.html.twig', [
            'arMovimientos' => $arMovimientos,
            'clase' => $clase,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/tesoreria/movimiento/movimiento/movimiento/nuevo/{id}/{clase}", name="tesoreria_movimiento_movimiento_movimiento_nuevo")
     */
    public function nuevo(Request $request, $id, $clase)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = new TesMovimiento();
        if ($id != 0) {
            $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);

        } else {
            $arMovimiento->setMovimientoClaseRel($em->getReference(TesMovimientoClase::class, $clase));
            $arMovimiento->setFecha(new \DateTime('now'));
        }

        switch ($clase) {
            case 'EG':
                $form = $this->createForm(MovimientoType::class, $arMovimiento);
                break;
            case 'CP':
                $form = $this->createForm(MovimientoCompraType::class, $arMovimiento);
                break;
            case 'NC':
                $form = $this->createForm(MovimientoCompraType::class, $arMovimiento);
                break;
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(TesTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        if($arMovimiento->getMovimientoTipoRel()) {
                            $arMovimiento->setTerceroRel($arTercero);
                            $em->persist($arMovimiento);
                            $em->flush();
                            return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $arMovimiento->getCodigoMovimientoPk()]));
                        } else {
                            Mensajes::error('Debe seleccionar un tipo');
                        }
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        switch ($clase) {
            case 'EG':
                return $this->render('tesoreria/movimiento/movimiento/nuevo.html.twig', [
                    'arMovimiento' => $arMovimiento,
                    'clase' => $clase,
                    'form' => $form->createView()
                ]);
                break;
            case 'CP':
                return $this->render('tesoreria/movimiento/movimiento/nuevoCompra.html.twig', [
                    'arMovimiento' => $arMovimiento,
                    'clase' => $clase,
                    'form' => $form->createView()
                ]);
                break;
            case 'NC':
                return $this->render('tesoreria/movimiento/movimiento/nuevoCompra.html.twig', [
                    'arMovimiento' => $arMovimiento,
                    'clase' => $clase,
                    'form' => $form->createView()
                ]);
                break;
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/tesoreria/movimiento/movimiento/movimiento/movimiento/{id}", name="tesoreria_movimiento_movimiento_movimiento_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
        $form = Estandares::botonera($arMovimiento->getEstadoAutorizado(), $arMovimiento->getEstadoAprobado(), $arMovimiento->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAdicionar = ['label' => 'Adicionar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arMovimiento->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnAdicionar['disabled'] = true;
        }
        $form
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnAdicionar', SubmitType::class, $arrBtnAdicionar);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnAutorizar')->isClicked()) {
                    $em->getRepository(TesMovimientoDetalle::class)->actualizar($arrControles, $id);
                    $em->getRepository(TesMovimiento::class)->liquidar($id);
                    $em->getRepository(TesMovimiento::class)->autorizar($arMovimiento);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));

            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arMovimiento->getEstadoAutorizado() == 1 && $arMovimiento->getEstadoAprobado() == 0) {
                    $em->getRepository(TesMovimiento::class)->desAutorizar($arMovimiento);
                    return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El movimiento debe estar autorizado y no puede estar aprobado");
                }
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TesMovimiento::class)->aprobar($arMovimiento);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Movimiento();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(TesMovimiento::class)->anular($arMovimiento);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TesMovimientoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(TesMovimiento::class)->liquidar($id);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnAdicionar')->isClicked()) {
	            $em->getRepository(TesMovimiento::class)->liquidar($id);
                $cantidadMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk'=>$arMovimiento->getCodigoMovimientoPk()]);
                if (count($cantidadMovimientoDetalles) > 0){
                    $em->getRepository(TesMovimientoDetalle::class)->actualizar($arrControles, $id);
                }
                $arMovimientoDetalle = new TesMovimientoDetalle();
                $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                $arMovimientoDetalle->setTerceroRel($arMovimiento->getTerceroRel());
                if($arMovimiento->getCodigoMovimientoClaseFk() == 'EG') {
                    $arMovimientoDetalle->setNaturaleza('C');
                } else {
                    $arMovimientoDetalle->setNaturaleza('D');
                    $arMovimientoDetalle->setNumero($arMovimiento->getNumeroDocumento());
                }

                $em->persist($arMovimientoDetalle);
                $em->flush();

                $em->getRepository(TesMovimiento::class)->liquidar($id);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TesMovimientoDetalle::class)->eliminar($arMovimiento, $arrDetallesSeleccionados);
                $em->getRepository(TesMovimiento::class)->liquidar($id);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_movimiento_movimiento_detalle', ['id' => $id]));
            }
            if ($form->get('btnArchivoPlanoBbva')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $numero = $arMovimiento->getNumero();
                $this->generarArchivoBBVA($arMovimiento, $numero, $arrDetallesSeleccionados);
            }

        }
        $arMovimientoDetalles = $paginator->paginate($em->getRepository(TesMovimientoDetalle::class)->lista($arMovimiento->getCodigoMovimientoPk()), $request->query->getInt('page', 1), 500);

        return $this->render(   'tesoreria/movimiento/movimiento/detalle.html.twig', [
            'arMovimientoDetalles' => $arMovimientoDetalles,
            'arMovimiento' => $arMovimiento,
            'clase' => array('clase' => 'TesMovimiento', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/tesoreria/movimiento/movimiento/movimiento/detalle/nuevo/{id}", name="tesoreria_movimiento_movimiento_movimiente_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('todosTerceros', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroTesCuentaPagarTodosTerceros')))
            ->add('txtCodigoTercero', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => ""])
            ->add('cboCuentaPagarTipo', EntityType::class, $em->getRepository(TesCuentaPagarTipo::class)->llenarCombo())
            ->add('cboBanco', EntityType::class, $em->getRepository(GenBanco::class)->llenarCombo())
            ->add('txtCodigoCuentaPagar', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => $session->get('filtroTesCuentaPagarCodigo')])
            ->add('txtNumero', TextType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroTesCuentaPagarNumero')])
            ->add('txtNumeroReferencia', TextType::class, ['label' => 'Numero referecia: ', 'required' => false, 'data' => $session->get('filtroTesCuentaPagarNumeroReferencia')])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesFechaDesde') ? date_create($session->get('filtroTesFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesFechaHasta') ? date_create($session->get('filtroTesFechaHasta')) : null])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnGuardarNuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCuentaPagarTipo = $form->get('cboCuentaPagarTipo')->getData();
                if ($arCuentaPagarTipo) {
                    $session->set('filtroTesCuentaPagarTipo', $arCuentaPagarTipo->getCodigoCuentaPagarTipoPk());
                } else {
                    $session->set('filtroTesCuentaPagarTipo', null);
                }
                $arBanco = $form->get('cboBanco')->getData();
                if ($arBanco) {
                    $session->set('filtroGenBanco', $arBanco->getCodigoBancoPk());
                } else {
                    $session->set('filtroGenBanco', null);
                }
                $session->set('filtroTesCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroTesCuentaPagarCodigo', $form->get('txtCodigoCuentaPagar')->getData());
                $session->set('filtroTesCuentaPagarNumero', $form->get('txtNumero')->getData());
                $session->set('filtroTesCuentaPagarNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
                $session->set('filtroTesFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroTesFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
                $session->set('filtroTesCuentaPagarTodosTerceros', $form->get('todosTerceros')->getData());
            }
            if ($form->get('btnGuardar')->isClicked() || $form->get('btnGuardarNuevo')->isClicked()) {
                $arrCuentasPagar = $request->request->get('ChkSeleccionar');
                if ($arrCuentasPagar) {
                    foreach ($arrCuentasPagar as $codigoCuentaPagar) {
                        /** @var $arCuentaPagar  TesCuentaPagar */
                        $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($codigoCuentaPagar);
                        $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
                        $arMovimientoDetalle = new TesMovimientoDetalle();
                        $arMovimientoDetalle->setMovimientoRel($arMovimiento);
                        $arMovimientoDetalle->setNumero($arCuentaPagar->getNumeroDocumento());
                        $arMovimientoDetalle->setCuentaPagarRel($arCuentaPagar);
                        $arMovimientoDetalle->setVrPago($arCuentaPagar->getVrSaldo());
                        $arMovimientoDetalle->setUsuario($this->getUser()->getUserName());
                        $arMovimientoDetalle->setCuentaRel($em->getReference(FinCuenta::class, $arCuentaPagar->getCuentaPagarTipoRel()->getCodigoCuentaProveedorFk()));
                        $arMovimientoDetalle->setTerceroRel($arCuentaPagar->getTerceroRel());
                        if ($arCuentaPagar->getOperacion() == 1) {
                            $arMovimientoDetalle->setNaturaleza('D');
                        } else {
                            $arMovimientoDetalle->setNaturaleza('C');
                        }

                        $arMovimientoDetalle->setCuenta($arCuentaPagar->getCuenta());
                        $arMovimientoDetalle->setBancoRel($arCuentaPagar->getBancoRel());
                        $em->persist($arMovimientoDetalle);
                    }
                    $em->flush();
                    $em->getRepository(TesMovimiento::class)->liquidar($id);
                    if ($form->get('btnGuardar')->isClicked()) {
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                    if ($form->get('btnGuardarNuevo')->isClicked()) {
                        echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();</script>";
                    }

                }
            }
        }
        $arCuentasPagar = $paginator->paginate($em->getRepository(TesCuentaPagar::class)->cuentasPagarDetalleNuevo($arMovimiento->getCodigoTerceroFk()), $request->query->getInt('page', 1), 500);
        return $this->render('tesoreria/movimiento/movimiento/detalleNuevo.html.twig', [
            'arCuentasPagar' => $arCuentasPagar,
            'form' => $form->createView()
        ]);
    }


    private function generarArchivoBBVA($arMovimiento, $numero, $arrSeleccionados)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $arRhuConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $dateNow = new \DateTime('now');
        $dateNow = $dateNow->format('YmdHis');
        $strNombreArchivo = "pagoBBVA{$numero}_{$dateNow}.txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $archivo = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $strValorTotal += round($arMovimientoDetalle->getVrPago());
        }
        if ($arrSeleccionados != null) {
            $strValorTotal = 0;
            $arMovimientoDetalle = [];
            foreach ($arrSeleccionados as $codigo) {
                $arMovimientoDetalle = $em->getRepository(TesMovimientoDetalle::class)->find($codigo);
                $arMovimientoDetalle[] = $arMovimientoDetalle;
                $strValorTotal += round($arMovimientoDetalle->getVrPago());
            }
        }
        //Inicio cuerpo
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getVrPago() > 0) {
                $varTipoDocumento = $arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getCodigoIdentificacionFk();
                switch ($varTipoDocumento) {
                    //'01' - Cédula de ciudadanía
                    case 'CC':
                        $strTipoDocumento = '01';
                        break;
                    //'02' - Cédula de extranjería
                    case 'CE':
                        $strTipoDocumento = '02';
                        break;
                    //'03' - N.I.T.
                    case 'NI':
                        $strTipoDocumento = '03';
                        break;
                    //'04' - Tarjeta de Identidad
                    case 'TI':
                        $strTipoDocumento = '04';
                        break;
                    //'05' - Pasaporte
                    case 'PA':
                        $strTipoDocumento = '05';
                        break;
                    // '06' - Sociedad extranjera sin N.I.T. En Colombia
                    case 'TDE':
                        $strTipoDocumento = '06';
                        break;
                }

                //Tipo de identificacion del empleado
                fputs($archivo, $strTipoDocumento);

                //Numero de identificacion del empleado
                fputs($archivo, $this->RellenarNr($arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getNumeroIdentificacion(), "0", 15));

                fputs($archivo, '01');

                //Codigo general del banco o codigo interface
                fputs($archivo, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoInterface(), "0", 4));

                //Numero de cuenta del empleado y se valida si al cuenta es de BBVA o pertenece a un banco diferente
                if ($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoInterface() != 13) {
                    fputs($archivo, '0000000000000000');
                    switch ($arMovimiento->getTerceroRel()->getCodigoCuentaTipoFk()) {
                        case 'S':
                            $tipoCuenta = '02';
                            break;
                        case 'D':
                            $tipoCuenta = '01';
                            break;
                    }
//                    fputs($archivo, $this->RellenarNr2($tipoCuenta . $arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getCuenta(), ' ', 19, 'D'));
                    fputs($archivo, $this->RellenarNr2($tipoCuenta . $arMovimientoDetalle->getCuentaPagarRel()->getCuenta(), ' ', 19, 'D'));
                } else {
                    if ($arRhuConfiguracion->getConcatenarOfinaCuentaBbva()) {
                        $oficina = substr($arMovimientoDetalle->getCuentaPagarRel()->getCuenta(), 0, 3);
                        $cuenta = substr($arMovimientoDetalle->getCuentaPagarRel()->getCuenta(), 3);
                        $strRellenar = "";
                        if ($arMovimiento->getTerceroRel()->getCodigoCuentaTipoFk() == "S") {
                            $strRellenar = '000200';
                        } elseif ($arMovimiento->getTerceroRel()->getCodigoCuentaTipoFk() == "D") {
                            $strRellenar = '000100';
                        }
                        fputs($archivo, $this->RellenarNr2('0' . $oficina . '' . $strRellenar . '' . $cuenta, ' ', 16, 'D'));
                    } else {
                        fputs($archivo, $this->RellenarNr2($arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getCuenta(), ' ', 16, 'D'));
                    }
                    fputs($archivo, '0000000000000000000');
                }

                //Valor entero del pago
                fputs($archivo, $this->RellenarNr($arMovimientoDetalle->getVrPago(), '0', 13));

                //Valor decimal del pago
                fputs($archivo, $this->RellenarNr('0', '0', 2));

                //Fecha limite de pago, no aplica
                fputs($archivo, '000000000000');

                //Nombre del empleado
                fputs($archivo, $this->RellenarNr2(substr(utf8_decode($arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getNombreCorto()), 0, 36), " ", 36, 'D'));

                //Direccion del empleado
                fputs($archivo, $this->RellenarNr2('MEDELLIN', " ", 36, 'D'));

                //2da direccion del empleado
                fputs($archivo, $this->RellenarNr2(" ", " ", 36, 'D'));

                //Email del empleado
                fputs($archivo, $this->RellenarNr2("", " ", 48, 'D'));

                //Concepto del pago
                fputs($archivo, $this->RellenarNr2("NOMINA", " ", 40, 'D'));
                fputs($archivo, "\n");
            }
        }
        fclose($archivo);
        $em->flush();
        //Fin cuerpo
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;
    }

    //Rellenar numeros
    public static function RellenarNr($Nro, $Str, $NroCr): string
    {
        $Longitud = strlen($Nro);

        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++)
            $Nro = $Str . $Nro;

        return (string)$Nro;
    }

    public static function RellenarNr2($Nro, $Str, $NroCr, $strPosicion): string
    {
        $Nro = utf8_decode($Nro);
        $Longitud = strlen($Nro);
        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++) {
            if ($strPosicion == "I") {
                $Nro = $Str . $Nro;
            } else {
                $Nro = $Nro . $Str;
            }
        }

        return (string)$Nro;
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoMovimiento' => $form->get('codigoMovimientoPk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoTercero' => $form->get('txtCodigoTercero')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoContabilizado' => $form->get('estadoContabilizado')->getData(),
        ];

        $arMovimientoTipo = $form->get('codigoMovimientoTipoFk')->getData();

        if (is_object($arMovimientoTipo)) {
            $filtro['movimientoTipo'] = $arMovimientoTipo->getCodigoMovimientoTipoPk();
        } else {
            $filtro['movimientoTipo'] = $arMovimientoTipo;
        }

        return $filtro;

    }

}
