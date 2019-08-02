<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Liquidacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPago;
use App\Form\Type\RecursoHumano\LiquidacionType;
use App\Form\Type\RecursoHumano\PagoType;
use App\Formato\RecursoHumano\Liquidacion;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LiquidacionController extends ControllerListenerGeneral
{
    protected $clase = RhuLiquidacion::class;
    protected $claseFormulario = LiquidacionType::class;
    protected $claseNombre = "RhuLiquidacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Liquidacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/liquidacion/lista", name="recursohumano_movimiento_nomina_liquidacion_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Liquidaciones");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuLiquidacion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/liquidacion/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/liquidacion/nuevo/{id}", name="recursohumano_movimiento_nomina_liquidacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("recursohumano/movimiento/nomina/liquidacion/detalle/{id}", name="recursohumano_movimiento_nomina_liquidacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLiquidacion = $em->getRepository(RhuLiquidacion::class)->find($id);
        $form = Estandares::botonera($arLiquidacion->getEstadoAutorizado(), $arLiquidacion->getEstadoAprobado(), $arLiquidacion->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formatoLiquidacion = new Liquidacion();
                $formatoLiquidacion->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                if ($arLiquidacion->getEstadoAutorizado() == 0) {
                    $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
                    $boolValidacionFechaUltimoPago = true;
                    if ($arConfiguracion->getValidarFechaUltimoPagoLiquidacion()) {
                        if ($arLiquidacion->getContratoRel()->getFechaUltimoPago() < $arLiquidacion->getContratoRel()->getFechaHasta()) {
                            $boolValidacionFechaUltimoPago = false;
                            Mensajes::error("No se puede liquidar el contrato, la fecha del ultimo pago es inferior a la fecha de terminación del contrato.");
                        }
                    }
                    if ($boolValidacionFechaUltimoPago) {
                        $respuesta = $em->getRepository(RhuLiquidacion::class)->liquidar($id);
                        $arLiquidacion->setEstadoAutorizado(1);
                        if ($respuesta == "") {
                            $em->flush();
                        } else {
                            Mensajes::error($respuesta);
                        }
                    }
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', array('id' => $id)));
                } else {
                    Mensajes::error("No puede reliquidar una liquidacion autorizada");
                }
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuLiquidacion::class)->desautorizar($arLiquidacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', array('id' => $id)));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(RhuLiquidacion::class)->aprobar($arLiquidacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_liquidacion_detalle', array('id' => $id)));
            }
        }

        return $this->render('recursohumano/movimiento/nomina/liquidacion/detalle.html.twig', [
            'arLiquidacion' => $arLiquidacion,
            'form' => $form->createView()
        ]);
    }
}

