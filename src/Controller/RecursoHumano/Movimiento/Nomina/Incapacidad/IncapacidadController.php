<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina\Incapacidad;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuIncapacidadDiagnostico;
use App\Entity\RecursoHumano\RhuIncapacidadTipo;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Form\Type\RecursoHumano\IncapacidadType;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IncapacidadController extends ControllerListenerGeneral
{
    protected $clase = RhuIncapacidad::class;
    protected $claseNombre = "RhuIncapacidad";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Incapacidad";

    /**
     * @Route("recursohumano/moviento/nomina/Incapacidad/lista", name="recursohumano_movimiento_nomina_incapacidad_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoPk', TextType::class, array('required' => false))
            ->add('numeroEps', TextType::class, array('required' => false))
            ->add('tipoIncapacidad', ChoiceType::class, ['choices' => ['TODOS' => '', 'GENERAL' => '1', 'LABORAL' => '0'], 'required' => false])
            ->add('entidadSaludRel', EntityType::class, [
                'class' => RhuEntidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.eps = 1')
                        ->orderBy('r.nombre', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('grupoRel', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('tipoLegalizada', ChoiceType::class, ['choices' => ['TODOS' => '', 'LEGALIZADA' => '1', 'SIN LEGALIZADA' => '0'], 'required' => false])
            ->add('estadoTranscripcion', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaIncapacidadDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuIncapacidadFechaDesde') ? date_create($session->get('filtroTteGuiaFechaIngresoDesde')): null])
            ->add('fechaIncapacidadHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuIncapacidadFechaHasta') ? date_create($session->get('filtroRhuIncapacidadFechaHasta')): null])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add( 'btnEliminar', SubmitType::class, ['label'=>'Eliminar', 'attr'=>['class'=> 'btn btn-sm btn-danger']])
          ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted())
            if ($form->get('btnFiltro')->isClicked()) {
                $codigoEmpleado =$form->get('codigoEmpleadoPk')->getData();
                $arEntidadSalud =$form->get('entidadSaludRel')->getData();
                $arGrupoRel =$form->get('grupoRel')->getData();
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado??0);
                if ($arEmpleado){
                    $session->set('filtroRhuIncapacidadCodigoEmpleado',  $arEmpleado->getCodigoEmpleadoPk());
                }else{
                    $session->set('filtroRhuIncapacidadCodigoEmpleado',  null);
                }
                if ($arEntidadSalud){
                    $session->set('filtroRhuIncapacidadCodigoEntidadSalud',  $arEntidadSalud->getCodigoEntidadPk());
                }else{
                    $session->set('filtroRhuIncapacidadCodigoEntidadSalud',  null);
                }
                if ($arGrupoRel){
                    $session->set('filtroRhuIncapacidadCodigoGrupo',  $arGrupoRel->getCodigoGrupoPk());
                }else{
                    $session->set('filtroRhuIncapacidadCodigoGrupo',  null);
                }
                $session->set('filtroRhuIncapacidadNumeroEps',  $form->get('numeroEps')->getData());
                $session->set('filtroRhuIncapacidadLegalizada',  $form->get('tipoLegalizada')->getData());
                $session->set('filtroRhuIncapacidadEstadoTranscripcion',  $form->get('estadoTranscripcion')->getData());
                $session->set('filtroRhuIncapacidadTipoIncapacidad',  $form->get('tipoIncapacidad')->getData());
                $session->set('filtroRhuIncapacidadFechaDesde',  $form->get('fechaIncapacidadDesde')->getData() ?$form->get('fechaIncapacidadDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuIncapacidadFechaHasta', $form->get('fechaIncapacidadHasta')->getData() ? $form->get('fechaIncapacidadHasta')->getData()->format('Y-m-d'): null);
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arClienterSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuIncapacidad::class, $arClienterSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_incapacidad_lista'));
            }


        $arIncapacidades = $paginator->paginate($em->getRepository(RhuIncapacidad::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/incapacidad/lista.html.twig', [
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/Incapacidad/nuevo/{id}", name="recursohumano_movimiento_nomina_incapacidad_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIncapacidad = new RhuIncapacidad();
        if ($id != 0) {
            $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($id);
            if (!$arIncapacidad) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_incapacidad_lista'));
            }
        }
        $form = $this->createForm(IncapacidadType::class, $arIncapacidad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arIncapacidad = $form->getData();
                $codigoEmpleado = $request->request->get('form_txtNumeroIdentificacion');
                $codigoIncapacidadDiagnostico = $request->request->get('form_txtCodigoIncapacidadDiagnostico');
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado);
                $arIncapacidadDiagnostico = $em->getRepository(RhuIncapacidadDiagnostico::class)->find($codigoIncapacidadDiagnostico);
                $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
                $arConfiguracionAporte = $em->getRepository(RhuConfiguracion::class)->find(1);
                if ($arEmpleado){
                    if ( is_null($arEmpleado->getcodigoContratoUltimoFk()) ) {
                        $codigoContrato = $arEmpleado->getCodigoContratoFk();
                    } else {
                        $codigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                    }
                    if ($codigoContrato != "") {
                        $arContrato = $em->getRepository(RhuContrato::class)->findOneBy(['codigoEmpleadoFk'=>$arEmpleado->getCodigoEmpleadoPk()]);
                        if ($arContrato){
                            if (($arIncapacidad->getFechaHasta() > $arContrato->getFechaHasta()) && ($arContrato->getEstadoTerminado() == 1)) {
                                $strRespuesta = "El empleado tiene contrato terminado y la fecha de terminacion es inferior o igual a la fecha hasta de la incapacidad.";
                                $boolRespuesta = FALSE;
                            }else{
                                $boolRespuesta = true;
                            }
                            if ($boolRespuesta) {
                                $arIncapacidad->setEmpleadoRel($arEmpleado);
                                if ($arIncapacidadDiagnostico){
                                    $arIncapacidad->setIncapacidadDiagnosticoRel($arIncapacidadDiagnostico);
                                    if ($arIncapacidad->getFechaDesde() <= $arIncapacidad->getFechaHasta()) {
                                        $diasIncapacidad = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
                                        $diasIncapacidad = $diasIncapacidad->format('%a');
                                        $diasIncapacidad = $diasIncapacidad + 1;
                                        if ($diasIncapacidad < 180) {
                                            $boolLicencia = $em->getRepository(RhuLicencia::class)->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), "");
                                            if ($em->getRepository(RhuIncapacidad::class)->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arIncapacidad->getCodigoIncapacidadPk())) {
                                                $boolVacacion = $em->getRepository(RhuVacacion::class)->validarVacacion($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk());
                                                if ($boolVacacion){
                                                    if ($boolLicencia) {
                                                        if ($arIncapacidad->getFechaDesde() >= $arContrato->getFechaDesde()) {
                                                            $arEntidad = null;
                                                            if ($arIncapacidad->getIncapacidadTipoRel()->getTipo() == 1) {
                                                                $arEntidad = $arContrato->getEntidadSaludRel();
                                                            } else {
                                                                if ($arConfiguracionAporte->getCodigoEntidadRiesgosProfesionalesFk()) {
                                                                    $arEntidad = $em->getRepository(RhuEntidad::class)->find($arConfiguracionAporte->getCodigoEntidadRiesgosProfesionalesFk());
                                                                }
                                                            }
                                                            if ($arEntidad) {
                                                                $arIncapacidad->setEntidadSaludRel($arEntidad);
                                                                $arIncapacidad->setCodigoUsuario($this->getUser()->getUserName());
                                                                $arIncapacidad->setContratoRel($arContrato);
                                                                $arIncapacidad->setGrupoRel($arContrato->getGrupoRel());
                                                                $arIncapacidad->setFecha(new \DateTime('now'));
//                                                                $arIncapacidad->setClienteRel($arIncapacidad->getGrupoRel()->getClienteRel());
                                                                //validar prologar
                                                                $respuesta = $em->getRepository(RhuIncapacidad::class)->liquidar($arIncapacidad, $this->getUser());
                                                                if ($respuesta == "") {
                                                                    $em->persist($arIncapacidad);
//                                                                    if ($arConfiguracion->getGenerarNovedadIncapacidadTurnos()) {
//                                                                        $em->getRepository(RhuIncapacidad::class)->generarNovedadTurnos($arIncapacidad,  $this->getUser()->getUserName(), $arEmpleado->getCodigoEmpleadoPk());
//                                                                    }
                                                                    try {
                                                                        $em->flush();
                                                                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_incapacidad_detalle', array('id' => $arIncapacidad->getCodigoIncapacidadPk())));
                                                                    } catch (\Exception $exception) {
                                                                        Mensajes::error("El registro no puede ser actualizada por que ya esta siendo usado en el sistema");
                                                                    }
                                                                }else{
                                                                    Mensajes::error($respuesta);
                                                                }
                                                            }else{
                                                                Mensajes::error("El empleado no tiene una entidad asociada o en configuracion aportes no esta configurada una entidad de riesgos");
                                                            }
                                                        }else{
                                                            Mensajes::error("No puede ingresar novedades antes de la fecha de inicio del contrato");
                                                        }
                                                    }else{
                                                        Mensajes::error("Existe una licencia en este periodo de fechas");
                                                    }
                                                }else {
                                                    Mensajes::error("Existe una vacación para este periodo de fechas");
                                                }
                                            }else{
                                                Mensajes::error("Existe otra incapaciad del empleado en esta fecha");
                                            }
                                        }else{
                                            Mensajes::error("La incapacidad no debe ser mayor 180 dias");
                                        }
                                    }else{
                                        Mensajes::error("La fecha desde debe ser inferior o igual a la fecha hasta de la incapacidad");
                                    }
                                }else{
                                    Mensajes::error("El diagnostico no existe");
                                }
                            }else{
                                Mensajes::error($strRespuesta);
                            }
                        }else{
                            Mensajes::error("No se encontro un contrato con ID {$codigoContrato}.");
                        }
                    }else{
                        Mensajes::error("El empleado no tiene contrato");
                    }
                }else{
                    Mensajes::error("El empleado no existe");
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/incapacidad/nuevo.html.twig', [
            'form' => $form->createView(),
            'arIncapacidad' => $arIncapacidad,
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/Incapacidad/detalle/{id}", name="recursohumano_movimiento_nomina_incapacidad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($id);
            if (!$arIncapacidad) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_incapacidad_incapacidad_lista'));
            }
        }
        $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($id);
        $form = Estandares::botonera($arIncapacidad->getEstadoAutorizado(), $arIncapacidad->getEstadoAprobado(), $arIncapacidad->getEstadoAnulado());

        return $this->render('recursohumano/movimiento/nomina/incapacidad/detalle.html.twig', [
            'arIncapacidad' => $arIncapacidad,
            'form'=>$form->createView()
        ]);

    }

}