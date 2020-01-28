<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionAdicional;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\Transporte\TteDespacho;
use App\Form\Type\RecursoHumano\LiquidacionParametrosType;
use App\Form\Type\RecursoHumano\LiquidacionType;
use App\Form\Type\RecursoHumano\PagoType;
use App\Formato\RecursoHumano\Liquidacion;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LiquidacionController extends AbstractController
{
    protected $clase = RhuLiquidacion::class;
    protected $claseFormulario = LiquidacionType::class;
    protected $claseNombre = "RhuLiquidacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Liquidacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/liquidacion/lista", name="recursohumano_movimiento_nomina_liquidacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroRhuLiquidacion')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoLiquidacionPk', TextType::class, array('required' => false, 'data'=>$raw['filtros']['codigoLiquidacion']))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false, 'data'=>$raw['filtros']['codigoEmpleado'] ])
            ->add('numero', TextType::class, ['required' => false, 'data'=>$raw['filtros']['numero'] ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaDesde']?date_create($raw['filtros']['fechaDesde']):null ])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaHasta']?date_create($raw['filtros']['fechaHasta']):null ])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAutorizado'] ])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAprobado'] ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAnulado'] ])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();

        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuLiquidacion::class)->lista($raw), "Liquidaciones");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuLiquidacion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_lista'));
            }
        }
        $arLiquidaciones = $paginator->paginate($em->getRepository(RhuLiquidacion::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/liquidacion/lista.html.twig', [
            'arLiquidaciones' => $arLiquidaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/liquidacion/nuevo/{id}", name="recursohumano_movimiento_nomina_liquidacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = new RhuLiquidacion();
        if ($id != 0) {
            $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($id);
            if (!$arLiquidacion) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_lista'));
            }
        }
        $form = $this->createForm(LiquidacionType::class, $arLiquidacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arLiquidacion);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', ['id' => $arLiquidacion->getCodigoLiquidacionPk()]));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/liquidacion/nuevo.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/liquidacion/detalle/{id}", name="recursohumano_movimiento_nomina_liquidacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($id);
        $form = Estandares::botonera($arLiquidacion->getEstadoAutorizado(), $arLiquidacion->getEstadoAprobado(), $arLiquidacion->getEstadoAnulado());
        $form->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']]);
        $form->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formatoLiquidacion = new Liquidacion();
                $formatoLiquidacion->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0) {
                    $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
                    $boolValidacionFechaUltimoPago = true;
                    if ($arConfiguracion->getValidarFechaUltimoPagoLiquidacion()) {
                        if ($arLiquidacion->getContratoRel()->getFechaUltimoPago() < $arLiquidacion->getContratoRel()->getFechaHasta()) {
                            $boolValidacionFechaUltimoPago = false;
                            Mensajes::error("No se puede liquidar el contrato, la fecha del ultimo pago es inferior a la fecha de terminación del contrato.");
                        }
                    }
                    if ($boolValidacionFechaUltimoPago) {
                        $respuesta = $em->getRepository(RhuLiquidacion::class)->liquidar($id);
                        $arLiquidacion->setEstadoAutorizado(1);
                        if ($respuesta == "") {
                            $em->flush();
                        } else {
                            Mensajes::error($respuesta);
                        }
                    }
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', array('id' => $id)));
                } else {
                    Mensajes::error("No puede reliquidar una liquidacion autorizada");
                }
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arLiquidacionAdicional = $em->getRepository(RhuLiquidacionAdicional::class)->find($codigo);
                        if ($arLiquidacionAdicional) {
                            $em->remove($arLiquidacionAdicional);
                        }
                    }
                    $em->flush();
                }
                $em->getRepository(RhuLiquidacion::class)->liquidar($id);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuLiquidacion::class)->desautorizar($arLiquidacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', array('id' => $id)));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(RhuLiquidacion::class)->aprobar($arLiquidacion, $this->getUser()->getUsername());
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', array('id' => $id)));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuLiquidacion::class)->Adicionales($id)->getQuery()->getResult(), "Adicionales");
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(RhuLiquidacion::class)->anular($arLiquidacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', array('id' => $id)));
            }
        }
        $arLiquidacionAdicionales = $em->getRepository(RhuLiquidacionAdicional::class)->findBy(['codigoLiquidacionFk' => $id]);
        return $this->render('recursohumano/movimiento/nomina/liquidacion/detalle.html.twig', [
            'arLiquidacion' => $arLiquidacion,
            'arLiquidacionAdicionales' => $arLiquidacionAdicionales,
            'clase' => array('clase' => 'RhuLiquidacion', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/credito/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_credito")
     */
    public function detalleCredito(Request $request, $codigoLiquidacion)
    {

        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoLiquidacion);
        $arCreditos = $em->getRepository(RhuCredito::class)->pendientes($arLiquidacion->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $floVrDeducciones = 0;
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCredito = $em->getRepository(RhuCredito::class)->find($codigoCredito);
                        $valor = 0;
                        if ($arrControles['TxtValor' . $codigoCredito] != '') {
                            $valor = $arrControles['TxtValor' . $codigoCredito];
                        }
                        $arLiquidacionAdicional = new RhuLiquidacionAdicional();
                        $arLiquidacionAdicional->setCreditoRel($arCredito);
                        $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                        $arLiquidacionAdicional->setVrDeduccion($valor);
                        $arLiquidacionAdicional->setConceptoRel($arCredito->getCreditoTipoRel()->getConceptoRel());
                        $em->persist($arLiquidacionAdicional);
                        $floVrDeducciones += $valor;
                    }
                    $em->flush();
                    $em->getRepository(RhuLiquidacion::class)->liquidar($arLiquidacion->getCodigoLiquidacionPk());
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('recursohumano/movimiento/nomina/liquidacion/detalleNuevo.html.twig', array(
            'arCreditos' => $arCreditos,
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/descuento/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_descuento")
     */
    public function detalleDescuento(Request $request, $codigoLiquidacion)
    {

        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoLiquidacion);
        $form = $this->createFormBuilder()
            ->add('conceptoRel', EntityType::class, array(
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.adicionalTipo = 'DES'")
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('txtValor', NumberType::class, array('required' => true))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arPagoConcepto = new RhuConcepto();
                $arPagoConcepto = $form->get('conceptoRel')->getData();
                $arLiquidacionAdicional = new RhuLiquidacionAdicional();
                $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                $arLiquidacionAdicional->setConceptoRel($arPagoConcepto);
                $arLiquidacionAdicional->setVrDeduccion($form->get('txtValor')->getData());
                $em->persist($arLiquidacionAdicional);
                $em->flush();
                $em->getRepository(RhuLiquidacion::class)->liquidar($arLiquidacion->getCodigoLiquidacionPk());
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/nomina/liquidacion/detalleDescuentoNuevo.html.twig', array(
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/bonificacion/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_bonificacion")
     */
    public function detalleBonificacion(Request $request, $codigoLiquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoLiquidacion);
        $form = $this->createFormBuilder()
            ->add('conceptoRel', EntityType::class, array(
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.adicionalTipo = 'BON'")
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('txtValor', NumberType::class, array('required' => true))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arPagoConcepto = new RhuConcepto();
                $arPagoConcepto = $form->get('conceptoRel')->getData();
                $arLiquidacionAdicional = new RhuLiquidacionAdicional();
                $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                $arLiquidacionAdicional->setConceptoRel($arPagoConcepto);
                $arLiquidacionAdicional->setVrBonificacion($form->get('txtValor')->getData());
                $em->persist($arLiquidacionAdicional);
                $em->flush();
                $em->getRepository(RhuLiquidacion::class)->liquidar($arLiquidacion->getCodigoLiquidacionPk());
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/nomina/liquidacion/detalleBonificacionNuevo.html.twig', array(
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/cesantias/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_cesantias")
     */
    public function detallePagosCesantias(Request $request, $codigoLiquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arDetalleCesantia = $em->getRepository(RhuLiquidacion::class)->detalleCesantias($codigoLiquidacion);
        return $this->render('recursohumano/movimiento/nomina/liquidacion/detalleCesantias.html.twig', array(
            'arDetalleCesantia' => $arDetalleCesantia
        ));

    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/primas/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_primas")
     */
    public function detallePrimas(Request $request, $codigoLiquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arDetallePrimas = $em->getRepository(RhuLiquidacion::class)->detallePrimas($codigoLiquidacion);
        return $this->render('recursohumano/movimiento/nomina/liquidacion/detallePrimas.html.twig', array(
            'arDetallePrimas' => $arDetallePrimas
        ));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/vacaciones/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_vacaciones")
     */
    public function detalleVacaciones(Request $request, $codigoLiquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arDetalleVacaciones = $em->getRepository(RhuLiquidacion::class)->detalleVacacion($codigoLiquidacion);
        return $this->render('recursohumano/movimiento/nomina/liquidacion/detalleVacaciones.html.twig', array(
            'arDetalleVacaciones' => $arDetalleVacaciones
        ));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/cesantiasanterior/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_cesantiasanterior")
     */
    public function cesantiasAnterior(Request $request, $codigoLiquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoLiquidacion);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('OpSeleccionar')) {
                $codigo = $request->request->get('OpSeleccionar');
                $arPago = $em->getRepository(RhuPago::class)->find($codigo);
                $arLiquidacion->setCodigoPagoCesantiaAnteriorFk($arPago->getCodigoPagoPk());
                $arLiquidacion->setVrCesantiasAnterior($arPago->getVrNeto());
                $em->persist($arLiquidacion);
                $em->flush();
                $em->getRepository(RhuLiquidacion::class)->liquidar($codigoLiquidacion);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

            }
        }
        $arPagosCesantiasAnterior = $em->getRepository(RhuPago::class)->cesantiasAnterior($arLiquidacion->getCodigoEmpleadoFk());
        return $this->render('recursohumano/movimiento/nomina/liquidacion/cesantiasAnterior.html.twig', array(
            'arPagosCesantiasAnterior' => $arPagosCesantiasAnterior,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/recursohumano/movimiento/nomina/liquidacion/detalle/interesescesantiasanterior/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_interesescesantiasanterior")
     */
    public function interesesAnterior(Request $request, $codigoLiquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoLiquidacion);
        $form = $this->createFormBuilder()

            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('OpSeleccionar')) {
                $codigo = $request->request->get('OpSeleccionar');
                $arPago = $em->getRepository(RhuPago::class)->find($codigo);
                $arLiquidacion->setCodigoPagoCesantiaInteresAnteriorFk($arPago->getCodigoPagoPk());
                $arLiquidacion->setVrInteresesCesantiasAnterior($arPago->getVrNeto());
                $em->persist($arLiquidacion);
                $em->flush();
                $em->getRepository(RhuLiquidacion::class)->liquidar($codigoLiquidacion);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arPagosInteresesCesantiasAnterior = $em->getRepository(RhuPago::class)->interesesCesantiasAnterior($arLiquidacion->getCodigoEmpleadoFk());
        return $this->render('recursohumano/movimiento/nomina/liquidacion/interesesCesantiasAnterior.html.twig', array(
            'arPagosInteresesCesantiasAnterior' => $arPagosInteresesCesantiasAnterior,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/liquidacion/detalle/parametros/{codigoLiquidacion}", name="recursohumano_movimiento_nomina_liquidacion_detalle_parametros")
     */
    public function parametros(Request $request, $codigoLiquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($codigoLiquidacion);
        $form = $this->createForm(LiquidacionParametrosType::class, $arLiquidacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arLiquidacion);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/nomina/liquidacion/parametros.html.twig', [
            '$arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoLiquidacion' => $form->get('codigoLiquidacionPk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];
        $session->set('filtroRhuLiquidacion', $filtro);
        return $filtro;
    }
}

