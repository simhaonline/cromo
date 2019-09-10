<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Pago;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Form\Type\RecursoHumano\PagoType;
use App\Formato\RecursoHumano\Pago;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PagoController extends ControllerListenerGeneral
{
    protected $clase = RhuPago::class;
    protected $claseFormulario = PagoType::class;
    protected $claseNombre = "RhuPago";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Pago";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/pago/lista", name="recursohumano_movimiento_nomina_pago_lista")
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
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuPago::class)->lista()->getQuery()->execute(), "Pagos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuPago::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_pago_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/pago/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/pago/nuevo/{id}", name="recursohumano_movimiento_nomina_pago_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        Mensajes::error('Esta funcion aun no esta disponible');
        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_pago_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/pago/detalle/{id}", name="recursohumano_movimiento_nomina_pago_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPago = $em->find(RhuPago::class, $id);
        $form = Estandares::botonera($arPago->getEstadoAutorizado(), $arPago->getEstadoAprobado(), $arPago->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnImprimir')->isClicked()){
                $objFormato = new Pago();
                $objFormato->Generar($em, $id);
            }
        }
        $arPagoDetalles = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->lista($id), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/pago/detalle.html.twig', [
            'arPago' => $arPago,
            'arPagoDetalles' => $arPagoDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/pago/programacion/{id}", name="recursohumano_movimiento_nomina_pago_programacion")
     */
    public function verProgramacion(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPago = $em->getRepository(RhuPago::class)->find($id);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(RhuProgramacionDetalle::class)->actualizar($arProgramacionDetalle, $this->getUser()->getUsername());
            }*/
        }

        $arProgramaciones = $em->getRepository(TurProgramacion::class)->programacionEmpleado($arPago->getCodigoEmpleadoFk(), $arPago->getFechaDesde()->format('Y'), $arPago->getFechaHasta()->format('n'));
        return $this->render('recursohumano/movimiento/nomina/pago/verTurnos.html.twig', [
            'arPago' => $arPago,
            'arProgramaciones' => $arProgramaciones,
            'form' => $form->createView()
        ]);
    }

}