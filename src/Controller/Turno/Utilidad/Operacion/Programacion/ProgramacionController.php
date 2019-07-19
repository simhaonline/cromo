<?php

namespace App\Controller\Turno\Utilidad\Operacion\Programacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurSecuencia;
use App\Entity\Turno\TurSimulacion;
use App\Entity\Turno\TurTurno;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProgramacionController extends Controller
{

    /**
     * @Route("turno/utilidad/operacion/programacion", name="turno_utilidad_operacion_programacion")
     */
    public function detalle(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion'));
        }
        $arPedidoDetalles = $em->getRepository(TurPedidoDetalle::class)->pendienteProgramar();
        return $this->render('turno/utilidad/operacion/programacion/lista.html.twig', [
            'arPedidoDetalles' => $arPedidoDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("turno/utilidad/operacion/programacion/detalle/{id}", name="turno_utilidad_operacion_programacion_detalle")
     */
    public function prototipo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPedidoDetalle = $em->getRepository(TurPedidoDetalle::class)->find($id);
        $fechaProgramacion = FuncionesController::primerDia(new \DateTime('now'));
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnSimular = ['label' => 'Simular', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnSimularLimpiar = ['label' => 'Limpiar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $form = $this->createFormBuilder()
            ->add('fechaSimulacion', DateType::class, array('data' => $fechaProgramacion, 'format' => 'yyyyMMdd'))
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnSimular', SubmitType::class, $arrBtnSimular)
            ->add('btnSimularLimpiar', SubmitType::class, $arrBtnSimularLimpiar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurPrototipo::class)->actualizar($arrControles);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $this->get("UtilidadesModelo")->eliminar(TurPrototipo::class, $arrDetallesSeleccionados);
            }
            if($form->get('btnSimular')->isClicked()) {
                $fechaProgramacion = $form->get('fechaSimulacion')->getData();
                $em->getRepository(TurPrototipo::class)->generarSimulacion($arPedidoDetalle, $fechaProgramacion);
            }
            if($form->get('btnSimularLimpiar')->isClicked()) {
                $em->getRepository(TurSimulacion::class)->limpiar($id);
            }
            return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion_detalle', ['id' => $id]));
        }
        $arPrototipos = $em->getRepository(TurPrototipo::class)->listaProgramar($arPedidoDetalle->getCodigoContratoDetalleFk());
        $arSimulaciones = $em->getRepository(TurSimulacion::class)->listaProgramar($id);
        $arSecuencias = $em->getRepository(TurSecuencia::class)->findAll();

        $fechaProgramacion = new \DateTime('now');
        $strAnioMes = $fechaProgramacion->format('Y/m');
        $arrDiaSemana = array();
        $arrFestivos = $em->getRepository(TurFestivo::class)->fechaArray($fechaProgramacion->format("Y-m-01"), $fechaProgramacion->format("Y-m-t"));
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $boolFestivo = 0;
            $fechaRecorrida = $fechaProgramacion->format("Y-m-" . ($i < 10 ? '0' . $i : $i));
            if ($diaSemana == 'd' || in_array($fechaRecorrida, $arrFestivos)) {
                $boolFestivo = true;
            }
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana, 'festivo' => $boolFestivo);
        }
        return $this->render('turno/utilidad/operacion/programacion/prototipo.html.twig', [
            'arPrototipos' => $arPrototipos,
            'arSimulaciones' => $arSimulaciones,
            'arPedidoDetalle' => $arPedidoDetalle,
            'arSecuencias' => $arSecuencias,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()
        ]);
    }
    private function devuelveDiaSemanaEspaniol($dateFecha)
    {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }
}
