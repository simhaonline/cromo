<?php

namespace App\Controller\Cartera\Movimiento\CuentaCobrar;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinTercero;
use App\Form\Type\Cartera\CuentaCobrarType;
use App\Form\Type\Financiero\AsientoType;
use App\Formato\Financiero\Asiento;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/cartera/cuentacobrar/lista", name="cartera_movimiento_cartera_cuentacobrar_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        $datos = $this->getDatosLista();
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Asientos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                $session->set($this->claseNombre . '_numero', $formFiltro->get('numero')->getData());
                $session->set($this->claseNombre . '_codigoComprobanteFk', $formFiltro->get('codigoComprobanteFk')->getData() != "" ? $formFiltro->get('codigoComprobanteFk')->getData()->getCodigoComprobantePk() : "");
                $session->set($this->claseNombre . '_filtrarFecha', $formFiltro->get('filtrarFecha')->getData());
                if ($formFiltro->get('filtrarFecha')->getData()) {
                    $session->set($this->claseNombre . '_fechaDesde', $formFiltro->get('fechaDesde')->getData()->format('Y-m-d'));
                    $session->set($this->claseNombre . '_fechaHasta', $formFiltro->get('fechaHasta')->getData()->format('Y-m-d'));
                }
                $datos = $this->getDatosLista();
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
        return $this->render('financiero/movimiento/contabilidad/asiento/detalle.html.twig', [
            'arCuentaCobrar' => $arCuentaCobrar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $id integer
     * @Route("/cartera/movimiento/cartera/cuentacobrar/referencia/{id}", name="cartera_movimiento_cartera_cuentacobrar_referencia")
     */
    public function referencia($id){

    }
}