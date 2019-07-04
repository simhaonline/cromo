<?php

namespace App\Controller\Turno\Movimiento\Operacion;

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
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
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
        $form = $this->createFormBuilder()

            ->getForm();

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            /*if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurPedidoDetalle::class)->eliminar($arPedido, $arrDetallesSeleccionados);
                $em->getRepository(TurPedido::class)->liquidar($arPedido);
            }*/
            return $this->redirect($this->generateUrl('turno_movimiento_operacion_programacion_detalle', ['id' => $id]));
        }
        $arPedidoDetalles = $em->getRepository(TurProgramacion::class)->detalleProgramacion($id);
        return $this->render('turno/movimiento/operacion/programacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arPedidoDetalles' => $arPedidoDetalles,
            'arPedido' => $arPedido
        ]);
    }

    /**
     *@Route("/turno/movimiento/operacion/programacion/masiva/{anio}/{mes}/{codigoPedido}", name="turno_movimiento_operacion_programacion_masiva")
     */
    public function programacion_masiva(Request $request, $anio, $mes, $codigoPedido)
    {
        $em = $this->getDoctrine()->getManager();
        $strAnioMes = $anio . "/" . $mes;
        $arrDiaSemana = array();
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = ['dia' => $i, 'diaSemana'=> $diaSemana];
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
            }
        }
        $arTurProgramaciones = $em->getRepository(TurProgramacion::class)->findBy(['codigoPedidoFk'=>$codigoPedido]);
        return $this->render('turno/movimiento/operacion/programacion/programacionMasiva.html.twig', [
            'arrDiaSemana' => $arrDiaSemana,
            'arTurProgramaciones'=>$arTurProgramaciones,
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

    public function actualizarDetalle($arrControles)
    {
        $em = $this->getDoctrine()->getManager();

    }

}
