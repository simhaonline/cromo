<?php

namespace App\Controller\Turno\Utilidad\Operacion\Programacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
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
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnSimular = ['label' => 'Simular', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-primary']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnSimular', SubmitType::class, $arrBtnSimular)
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
                $em->getRepository(TurPrototipo::class)->generarSimulacion($arPedidoDetalle->getCodigoContratoDetalleFk());
            }
            return $this->redirect($this->generateUrl('turno_utilidad_operacion_programacion_detalle', ['id' => $id]));
        }
        $arPrototipos = $em->getRepository(TurPrototipo::class)->listaProgramar($arPedidoDetalle->getCodigoContratoDetalleFk());
        $arSimulaciones = $em->getRepository(TurSimulacion::class)->listaProgramar($id);
        $arSecuencias = $em->getRepository(TurSecuencia::class)->findAll();
        return $this->render('turno/utilidad/operacion/programacion/prototipo.html.twig', [
            'arPrototipos' => $arPrototipos,
            'arSimulaciones' => $arSimulaciones,
            'arPedidoDetalle' => $arPedidoDetalle,
            'arSecuencias' => $arSecuencias,
            'form' => $form->createView()
        ]);
    }

}
