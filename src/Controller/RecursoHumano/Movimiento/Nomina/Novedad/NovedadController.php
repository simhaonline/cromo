<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Novedad;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuNovedad;
use App\Form\Type\RecursoHumano\NovedadType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NovedadController extends BaseController
{
    protected $clase = RhuNovedad::class;
    protected $claseFormulario = NovedadType::class;
    protected $claseNombre = "RhuNovedad";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Novedad";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/novedad/lista", name="recursohumano_movimiento_nomina_novedad_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Novedades");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuNovedad::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_novedad_lista'));
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/novedad/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/novedad/nuevo/{id}", name="recursohumano_movimiento_nomina_novedad_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = new RhuNovedad();
        if ($id != 0) {
            $arNovedad = $em->getRepository(RhuNovedad::class)->find($id);
        } else {
            $arNovedad->setFechaDesde(new \DateTime('now'));
            $arNovedad->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm($this->claseFormulario, $arNovedad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arNovedad->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                    } elseif ($arEmpleado->getCodigoContratoUltimoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoUltimoFk());
                    } else {
                        $arContrato = null;
                    }
                    if ($arContrato) {
                        $arNovedad->setContratoRel($arContrato);
                        $arNovedad->setEmpleadoRel($arEmpleado);
                        $arNovedad->setGrupoRel($arContrato->getGrupoRel());
                        $em->persist($arNovedad);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_novedad_detalle', ['id' => $arNovedad->getCodigoNovedadPk()]));
                    } else {
                        Mensajes::error('No se ha encontrado un contrato para el empleado seleccionado');
                    }
                } else {
                    Mensajes::error('No se ha encontrado un empleado con el codigo ingresado');
                }
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/novedad/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/novedad/detalle/{id}", name="recursohumano_movimiento_nomina_novedad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arNovedad->getEstadoAutorizado(), $arNovedad->getEstadoAprobado(), $arNovedad->getEstadoAnulado());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('btnAutorizar')->isClicked()){

            }
            if($form->get('btnDesautorizar')->isClicked()){

            }
            if($form->get('btnImprimir')->isClicked()){

            }
            if($form->get('btnAprobar')->isClicked()){

            }
            if($form->get('btnAnular')->isClicked()){

            }
        }
        return $this->render('recursoHumano/movimiento/nomina/novedad/detalle.html.twig', [
            'arNovedad' => $arNovedad,
            'form' => $form->createView()
        ]);
    }
}