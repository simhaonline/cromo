<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Reclamo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuReclamo;
use App\Form\Type\RecursoHumano\ReclamoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReclamoController extends ControllerListenerGeneral
{
    protected $class = RhuReclamo::class;
    protected $claseNombre = "RhuReclamo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Reclamo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/reclamo/lista", name="recursohumano_movimiento_nomina_reclamo_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Reclamos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuReclamo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_reclamo_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/reclamo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/reclamo/nuevo/{id}", name="recursohumano_movimiento_nomina_reclamo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arReclamo = new RhuReclamo();
        if ($id != 0) {
            $arReclamo = $em->getRepository(RhuReclamo::class)->find($id);
            if (!$arReclamo) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_reclamo_lista'));
            }
        } else {
            $arReclamo->setFecha(new \DateTime('now'));
            $arReclamo->setUsuario($this->getUser()->getUsername());
        }

        $form = $this->createForm(ReclamoType::class, $arReclamo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($arReclamo->getCodigoEmpleadoFk() != ''){
                    $arEmpleado = $em->find(RhuEmpleado::class,$arReclamo->getCodigoEmpleadoFk());
                    if($arEmpleado){
                        $arReclamo->setEmpleadoRel($arEmpleado);
                        $em->persist($arReclamo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_reclamo_detalle'));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un empleado');
                }

            }
        }
        return $this->render('recursohumano/movimiento/nomina/reclamo/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/reclamo/detalle/{id}", name="recursohumano_movimiento_nomina_reclamo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arReclamo = $em->getRepository($this->class)->find($id);
        $form = Estandares::botonera($arReclamo->getEstadoAutorizado(),$arReclamo->getEstadoAprobado(),$arReclamo->getEstadoAnulado());
        $form->handleRequest($request);
        return $this->render('recursohumano/movimiento/nomina/reclamo/detalle.html.twig', [
            'arReclamo' => $arReclamo,
            'form' => $form->createView()
        ]);
    }
}

