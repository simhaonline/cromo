<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;


use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoMotivo;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionAdicional;
use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use App\Entity\RecursoHumano\RhuParametroPrestacion;
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
            ->add('cboGrupo', EntityType::class, $em->getRepository(RhuGrupo::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroRhuContratoEstadoTerminado', $form->get('chkEstadoTerminado')->getData());
            $session->set('filtroRhuCodigoContrato', $form->get('txtCodigoContrato')->getData());
            $session->set('chkEstadoTerminado', $form->get('txtNombreEmpleado')->getData());
            $session->set('filtroRhuNumeroIdentificacionEmpleado', $form->get('txtNumeroIdentificacion')->getData());
            $arGrupo = $form->get('cboGrupo')->getData();
            if ($arGrupo) {
                $session->set('filtroRhuGrupo', $arGrupo->getCodigoGrupoPk());
            } else {
                $session->set('filtroRhuGrupo', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(RhuContrato::class)->lista(), "Contratos");
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
                $fechaActual =$dateNow = new \DateTime('now');
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
                    '#8' => $fechaActual->format('Y-m-d'),
                    '#9' => $salarioLetras]);
            }
            if($form->get('btnPdf')->isClicked()){
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);

                $interval = $arContrato->getFechaDesde()->diff($arContrato->getFechaHasta());
                $interval = round($interval->format('%a%') / 30);
                $salarioLetras =$em->getRepository(RhuContrato::class)->numtoletras($arContrato->getVrSalario());
                $formato = New Formato($em);
                $formato->generarFormatoContrato(5, [
                    '#1' => $arContrato->getEmpleadoRel()->getNumeroIdentificacion(),
                    '#2' => $arContrato->getEmpleadoRel()->getNombreCorto(),
                    '#3' => $arConfiguracion->getNit(),
                    '#4' => $arConfiguracion->getNombre(),
                    '#5' => $arContrato->getEmpleadoRel()->getDireccion(),
                    '#6' => $arConfiguracion->getDireccion(),
                    '#7' => $arContrato->getEmpleadoRel()->getBarrio(),
                    '#8' => $arContrato->getEmpleadoRel()->getFechaNacimiento()->format('Y/m/d'),
                    '#9' => $arContrato->getEmpleadoRel()->getCiudadNacimientoRel()->getNombre()??"",
                    '#a' => $arContrato->getEmpleadoRel()->getCiudadRel()->getNombre(),
                    '#b' => $arContrato->getCargoRel()->getNombre(),
                    '#c' => number_format($arContrato->getVrSalario(), 2, '.', ','),
                    '#d' => $arContrato->getCentroCostoRel()->getNombre(),
                    '#e' => "",
                    '#f' => $arContrato->getFechaDesde()->format('Y/m/d'),
                    '#g' => $arContrato->getFechaDesde()->format('Y/m/d'),
                    '#h' => $arContrato->getCiudadContratoRel()->getNombre(),
                    '#i' => $arContrato->getEmpleadoRel()->getCiudadExpedicionRel()->getNombre()??"",
                    '#j' => strftime("%d de " . $this->MesesEspañol($arContrato->getFechaDesde()->format('m')) . " de %Y", strtotime($arContrato->getFechaDesde()->format('Y/m/d'))),
                    '#k' => $arContrato->getTiempoRel()->getNombre(),
                    '#l' => strftime("%d de " . $this->MesesEspañol($arContrato->getFechaHasta()->format('m')) . " de %Y", strtotime($arContrato->getFechaDesde()->format('Y/m/d'))),
                    '#m' => $interval,
                    '#n' => $arContrato->getFechaHasta()->format('Y/m/d'),
                    '#o' => strftime("%d de " . $this->MesesEspañol    ($arContrato->getFechaHasta()->format('m')) . " de %Y", strtotime($arContrato->getFechaHasta()->format('Y/m/d'))),
                    '#p' =>  " - " . $arConfiguracion->getDigitoVerificacion(),
                    '#q' => $salarioLetras,
                    '#r' => $salarioLetras . " $(",
                    '#s' => number_format($arContrato->getVrSalario(), 2, '.', ','),
                    '#t' => ")",
                    '#u' => $arContrato->getCiudadContratoRel()->getNombre(),
                    '#v' => $arContrato->getCiudadLaboraRel()->getNombre(),
                    '#w' => date('Y-m-d'),
                    '#x' => $arContrato->getCentroCostoRel()->getNombre(),
                    '#y' => number_format($arContrato->getVrSalarioPago(), 2, '.', ','),
                    '#z' => $arContrato->getEntidadCesantiaRel() ? $arContrato->getEntidadCesantiaRel()->getNombre() : '',
                    '##' => $arContrato->getEntidadPensionRel() ? $arContrato->getEntidadPensionRel()->getNombre() : '',
                    '#.' => $arContrato->getEntidadSaludRel()->getNombre(),
                    '#-' => $arContrato->getEmpleadoRel()->getTelefono(),
                    'dp' =>  $arContrato->getCiudadContratoRel()->getDepartamentoRel()->getNombre(),
                    '#_' => "Sin puesto",
                    '#,' => "Sin cliente",
                    '#@' => "",
                ], $id);
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/detalle.html.twig', [
            'arContrato' => $arContrato,
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/nuevo/{id}", name="recursohumano_administracion_recurso_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
    }

    public static function MesesEspañol($mes) {

        if ($mes == '01'){
            $mesEspañol = "Enero";
        }
        if ($mes == '02'){
            $mesEspañol = "Febrero";
        }
        if ($mes == '03'){
            $mesEspañol = "Marzo";
        }
        if ($mes == '04'){
            $mesEspañol = "Abril";
        }
        if ($mes == '05'){
            $mesEspañol = "Mayo";
        }
        if ($mes == '06'){
            $mesEspañol = "Junio";
        }
        if ($mes == '07'){
            $mesEspañol = "Julio";
        }
        if ($mes == '08'){
            $mesEspañol = "Agosto";
        }
        if ($mes == '09'){
            $mesEspañol = "Septiembre";
        }
        if ($mes == '10'){
            $mesEspañol = "Octubre";
        }
        if ($mes == '11'){
            $mesEspañol = "Noviembre";
        }
        if ($mes == '12'){
            $mesEspañol = "Diciembre";
        }

        return $mesEspañol;
    }

}