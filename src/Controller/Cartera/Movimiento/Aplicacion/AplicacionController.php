<?php

namespace App\Controller\Cartera\Movimiento\Aplicacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarAnticipo;
use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Compra\CuentaPagarType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AplicacionController extends ControllerListenerGeneral
{
    protected $clase = CarAplicacion::class;
    protected $claseNombre = "CarAplicacion";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Operacion";
    protected $nombre = "Aplicacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/operacion/aplicacion/lista", name="cartera_movimiento_operacion_aplicacion_lista")
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
                Mensajes::info("Las aplicaciones no se pueden eliminar solo anular");
//                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(CarAplicacion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_operacion_aplicacion_lista'));
            }
        }
        return $this->render('cartera/movimiento/operacion/aplicacion/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/operacion/aplicacion/nuevo/{id}", name="cartera_movimiento_operacion_aplicacion_nuevo")
     */
    public function nuevo()
    {
        return $this->redirect($this->generateUrl('cartera_movimiento_aplicacion_aplicacion_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/operacion/aplicacion/detalle/{id}", name="cartera_movimiento_operacion_aplicacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAplicacion = $em->getRepository(CarAplicacion::class)->find($id);
        $form = Estandares::botonera($arAplicacion->getEstadoAutorizado(),$arAplicacion->getEstadoAprobado(),$arAplicacion->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(CarAplicacion::class)->anular($arAplicacion);
                return $this->redirect($this->generateUrl('cartera_movimiento_operacion_aplicacion_detalle', ['id' => $id]));
            }
        }
        return $this->render('cartera/movimiento/operacion/aplicacion/detalle.html.twig', [
            'arAplicacion' => $arAplicacion,
            'form' => $form->createView()
        ]);
    }

}