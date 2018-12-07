<?php

namespace App\Controller\Transporte\Administracion\Poseedor;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TtePoseedor;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PoseedorController extends BaseController
{
    protected $clase= TtePoseedor::class;
    protected $claseNombre = "TtePoseedor";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Transporte";
    protected $nombre = "Poseedor";
    /**
     * @Route("/transporte/administracion/poseedor/lista", name="transporte_administracion_poseedor_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Poseedor");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(TteRelacionCaja::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_administracion_poseedor_lista'));
            }
        }
        return $this->render('transporte/administracion/poseedor/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/transporte/administracion/poseedor/nuevo/{id}", name="transporte_administracion_transporte_poseedor_nuevo")
     */
    public function nuevo()
    {
        return $this->redirect($this->generateUrl('transporte_administracion_poseedor_lista'));
    }

    /**
     * @Route("/transporte/administracion/poseedor/detalle/{id}", name="transporte_administracion_transporte_poseedor_detalle")
     */
    public function detalle(){
            return $this->redirect($this->generateUrl('transporte_administracion_poseedor_lista'));
    }
}
