<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuCambioSalario;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoMotivo;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionAdicional;
use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use App\Entity\RecursoHumano\RhuParametroPrestacion;
use App\Form\Type\RecursoHumano\ContratoActualizarTerminadoType;
use App\Form\Type\RecursoHumano\ContratoParametrosInicialesType;
use App\Form\Type\RecursoHumano\ContratoType;
use App\Formato\RecursoHumano\Contrato;
use App\General\General;
use App\Utilidades\Formato;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContratoController extends AbstractController
{
    protected $clase = RhuContrato::class;
    protected $claseFormulario = ContratoType::class;
    protected $claseNombre = "RhuContrato";
    protected $modulo = "RecursoHumano";
    protected $funcion = "administracion";
    protected $grupo = "Recurso";
    protected $nombre = "Contrato";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/administracion/recurso/contrato/lista", name="recursohumano_administracion_recurso_contrato_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoContrato', TextType::class, ['label' => 'Codigo contrato: ', 'required' => false, 'data' => $session->get('filtroRhuCodigoContrato')])
            ->add('txtNombreEmpleado', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroRhuNombreEmpleado')])
            ->add('txtNumeroIdentificacion', NumberType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroRhuNumeroIdentificacionEmpleado')])
            ->add('chkEstadoTerminado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroRhuContratoEstadoTerminado'), 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('cboGrupo', EntityType::class, $em->getRepository(RhuGrupo::class)->llenarCombo())
            ->add('cboContratoTipo', EntityType::class, $em->getRepository(RhuContratoTipo::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroRhuContratoEstadoTerminado', $form->get('chkEstadoTerminado')->getData());
            $session->set('filtroRhuCodigoContrato', $form->get('txtCodigoContrato')->getData());
            $session->set('filtroRhuNombreEmpleado', $form->get('txtNombreEmpleado')->getData());
            $session->set('chkEstadoTerminado', $form->get('chkEstadoTerminado')->getData());
            $session->set('filtroRhuNumeroIdentificacionEmpleado', $form->get('txtNumeroIdentificacion')->getData());
            $session->set('filtroRhuContratoFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
            $session->set('filtroRhuContratoFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
            $arGrupo = $form->get('cboGrupo')->getData();
            if ($arGrupo) {
                $session->set('filtroRhuGrupo', $arGrupo->getCodigoGrupoPk());
            } else {
                $session->set('filtroRhuGrupo', null);
            }
            $arGrupo = $form->get('cboContratoTipo')->getData();
            if ($arGrupo) {
                $session->set('filtroRhuContratoTipo', $arGrupo->getCodigoContratoTipoPk());
            } else {
                $session->set('filtroRhuContratoTipo', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            $arContratos = $em->getRepository(RhuContrato::class)->lista();
            $this->exportarExcelPersonalizado($arContratos);
        }
        if ($form->get('btnEliminar')->isClicked()) {
            $arrSeleccionados = $request->get('ChkSeleccionar');
            $em->getRepository(RhuContrato::class)->eliminar($arrSeleccionados);
        }
        $arContratos = $paginator->paginate($em->getRepository(RhuContrato::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/administracion/recurso/contrato/lista.html.twig',
            ['arContratos' => $arContratos,
                'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/{id}", name="recursohumano_administracion_recurso_contrato_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = new RhuContrato();
        if ($id != 0) {
            $arContrato = $em->getRepository(RhuContrato::class)->find($id);
            if (!$arContrato) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
            }
        }
        $form = $this->createFormBuilder()
            ->add('btnCartaLaboral', SubmitType::class, ['label' => 'Carta laboral', 'attr' => ['class' => 'btn btn-link']])
            ->add('btnPdf', SubmitType::class, ['label' => 'Imprimir', 'attr' => ['class' => 'btn btn-link']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnCartaLaboral')->isClicked()) {
                $fechaActual = $dateNow = new \DateTime('now');
                $salarioLetras = $em->getRepository(RhuContrato::class)->numtoletras($arContrato->getVrSalario());
                $formato = New Formato($em);
                $formato->generarFormatoCarta(4, [
                    '#1' => $arContrato->getEmpleadoRel()->getNombreCorto(),
                    '#2' => $arContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                    '#3' => $arContrato->getFechaDesde()->format('Y-m-d'),
                    '#4' => $arContrato->getFechaHasta()->format('y-m-d'),
                    '#5' => $arContrato->getContratoTipoRel()->getNombre(),
                    '#6' => $arContrato->getCargoRel()->getNombre(),
                    '#7' => number_format($arContrato->getVrSalario(), 0, '.', ','),
                    '#8' => strftime("%d de " . $this->MesesEspañol($fechaActual->format('m')) . " de %Y", strtotime($fechaActual->format('Y/m/d'))),
                    '#9' => $salarioLetras]);
            }
            if ($form->get('btnPdf')->isClicked()) {
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);

                $interval = $arContrato->getFechaDesde()->diff($arContrato->getFechaHasta());
                $interval = round($interval->format('%a%') / 30);
                $salarioLetras = $em->getRepository(RhuContrato::class)->numtoletras($arContrato->getVrSalario());
                $formato = New Formato($em);
                $formato->generarFormatoContrato(5, [
                    '#1' => $arContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                    '#2' => $arContrato->getEmpleadoRel()->getNombreCorto(),
                    '#3' => $arContrato->getEmpleadoRel()->getDireccion(),
                    '#4' => $arContrato->getEmpleadoRel()->getBarrio(),
                    '#5' => $arContrato->getEmpleadoRel()->getFechaNacimiento()->format('Y/m/d'),
                    '#6' => $arContrato->getEmpleadoRel()->getCiudadNacimientoRel()->getNombre(),
                    '#7' => $arContrato->getEmpleadoRel()->getCiudadRel()->getNombre(),
                    '#8' => $arContrato->getEmpleadoRel()->getTelefono(),
                    '#9' => $arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre(),
                    '#a' => $arContrato->getCiudadLaboraRel()->getNombre(),
                    '#b' => $arContrato->getCiudadContratoRel()->getNombre(),
                    '#c' => $arContrato->getCiudadContratoRel()->getDepartamentoRel()->getNombre(),
                    '#d' => strftime("%d de " . $this->MesesEspañol($arContrato->getFechaHasta()->format('m')) . " de %Y", strtotime($arContrato->getFechaDesde()->format('Y/m/d'))),
                    '#e' => $arContrato->getFechaDesde()->format('Y/m/d'),
                    '#f' => $arContrato->getFechaHasta()->format('Y/m/d'),
                    '#g' => strftime("%d de " . $this->MesesEspañol($arContrato->getFechaDesde()->format('m')) . " de %Y", strtotime($arContrato->getFechaDesde()->format('Y/m/d'))),
                    '#h' => $interval,
                    '#i' => number_format($arContrato->getVrSalarioPago(), 2, '.', ','),
                    '#j' => $salarioLetras,
                    '#k' => $arContrato->getCargoRel()->getNombre(),
                    '#l' => $arContrato->getCargoRel()->getNombre(),
                    '#m' => $arContrato->getEntidadCesantiaRel() ? $arContrato->getEntidadCesantiaRel()->getNombre() : '',
                    '#n' => $arContrato->getEntidadSaludRel() ? $arContrato->getEntidadSaludRel()->getNombre() : '',
                    '#o' => $arContrato->getEntidadPensionRel() ? $arContrato->getEntidadPensionRel()->getNombre() : '',
                    '#p' => $arConfiguracion->getNit(),
                    '#q' => $arConfiguracion->getDigitoVerificacion(),
                    '#r' => $arConfiguracion->getNombre(),
                    '#s' => $arConfiguracion->getDireccion(),
                    '#t' => "",
                    '#u' => $arContrato->getGrupoRel()->getNombre(),
                ], $id);
            }
        }

        $arCambiosSalario = $em->getRepository(RhuCambioSalario::class)->findBy(array('codigoContratoFk' => $id));
        return $this->render('recursohumano/administracion/recurso/contrato/detalle.html.twig', [
            'arContrato' => $arContrato,
            'arCambiosSalario'=>$arCambiosSalario,
            'clase' => array('clase' => 'RhuContrato', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/parametrosIniciales/{id}", name="recursohumano_administracion_recurso_contrato_detalle_parametrosIniciales")
     */
    public function parametrosIniciales(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = $em->getRepository(RhuContrato::class)->find($id);
        $form = $this->createForm(ContratoParametrosInicialesType::class, $arContrato);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arContrato);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/parametrosIniciales.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/actualizarterminado/{id}", name="recursohumano_administracion_recurso_contrato_detalle_actualizarterminado")
     */
    public function actualizarTerminado(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = $em->getRepository(RhuContrato::class)->find($id);
        $form = $this->createForm(ContratoActualizarTerminadoType::class, $arContrato);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arContrato);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/actualizarTerminado.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("recursohumano/administracion/recurso/contrato/detalle/terminar/{id}", name="recursohumano_administracion_recurso_contrato_detalle_terminar")
     */
    public function terminar(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = $em->getRepository(RhuContrato::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('liquidacionTipoRel', EntityType::class, [
                'class' => RhuLiquidacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('lt')
                        ->orderBy('lt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('terminacionContratoRel', EntityType::class, array(
                'class' => RhuContratoMotivo::class,
                'choice_label' => 'motivo',
            ))
            ->add('fechaTerminacion', DateType::class, array('label' => 'Terminacion', 'data' => new \DateTime('now')))
            ->add('comentarioTerminacion', TextareaType::class, array('required' => false))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dateFechaHasta = $form->get('fechaTerminacion')->getData();
            $codigoMotivoContrato = $form->get('terminacionContratoRel')->getData();
            $comentarioTerminacion = $form->get('comentarioTerminacion')->getData();
            if ($form->get('btnGuardar')->isClicked()) {
                /**
                 * @var $arContrato RhuContrato
                 */
                $arContrato->setFechaHasta($dateFechaHasta);
                $arContrato->setIndefinido(0);
                $arContrato->setEstadoTerminado(1);
                $arContrato->setContratoMotivoRel($codigoMotivoContrato);
                $arContrato->setComentarioTerminacion($comentarioTerminacion);
                $em->persist($arContrato);
                /**
                 * @var $arEmpleado RhuEmpleado
                 */
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arContrato->getCodigoEmpleadoFk());
                $arEmpleado->setCodigoClasificacionRiesgoFk(NULL);
                $arEmpleado->setCodigoCargoFk(NULL);
                $arEmpleado->setEstadoContrato(0);
                $arEmpleado->setCodigoContratoFk(NULL);
                $arEmpleado->setCodigoContratoUltimoFk($id);

                //Generar liquidacion
                if ($arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() != 'APR' && $arContrato->getContratoTipoRel()->getCodigoContratoClaseFk() != 'PRA') {
                    $arLiquidacion = new RhuLiquidacion();
                    $arLiquidacionTipo = $em->getRepository(RhuLiquidacionTipo::class)->find('GEN');
                    $arLiquidacion->setFecha(new \DateTime('now'));
                    $arLiquidacion->setEmpleadoRel($arContrato->getEmpleadoRel());
                    $arLiquidacion->setContratoRel($arContrato);
                    $arLiquidacion->setMotivoTerminacionRel($codigoMotivoContrato);
                    $arLiquidacion->setLiquidacionTipoRel($arLiquidacionTipo);
                    if ($arContrato->getFechaUltimoPagoCesantias() > $arContrato->getFechaDesde()) {
                        $arLiquidacion->setFechaDesde($arContrato->getFechaUltimoPagoCesantias());
                    } else {
                        $arLiquidacion->setFechaDesde($arContrato->getFechaDesde());
                    }
                    $arLiquidacion->setFechaHasta($arContrato->getFechaHasta());
                    $arLiquidacion->setLiquidarCesantias(1);
                    $arLiquidacion->setLiquidarPrima(1);
                    $arLiquidacion->setLiquidarVacaciones(1);
                    if ($arContrato->getSalarioIntegral() == 1) {
                        $arLiquidacion->setLiquidarCesantias(0);
                        $arLiquidacion->setLiquidarPrima(0);
                    }
                    //Para clientes que manejan porcentajes en la liquidacion
                    $arLiquidacion->setPorcentajeIbp(100);
                    $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
                    if ($arConfiguracion->getGeneraPorcentajeLiquidacion()) {
                        if ($arContrato->getCodigoSalarioTipoFk() == 2) {
                            if ($arLiquidacion->getCodigoContratoMotivoFk() != 'SJC' && $arLiquidacion->getCodigoContratoMotivoFk() != 'CJC') {
                                $intDiasLaborados = $em->getRepository(RhuLiquidacion::class)->diasPrestaciones($arContrato->getFechaDesde(), $arContrato->getFechaHasta());
                                $arParametrosPrestacion = $em->getRepository(RhuParametroPrestacion::class)->findBy(array('tipo' => 'LIQ'));
                                foreach ($arParametrosPrestacion as $arParametroPrestacion) {
                                    if ($intDiasLaborados >= $arParametroPrestacion->getDiaDesde() && $intDiasLaborados <= $arParametroPrestacion->getDiaHasta()) {
                                        if ($arParametroPrestacion->getOrigen() == 'SAL') {
                                            $arLiquidacion->setLiquidarSalario(1);
                                        } else {
                                            $arLiquidacion->setPorcentajeIbp($arParametroPrestacion->getPorcentaje());
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Calcular deducciones credito

                    $arCreditos = $em->getRepository(RhuCredito::class)->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'codigoCreditoPagoTipoFk' => 'NOM', 'estadoPagado' => 0, 'estadoSuspendido' => 0));
                    foreach ($arCreditos as $arCredito) {
                        $arLiquidacionAdicional = new RhuLiquidacionAdicional();
                        $arLiquidacionAdicional->setCreditoRel($arCredito);
                        $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                        $arLiquidacionAdicional->setVrDeduccion($arCredito->getVrSaldo());
                        $arLiquidacionAdicional->setConceptoRel($arCredito->getCreditoTipoRel()->getConceptoRel());
                        $em->persist($arLiquidacionAdicional);

                    }

                    // calcular adicionales al pago permanentes
                    //$arAdicionales = $em->getRepository(RhuAdicional::class)->findBy(['codigoContratoFk' => $arContrato->getCodigoContratoPk(), 'permanente' => 1, 'estadoInactivo' => 0]);
                    $arAdicionales = $em->getRepository(RhuAdicional::class)->deduccionLiquidacion($arContrato->getCodigoContratoPk());
                    foreach ($arAdicionales as $arAdicional) {
                        $arConcepto = $em->getRepository(RhuConcepto::class)->find($arAdicional['codigoConceptoFk']);
                        $arLiquidacionAdicional = new RhuLiquidacionAdicional();
                        $arLiquidacionAdicional->setLiquidacionRel($arLiquidacion);
                        $arLiquidacionAdicional->setConceptoRel($arConcepto);
                        $arLiquidacionAdicional->setVrDeduccion($arAdicional['vrValor']);
                        $em->persist($arLiquidacionAdicional);
                    }

                    $em->persist($arLiquidacion);
                }

                $em->persist($arEmpleado);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/terminar.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("recursohumano/administracion/recurso/contrato/informacion/{codigoContrato}", name="recursohumano_administracion_recurso_contrato_informacion")
     */
    public function informacionContrato(Request $request, $codigoContrato)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = $em->getRepository(RhuContrato::class)->find($codigoContrato);
        return $this->render('recursohumano/administracion/recurso/contrato/informacion.html.twig', [
            'arContrato' => $arContrato,
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/nuevo/{id}", name="recursohumano_administracion_recurso_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
    }

    public static function MesesEspañol($mes)
    {

        if ($mes == '01') {
            $mesEspañol = "Enero";
        }
        if ($mes == '02') {
            $mesEspañol = "Febrero";
        }
        if ($mes == '03') {
            $mesEspañol = "Marzo";
        }
        if ($mes == '04') {
            $mesEspañol = "Abril";
        }
        if ($mes == '05') {
            $mesEspañol = "Mayo";
        }
        if ($mes == '06') {
            $mesEspañol = "Junio";
        }
        if ($mes == '07') {
            $mesEspañol = "Julio";
        }
        if ($mes == '08') {
            $mesEspañol = "Agosto";
        }
        if ($mes == '09') {
            $mesEspañol = "Septiembre";
        }
        if ($mes == '10') {
            $mesEspañol = "Octubre";
        }
        if ($mes == '11') {
            $mesEspañol = "Noviembre";
        }
        if ($mes == '12') {
            $mesEspañol = "Diciembre";
        }

        return $mesEspañol;
    }

    public function exportarExcelPersonalizado($arContratos)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arContratos) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('CONTRATOS');
            $j = 0;
            $arrColumnas = [
                'ID', 'COD EMP', 'DOCUMENTO', 'TIPO COMPENSACION', 'EMPLEADO', 'TIPO', 'FECHA', 'GRUPO DE PAGO', 'ENTIDAD SALUD',
                'ENTIDAD PENSIÓN', 'ENTIDAD CAJA', 'ENTIDAD CESANTIA', 'COTIZANTE', 'SUBCOTIZANTE', 'TIEMPO', 'DESDE', 'HASTA',
                'SALARIO', 'TIPO SALARIO', 'DEVENGADO PACTADO', 'CARGO', 'CARGO DESCRIPCION', 'RIESGO', 'ULT. PAGO', 'ULT.PAGO PRIMAS',
                'ULT. CESANTIAS', 'ULT. PAGO VACACIONES', 'TERMINADO', 'LHE', 'IBP_CESANTIAS INICIAL',
                'IBP_PRIMAS INICIAL', 'CENTRO DE TRABAJO', 'GENERO', 'ZONA', 'CIUDAD', 'MOTIVO TERMINACION', 'COMENTARIOS',
                'SALARIO INTEGRAL', 'AUX TRANSPORTE', 'CAUSA', 'PRIMER NOMBRE', 'SEGUNDO NOMBRE', 'PRIMER APELLIDO', 'SEGUNDO APELLIDO',
                'LIQUIDADO'
            ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arContratos as $arContrato) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);

                $hoja->setCellValue('A' . $j, $arContrato['codigoContratoPk']);
                $hoja->setCellValue('B' . $j, $arContrato['codigoEmpleadoFk']);
                $hoja->setCellValue('C' . $j, $arContrato['numeroIdentificacion']);
                $hoja->setCellValue('D' . $j, $arContrato['distribucion']);
                $hoja->setCellValue('E' . $j, $arContrato['empleado']);
                $hoja->setCellValue('F' . $j, $arContrato['tipo']);
                $hoja->setCellValue('G' . $j, $arContrato['fecha']->format('Y/m/d'));
                $hoja->setCellValue('H' . $j, $arContrato['nombreGrupo']);
                $hoja->setCellValue('I' . $j, $arContrato['salud']);
                $hoja->setCellValue('J' . $j, $arContrato['pension']);
                $hoja->setCellValue('K' . $j, $arContrato['caja']);
                $hoja->setCellValue('L' . $j, $arContrato['cesantia']);
                $hoja->setCellValue('M' . $j, $arContrato['cotizante']);
                $hoja->setCellValue('N' . $j, $arContrato['subCotizante']);
                $hoja->setCellValue('O' . $j, $arContrato['tiempo']);
                $hoja->setCellValue('P' . $j, $arContrato['fechaDesde']->format('Y/m/d'));
                $hoja->setCellValue('Q' . $j, $arContrato['fechaHasta']->format('Y/m/d'));
                $hoja->setCellValue('R' . $j, $arContrato['vrSalario']);
                $hoja->setCellValue('S' . $j, $arContrato['salarioTipo']);
                $hoja->setCellValue('T' . $j, $arContrato['vrDevengadoPactado']);
                $hoja->setCellValue('U' . $j, $arContrato['cargo']);
                $hoja->setCellValue('V' . $j, $arContrato['cargoDescripcion']);
                $hoja->setCellValue('W' . $j, $arContrato['riesgo']);
                $hoja->setCellValue('X' . $j, $arContrato['fechaUltimoPago']->format('Y/m/d'));
                $hoja->setCellValue('Y' . $j, $arContrato['fechaUltimoPagoPrimas']->format('Y/m/d'));
                $hoja->setCellValue('Z' . $j, $arContrato['fechaUltimoPagoCesantias']->format('Y/m/d'));
                $hoja->setCellValue('AA' . $j, $arContrato['fechaUltimoPagoVacaciones']->format('Y/m/d'));
                $hoja->setCellValue('AB' . $j, $arContrato['estadoTerminado']);
                $hoja->setCellValue('AC' . $j, ""); //LHE
                $hoja->setCellValue('AD' . $j, $arContrato['ibpCesantiasInicial']);
                $hoja->setCellValue('AE' . $j, $arContrato['ibpPrimasInicial']);
                $hoja->setCellValue('AF' . $j, $arContrato['centroTrabajo']);
                $hoja->setCellValue('AG' . $j, $arContrato['sexo']);
                $hoja->setCellValue('AH' . $j, "");//zona
                $hoja->setCellValue('AI' . $j, $arContrato['ciudadContrato']);
                $hoja->setCellValue('AJ' . $j, $arContrato['motivo']);
                $hoja->setCellValue('AK' . $j, $arContrato['comentarioTerminacion']);
                $hoja->setCellValue('AL' . $j, $arContrato['salarioIntegral']);
                $hoja->setCellValue('AM' . $j, $arContrato['auxilioTransporte']);
                $hoja->setCellValue('AN' . $j, ""); //causa
                $hoja->setCellValue('AO' . $j, $arContrato['nombre1']);
                $hoja->setCellValue('AP' . $j, $arContrato['nombre2']);
                $hoja->setCellValue('AQ' . $j, $arContrato['apellido1']);
                $hoja->setCellValue('AR' . $j, $arContrato['apellido2']);
                $hoja->setCellValue('AS' . $j, $arContrato['liquidado']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=contratos.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }

}