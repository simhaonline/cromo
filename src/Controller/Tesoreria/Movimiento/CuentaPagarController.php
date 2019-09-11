<?php

namespace App\Controller\Tesoreria\Movimiento;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuentaPagarController extends ControllerListenerGeneral
{
    protected $clase = TesCuentaPagar::class;
    protected $claseNombre = "TesCuentaPagar";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "CuentaPagar";
    protected $nombre = "CuentaPagar";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/movimiento/cuenta/pagar/lista", name="tesoreria_movimiento_cuentapagar_cuentapagar_lista")
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
                General::get()->setExportar($em->getRepository(TesCuentaPagar::class)->lista()->getQuery()->getResult(), "Cuentas pagar");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TesCuentaPagar::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_cuentapagar_cuentapagar_lista'));
            }
        }
        return $this->render('tesoreria/movimiento/cuentapagar/cuentapagar/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/compra/movimiento/cuenta/pagar/lista", name="tesoreria_movimiento_cuentapagar_cuentapagar_nuevo")
     */
    public function nuevo(Request $request){
        return $this->redirect($this->generateUrl('tesoreria_movimiento_cuentapagar_cuentapagar_nuevo'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/compra/movimiento/cuenta/pagar/detalle/{id}", name="tesoreria_movimiento_cuentapagar_cuentapagar_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($id);
        $form = Estandares::botonera($arCuentaPagar->getEstadoAutorizado(), $arCuentaPagar->getEstadoAprobado(), $arCuentaPagar->getEstadoAnulado());

        $arrBtnVerificar = ['label' => 'Verificar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arCuentaPagar->getEstadoVerificado()) {
            $arrBtnVerificar['disabled'] = true;
        }
        $form
            ->add('btnVerificar', SubmitType::class, $arrBtnVerificar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnVerificar')->isClicked()) {
                $em->getRepository(TesCuentaPagar::class)->verificar($arCuentaPagar);
            }
            return $this->redirect($this->generateUrl('tesoreria_movimiento_cuentapagar_cuentapagar_detalle', ['id' => $id]));
        }

        return $this->render('inventario/administracion/general/contacto/detalle.html.twig', [
            'arCuentaPagar' => $arCuentaPagar,
            'clase' => array('clase' => 'TesCuentaPagar', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }
}
