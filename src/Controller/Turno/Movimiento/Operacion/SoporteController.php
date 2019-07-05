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
use App\Entity\Turno\TurSoporte;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Form\Type\Turno\SoporteType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SoporteController extends ControllerListenerGeneral
{
    protected $clase = TurSoporte::class;
    protected $claseNombre = "TurSoporte";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Operacion";
    protected $nombre = "Soporte";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/operacion/soporte/lista", name="turno_movimiento_operacion_soporte_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Soporte");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurSoporte::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_lista'));
            }
        }
        return $this->render('turno/movimiento/operacion/soporte/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/operacion/soporte/nuevo/{id}", name="turno_movimiento_operacion_soporte_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSoporte = new TurSoporte();
        if ($id != '0') {
            $arSoporte = $em->getRepository(TurSoporte::class)->find($id);
            if (!$arSoporte) {
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_lista'));
            }
        }
        $form = $this->createForm(SoporteType::class, $arSoporte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arSoporte->setUsuario($this->getUser()->getUserName());
                }
                $em->persist($arSoporte);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $arSoporte->getCodigoSoportePk()]));
            }
        }
        return $this->render('turno/movimiento/operacion/soporte/nuevo.html.twig', [
            'arSoporte' => $arSoporte,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/turno/movimiento/operacion/soporte/detalle/{id}", name="turno_movimiento_operacion_soporte_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arSoporte = $em->getRepository(TurSoporte::class)->find($id);
        $form = Estandares::botonera($arSoporte->getEstadoAutorizado(), $arSoporte->getEstadoAprobado(), $arSoporte->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arSoporte->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobado['disabled'] = false;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arSoporte->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $id]));
        }
        return $this->render('turno/movimiento/operacion/soporte/detalle.html.twig', [
            'form' => $form->createView(),
            'arSoporte' => $arSoporte
        ]);
    }


}
