<?php

namespace App\Controller\Cartera\Movimiento\CuentaCobrar;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Compra\CuentaPagarType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CuentaCobrarController extends ControllerListenerGeneral
{
    protected $clase = CarCuentaCobrar::class;
    protected $claseNombre = "CarCuentaCobrar";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Cartera";
    protected $nombre = "CuentaCobrar";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/cartera/cuentacobrar/lista", name="cartera_movimiento_cartera_cuentacobrar_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "CuentasCobrar");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarCuentaCobrar::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_cartera_cuentacobrar_lista'));
            }
        }
        return $this->render('cartera/movimiento/cuentaCobrar/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/cartera/cuentacobrar/nuevo/{id}", name="cartera_movimiento_cartera_cuentacobrar_nuevo")
     */
    public function nuevo()
    {
        return $this->redirect($this->generateUrl('cartera_movimiento_cartera_cuentacobrar_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/cartera/cuentacobrar/detalle/{id}", name="cartera_movimiento_cartera_cuentacobrar_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($id);
        $form = Estandares::botonera(false,false,false);
        $form->handleRequest($request);
        return $this->render('cartera/movimiento/cuentaCobrar/detalle.html.twig', [
            'arCuentaCobrar' => $arCuentaCobrar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/cartera/cuentacobrar/referencia/{id}", name="cartera_movimiento_cartera_cuentacobrar_referencia")
     */
    public function referencia($id){
        $em = $this->getDoctrine()->getManager();
        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($id);
        $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(['codigoCuentaCobrarFk' => $id]);
        return $this->render('cartera/movimiento/cuentaCobrar/referencia.html.twig',[
            'arCuentaCobrar' => $arCuentaCobrar,
            'arReciboDetalles' => $arReciboDetalles
        ]);
    }
}