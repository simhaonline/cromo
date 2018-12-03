<?php

namespace App\Controller\Compra\Movimiento;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Compra\ComCuentaPagar;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuentaPagarController extends ControllerListenerGeneral
{
    protected $clase = ComCuentaPagar::class;
    protected $claseNombre = "ComCuentaPagar";
    protected $modulo = "Compra";
    protected $funcion = "Movimiento";
    protected $grupo = "CuentaPagar";
    protected $nombre = "CuentaPagar";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/compra/movimiento/cuenta/pagar/lista", name="compra_movimiento_cuentapagar_cuentapagar_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Recibos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(ComCuentaPagar::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        }
        return $this->render('compra/movimiento/cuentaPagar/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/compra/movimiento/cuenta/pagar/lista", name="compra_movimiento_cuentapagar_cuentapagar_nuevo")
     */
    public function nuevo(Request $request){
        return $this->redirect($this->generateUrl('compra_movimiento_cuentapagar_cuentapagar_lista'));
    }
}
