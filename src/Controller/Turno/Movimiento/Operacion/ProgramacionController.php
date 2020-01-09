<?php

namespace App\Controller\Turno\Movimiento\Operacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurPedidoTipo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurTurno;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProgramacionController extends ControllerListenerGeneral
{
    protected $clase = TurPedido::class;
    protected $claseNombre = "TurPedido";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Pedido";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/operacion/programacion/lista", name="turno_movimiento_operacion_programacion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $fechaActual = new \DateTime('now');
        $intUltimoDia = date("d", (mktime(0, 0, 0, $fechaActual->format('m') + 1, 1, $fechaActual->format('Y')) - 1));
        $primerDiaDelMes = $fechaActual->format('Y/m/') . "01";
        $ultimoDiaDelMes = $fechaActual->format('Y/m/') . $intUltimoDia;
        $session->set('filtroTurPedidoProgramacionFechaDesde', $primerDiaDelMes);
        $session->set('filtroTurPedidoProgramacionFechaHasta', $ultimoDiaDelMes);
        $paginator = $this->get('knp_paginator');
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
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurPedidoProgramacionFechaDesde') ? date_create($session->get('filtroTurPedidoProgramacionFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurPedidoProgramacionFechaHasta') ? date_create($session->get('filtroTurPedidoProgramacionFechaHasta')) : null])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $arPedidoTipo = $form->get('codigoPedidoTipoFk')->getData();
                $session->set('filtroTurPedidoProgramacionCodigoCliente', $form->get('codigoClienteFk')->getData());
                $session->set('filtroTurPedidoProgramacionNumero', $form->get('numero')->getData());
                $session->set('filtroTurPedidoProgramacionCodigoPedido', $form->get('codigoPedidoPk')->getData());
                if ($arPedidoTipo != '') {
                    $session->set('filtroTurPedidoProgramacionCodigoPedidoTipo', $arPedidoTipo->getCodigoPedidoTipoPk());
                } else {
                    $session->set('filtroTurPedidoProgramacionCodigoPedidoTipo', null);
                }
                $session->set('filtroTurPedidoProgramacionEstadoAutorizado', $form->get('estadoAutorizado')->getData());
                $session->set('filtroTurPedidoProgramacionEstadoAprobado', $form->get('estadoAprobado')->getData());
                $session->set('filtroTurPedidoProgramacionEstadoAnulado', $form->get('estadoAnulado')->getData());
                $session->set('filtroTurPedidoProgramacionFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroTurPedidoProgramacionFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurPedido::class)->lista(), "pedidos");

            }

        }
        $arPedidos = $paginator->paginate($em->getRepository(TurPedido::class)->lista(), $request->query->getInt('page', 1), 100);
        return $this->render('turno/movimiento/operacion/programacion/lista.html.twig', [
            'arPedidos' => $arPedidos,
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
     * @Route("/turno/movimiento/operacion/programacion/detalle/{id}", name="turno_movimiento_operacion_programacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPedido = $em->getRepository(TurPedido::class)->find($id);
        $dateFecha = (new \DateTime('now'));
        $arrDiaSemana = "";
        $form = $this->createFormBuilder()
            ->getForm();

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurProgramacion::class)->eliminar($arrDetallesSeleccionados);
            }
            return $this->redirect($this->generateUrl('turno_movimiento_operacion_programacion_detalle', ['id' => $id]));
        }
        $arrDiaSemana = FuncionesController::diasMes($dateFecha, $em->getRepository(TurFestivo::class)->festivos($dateFecha->format('Y-m-') . '01', $dateFecha->format('Y-m-t')));
        $arPedidoDetalles = $em->getRepository(TurProgramacion::class)->detalleProgramacion($id);

        return $this->render('turno/movimiento/operacion/programacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arrDiaSemana' => $arrDiaSemana,
            'arPedidoDetalles' => $arPedidoDetalles,
            'arPedido' => $arPedido
        ]);
    }

    /**
     * @Route("/turno/movimiento/operacion/programacion/recurso/{codigoPedidoDetalle}/{codigoEmpleado}", name="turno_movimiento_operacion_programacion_recurso")
     */
    public function programacionRecurso(Request $request, $codigoPedidoDetalle, $codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $arPedidoDetalle TurPedidoDetalle */
        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
        $strAnioMes = $arPedidoDetalle->getAnio() . "/" . $arPedidoDetalle->getMes();
        $arrDiaSemana = array();
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = ['dia' => $i, 'diaSemana' => $diaSemana];
        }
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->add('btnComplementario', SubmitType::class, array('label' => 'Complementario'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrControles = $request->request->All();
                $resultado = $this->actualizarDetalle($arrControles);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('btnComplementario')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arProgramacionDetalle = $em->getRepository(TurProgramacion::class)->find($codigo);
                        if ($arProgramacionDetalle->getComplementario()) {
                            $arProgramacionDetalle->setComplementario(false);
                        } else {
                            $arProgramacionDetalle->setComplementario(true);
                        }
                        $em->persist($arProgramacionDetalle);
                    }
                    $em->flush();
                }
            }
        }
        $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy(array('anio' => $arPedidoDetalle->getAnio(), 'mes' => $arPedidoDetalle->getMes(), 'codigoEmpleadoFk' => $codigoEmpleado));
        return $this->render('turno/movimiento/operacion/programacion/programacionRecurso.html.twig', [
            'arrDiaSemana' => $arrDiaSemana,
            'arProgramaciones' => $arProgramaciones,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/movimiento/operacion/programacion/puesto/{codigoPedidoDetalle}", name="turno_movimiento_operacion_programacion_puesto")
     */
    public function programacionPuesto(Request $request, $codigoPedidoDetalle )
    {
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
        if (!$arPedidoDetalle) {
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arPuesto = $em->getRepository(TurPuesto::class)->find($arPedidoDetalle->getCodigoPuestoFk());
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
//                if ($arProgramacion->getEstadoAutorizado() == 0) {
                    $arrControles = $request->request->All();
                    $resultado = $this->actualizarDetalle2($arrControles, $codigoPedidoDetalle);
                    if ($resultado == false) {
                        $em->flush();
                        $em->getRepository(TurProgramacion::class)->liquidar($codigoProgramacion);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
//                }
//                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $strAnioMes = $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y/m');
        $arrDiaSemana = array();
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = ['dia' => $i, 'diaSemana' => $diaSemana];
        }

        $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy(array('codigoPedidoDetalleFk' => $codigoPedidoDetalle));
        $mes = $arPedidoDetalle->getMes();
        $anio = $arPedidoDetalle->getAnio();
        //$arConfiguracion = $em->getRepository("BrasaTurnoBundle:TurConfiguracion")->find(1);
        //$bloquearInicios = $arConfiguracion->getBloquearTurnosInicioRecurso();
        //$diaBloqueoPeriodoProgramacion = $em->getRepository("BrasaTurnoBundle:TurCierreProgramacionPeriodo")->getDiaHastaBloqueo($arProgramacion->getAnio(), $arProgramacion->getMes());

        $fechaActual = $anio . "-" . ($mes < 10 ? '0' . $mes : $mes) . "-01";
        return $this->render('turno/movimiento/operacion/programacion/programacionPuesto.html.twig', [
            'fechaActual' => $fechaActual,
            'arrDiaSemana' => $arrDiaSemana,
            'arPuesto' => $arPuesto,
            'arrDiaSemana' => $arrDiaSemana,
            'arProgramaciones' => $arProgramaciones,
            'form' => $form->createView()
        ]);
    }

    public function devuelveDiaSemanaEspaniol($dateFecha)
    {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "L";
                break;
            case 2:
                $strDia = "M";
                break;
            case 3:
                $strDia = "I";
                break;
            case 4:
                $strDia = "J";
                break;
            case 5:
                $strDia = "V";
                break;
            case 6:
                $strDia = "S";
                break;
            case 7:
                $strDia = "D";
                break;
        }

        return $strDia;
    }

    private function actualizarDetalle($arrControles)
    {
        $em = $this->getDoctrine()->getManager();
        $error = false;
        foreach ($arrControles['LblCodigo'] as $codigoProgramacion) {
            $arProgramacion = $em->getRepository(TurProgramacion::class)->find($codigoProgramacion);
            if ($arProgramacion) {
                $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($arProgramacion->getCodigoPedidoDetalleFk());
                $validar = $this->validarHoras($codigoProgramacion, $arrControles);
                if ($validar['validado']) {
                    $horasDiurnasPendientes = $arPedidoDetalle->getHorasDiurnas() - $arPedidoDetalle->getHorasDiurnasProgramadas();
                    $horasNocturnasPendientes = $arPedidoDetalle->getHorasNocturnas() - $arPedidoDetalle->getHorasNocturnasProgramadas();
                    $diffDiurnas = $validar['horasDiurnas'] - $validar['horasDiurnasLinea'];
                    $diffNocturnas = $validar['horasNocturnas'] - $validar['horasNocturnasLinea'];
                    //if ($horasDiurnasPendientes >= $diffDiurnas) {
                    //if ($horasNocturnasPendientes >= $diffNocturnas) {
                    $horasDiurnasProgramadas = ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacion->getHorasDiurnas()) + $validar['horasDiurnas'];
                    $horasNocturnasProgramadas = ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacion->getHorasNocturnas()) + $validar['horasNocturnas'];
                    $horasProgramadas = $horasDiurnasProgramadas + $horasNocturnasProgramadas;
                    $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnasProgramadas);
                    $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnasProgramadas);
                    $arPedidoDetalle->setHorasProgramadas($horasProgramadas);
                    $em->persist($arPedidoDetalle);
                    for ($i = 1; $i <= 31; $i++) {
                        $indice = "TxtDia" . ($i < 10 ? "0{$i}" : $i) . "D" . $codigoProgramacion;
                        if (isset($arrControles[$indice])) {
                            call_user_func_array([$arProgramacion, "setDia{$i}"], [$arrControles[$indice]]);
                        }
                    }
                    $arProgramacion->setHorasDiurnas($validar['horasDiurnas']);
                    $arProgramacion->setHorasNocturnas($validar['horasNocturnas']);
                    $arProgramacion->setHoras($validar['horasDiurnas'] + $validar['horasNocturnas']);
                    $em->persist($arProgramacion);
                    $em->flush();
                    //} else {
                    //    $error = true;
                    //    Mensajes::error("error", "Horas nocturnas superan las horas del pedido disponibles para programar detalle " );
                    //}
                    //} else {
                    //    $error = true;
                    //    Mensajes::error("error", "Horas diurnas superan las horas del pedido disponibles para programar detalle " );
                    //}
                } else {
                    $error = true;
                    Mensajes::error("error", $validar['mensaje']);
                }

            } else {
                $error = true;
                Mensajes::error("error", "No se encontro un detalle de programacion con este codigo");
            }
            if ($error) {
                break;
            }
        }
        return $error;
    }

    private function validarTurno($strTurno)
    {
        $em = $this->getDoctrine()->getManager();
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'errado' => false);
        if ($strTurno != "") {
            $arTurno = $em->getRepository(TurTurno::class)->find($strTurno);
            if ($arTurno) {
                $arrTurno['turno'] = $strTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();
            } else {
                $arrTurno['errado'] = true;
            }
        }

        return $arrTurno;
    }

    private function validarHoras($codigoProgramacion, $arrControles)
    {
        $em = $this->getDoctrine()->getManager();
        $arrDetalle = [
            'validado' => true,
            'horasDiurnas' => 0,
            'horasNocturnas' => 0,
            'mensaje' => '',
            'horasNocturnasLinea' => 0,
            'horasDiurnasLinea' => 0
        ];
        $horasDiurnas = 0;
        $horasNocturnas = 0;
        # Horas que tiene la linea de programación actualmente.
        $horasDiurnasLinea = 0;
        $horasNocturnasLinea = 0;

        $arProgramacion = $em->getRepository(TurProgramacion::class)->find($codigoProgramacion);
        for ($i = 1; $i <= 31; $i++) {
            # Obtenemos los turnos de la linea actual y sus respectivas horas.
            $codigoTurnoActual = call_user_func_array([$arProgramacion, "getDia{$i}"], []);
            if ($codigoTurnoActual && $turnoActual = $em->getRepository(TurTurno::class)->find($codigoTurnoActual)) {
                $horasDiurnasLinea += $turnoActual->getHorasDiurnas();
                $horasNocturnasLinea += $turnoActual->getHorasNocturnas();
            }

            $dia = $i;
            if (strlen($dia) < 2) {
                $dia = "0" . $i;
            }
            $indice = 'TxtDia' . $dia . 'D' . $codigoProgramacion;
            if (isset($arrControles[$indice]) && $arrControles[$indice] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia' . $dia . 'D' . $codigoProgramacion]);
                if ($arrTurno['errado'] == true) {
                    $arrDetalle['validado'] = false;
                    $arrDetalle['mensaje'] = "Turno " . $arrControles['TxtDia' . $dia . 'D' . $codigoProgramacion] . " no esta creado";
                    break;
                }
                $horasDiurnas += $arrTurno['horasDiurnas'];
                $horasNocturnas += $arrTurno['horasNocturnas'];
            }
        }

        $arrDetalle['horasDiurnas'] = $horasDiurnas;
        $arrDetalle['horasNocturnas'] = $horasNocturnas;
        $arrDetalle['horasDiurnasLinea'] = $horasDiurnasLinea;
        $arrDetalle['horasNocturnasLinea'] = $horasNocturnasLinea;

        return $arrDetalle;
    }

    private function actualizarDetalle2($arrControles, $codigoPedidoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $error = false;
        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find(1);
        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($codigoPedidoDetalle);
        $puestoRequiereArma = $arPedidoDetalle->getCodigoModalidadFk() == $arConfiguracion->getCodigoModalidadArma();

        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();
        $intIndice = 0;
        $boolTurnosSobrepasados = false;
        $cursoActivo = false;
        $intDiaVenceCurso = 0;
        $arrTotalHoras = $this->horasControles($arrControles);
        $horasDiurnasPendientes = $arPedidoDetalle->getHorasDiurnas() - ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arrTotalHoras['horasDiurnasProgramacion']);
        $horasDiurnasRestantes = $horasDiurnasPendientes - $arrTotalHoras['horasDiurnas'];
        $horasNocturnasPendientes = $arPedidoDetalle->getHorasNocturnas() - ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arrTotalHoras['horasNocturnasProgramacion']);
        $horasNocturnasRestantes = $horasNocturnasPendientes - $arrTotalHoras['horasNocturnas'];
        if ($validarHoras) {
            if ($horasDiurnasRestantes < 0) {
                $error = TRUE;
                Mensajes::error("Las horas diurnas de los turnos ingresadas [" . $arrTotalHoras['horasDiurnas'] . "], superan las horas del pedido disponibles para programar [" . $horasDiurnasPendientes . "]");
            }
            if ($horasNocturnasRestantes < 0) {
                $error = TRUE;
                Mensajes::error("Las horas nocturnas de los turnos ingresadas [" . $arrTotalHoras['horasNocturnas'] . "], superan las horas del pedido disponibles para programar [" . $horasNocturnasPendientes . "]");
            }
        }
        if ($error == FALSE) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arProgramacion = $em->getRepository(TurProgramacion::class)->find($intCodigo);
                $validar = $this->validarHoras($intCodigo, $arrControles);
                if ($validar['validado']) {
                    if ($arProgramacion->getPeriodoBloqueo() == 0) {
                        if ($intCodigo != '') {
                            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($intCodigo);
                            if ($arEmpleado) {
                                if ($puestoRequiereArma && $arEmpleado->getRestriccionArma()) {
                                    Mensajes::error("El empleado {$arEmpleado->getCodigoRecursoPk()} no es apto para portar arma.");
                                    $error = true;
                                } else {
                                    $arProgramacion->setRecursoRel($arEmpleado);
                                }
                                $respuestaValidarRestriccionCliente = $em->getRepository(RhuEmpleado::class)->validarClienteRecurso($arEmpleado->getCodigoRecursoPk(), $arPedidoDetalle);
                                if ($respuestaValidarRestriccionCliente != "") {
                                    Mensajes::error($respuestaValidarRestriccionCliente);
                                    $error = true;
                                }
                            }

                            // validar vencimiento de cursos de vigilancia
                            // aqui se consulta que cursos tiene el empleado
                            if ($arConfiguracion->getValidarVencimientoCurso()) {
                                $fechaProgramacion = $arProgramacion->getProgramacionRel()->getFecha();
                                $fechaDesdeVenceCurso = $fechaProgramacion->format("Y/m/1");
                                $dqlCursos = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->listaDql($arEmpleado->getCodigoEmpleadoFk(), "", "", "", $fechaDesde = "", $fechaHasta = "", $fechaDesdeVenceCurso, "", "", "", "", ""));
                                $arrCursos = $dqlCursos->getResult();
                                if ($arrCursos != null) {
                                    $arCurso = $arrCursos[0];
                                    $mesVenceCurso = $arCurso->getFechaVenceCurso()->format('n');
                                    $anioVenceCurso = $arCurso->getFechaVenceCurso()->format('Y');
                                    $intDiaVenceCurso = $arCurso->getFechaVenceCurso()->format('j');
                                    if (($mesVenceCurso >= $arPedidoDetalle->getMes() && $anioVenceCurso == $arPedidoDetalle->getAnio()) || $anioVenceCurso >= $arPedidoDetalle->getAnio()) {
                                        $cursoActivo = true;
                                    }
                                }
                            }
                        } else {
                            $arProgramacion->setRecursoRel(NULL);
                        }
                    }
                    $cambio = false;
                    for ($i = 1; $i <= 31; $i++) {
                        $indice = 'TxtDia' . ($i < 10 ? '0' . $i : $i) . 'D' . $intCodigo;
                        if (isset($arrControles[$indice]) && $arrControles[$indice] != "") {
                            //validacion de curso de vigilancia
                            // aqui se valida segun el dia de vencimiento de curso de vigilancia
                            if ($arConfiguracion->getValidarVencimientoCurso()) {
                                if ($cursoActivo == true) {
                                    if ($i > $intDiaVenceCurso && $mesVenceCurso == $arPedidoDetalle->getMes()) {
                                        Mensajes::error("El curso de vigilancia vence el día" . $intDiaVenceCurso . ", por ende el recurso {$arEmpleado->getCodigoRecursoPk()} no puede ser programado");
                                        $error = true;
                                        break;
                                    }
                                } else {
                                    Mensajes::error("El recurso {$arEmpleado->getCodigoRecursoPk()} no tiene curso de vigilancia vigente");
                                    $error = true;
                                    break;
                                }
                            }
                            if (call_user_func_array([$arProgramacion, "getDia{$i}"], []) != $indice) {
                                $cambio = true;
                            }
                            call_user_func_array([$arProgramacion, "setDia{$i}"], [$arrControles[$indice]]);
                        } else {
                            call_user_func_array([$arProgramacion, "setDia{$i}"], [null]);
                        }
                    }
                    if ($cambio) {
                        $arProgramacion->setActualizadoPor($this->getUser()->getNombreCorto());
                        $arProgramacion->setFechaActualizacion(new \DateTime(date("Y-m-d H:i:s")));
                    }
                    $arProgramacion->setHorasDiurnas($validar['horasDiurnas']);
                    $arProgramacion->setHorasNocturnas($validar['horasNocturnas']);
                    $arProgramacion->setHoras($validar['horasDiurnas'] + $validar['horasNocturnas']);
                    $em->persist($arProgramacion);
                } else {
                    $error = true;
                    Mensajes::error($validar['mensaje']);
                }
                if ($error) {
                    break;
                }
            }
//            $horasProgramadasDiurnasPedidoTotales = ($arPedidoDetalle->getHorasDiurnasProgramadas() - $arrTotalHoras['horasDiurnasProgramacion']) + $arrTotalHoras['horasDiurnas'];
//            $horasProgramadasNocturnasPedidoTotales = ($arPedidoDetalle->getHorasNocturnasProgramadas() - $arrTotalHoras['horasNocturnasProgramacion']) + $arrTotalHoras['horasNocturnas'];
            $arPedidoDetalle->setHorasDiurnasProgramadas($arrTotalHoras['horasDiurnas']);
            $arPedidoDetalle->setHorasNocturnasProgramadas($arrTotalHoras['horasNocturnas']);
            $arPedidoDetalle->setHorasProgramadas($arrTotalHoras['horasDiurnas'] + $arrTotalHoras['horasNocturnas']);
            $em->persist($arPedidoDetalle);
        }
        return $error;
    }

    private function horasControles($arrControles)
    {
        $em = $this->getDoctrine()->getManager();
        $arrDetalle = array('validado' => true, 'horasDiurnas' => 0, 'horasNocturnas' => 0, 'horasDiurnasProgramacion' => 0, 'horasNocturnasProgramacion' => 0, 'mensaje' => '');
        $horasDiurnas = 0;
        $horasNocturnas = 0;
        $horasDiurnasProgramacion = 0;
        $horasNocturnasProgramacion = 0;

        foreach ($arrControles['LblCodigo'] as $codigo) {
            for ($i = 1; $i <= 31; $i++) {
                $dia = $i;
                if (strlen($dia) < 2) {
                    $dia = "0" . $i;
                }
                $indice = 'TxtDia' . $dia . 'D' . $codigo;
                if (isset($arrControles[$indice]) && $arrControles[$indice] != '') {
                    $arrTurno = $this->validarTurno($arrControles['TxtDia' . $dia . 'D' . $codigo]);
                    if ($arrTurno['errado'] == true) {
                        $arrDetalle['validado'] = false;
                        $arrDetalle['mensaje'] = "Turno " . $arrControles['TxtDia' . $dia . 'D' . $codigo] . " no esta creado";
                        break;
                    }
                    $horasDiurnas += $arrTurno['horasDiurnas'];
                    $horasNocturnas += $arrTurno['horasNocturnas'];
                }
            }
            $arProgramacion = $em->getRepository(TurProgramacion::class)->find($codigo);
            if($arProgramacion){
                $horasDiurnasProgramacion += $arProgramacion->getHorasDiurnas();
                $horasNocturnasProgramacion += $arProgramacion->getHorasNocturnas();
            }
        }
        $arrDetalle['horasDiurnas'] = $horasDiurnas;
        $arrDetalle['horasNocturnas'] = $horasNocturnas;
        $arrDetalle['horasDiurnasProgramacion'] = $horasDiurnasProgramacion;
        $arrDetalle['horasNocturnasProgramacion'] = $horasNocturnasProgramacion;
        return $arrDetalle;
    }
}
