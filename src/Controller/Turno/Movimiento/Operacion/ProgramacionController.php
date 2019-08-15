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
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurTurno;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        $arPedidos = $paginator->paginate($em->getRepository(TurPedido::class)->lista(), $request->query->getInt('page', 1), 30);
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

//        dd($arPedidoDetalles);
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
    public function programacion_masiva(Request $request, $codigoPedidoDetalle, $codigoEmpleado)
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
        }
        $arProgramaciones = $em->getRepository(TurProgramacion::class)->periodoDias($arPedidoDetalle->getAnio() , $arPedidoDetalle->getMes(), $codigoEmpleado);
        return $this->render('turno/movimiento/operacion/programacion/programacionRecurso.html.twig', [
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

}
