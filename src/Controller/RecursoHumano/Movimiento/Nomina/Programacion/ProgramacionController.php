<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Programacion;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Form\Type\RecursoHumano\ProgramacionType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProgramacionController extends BaseController
{
    protected $clase = RhuProgramacion::class;
    protected $claseFormulario = ProgramacionType::class;
    protected $claseNombre = "RhuProgramacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Programacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/programacion/lista", name="recursohumano_movimiento_nomina_programacion_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $em->getRepository(RhuProgramacion::class)->eliminar($arrSeleccionados);
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/programacion/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/programacion/nuevo/{id}", name="recursohumano_movimiento_nomina_programacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new RhuProgramacion();
        if ($id != 0) {
            $arProgramacion = $em->getRepository($this->clase)->find($id);
            if (!$arProgramacion) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_empleado_lista'));
            }
        } else {
            $arProgramacion->setFechaDesde(new \DateTime('now'));
            $arProgramacion->setFechaHasta(new \DateTime('now'));
            $arProgramacion->setFechaHastaPeriodo(new \DateTime('now'));
        }
        $form = $this->createForm(ProgramacionType::class, $arProgramacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arProgramacion->setDias(($arProgramacion->getFechaDesde()->diff($arProgramacion->getFechaHasta()))->days+1);
                $em->persist($arProgramacion);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $arProgramacion->getCodigoProgramacionPk()]));
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/programacion/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("recursohumano/movimiento/nomina/programacion/detalle/{id}", name="recursohumano_movimiento_nomina_programacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = $this->clase;
        if ($id != 0) {
            $arProgramacion = $em->getRepository($this->clase)->find($id);
            if (!$arProgramacion) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_lista'));
            }
        }
        $arrBtnCargarContratos = ['attr' => ['class' => 'btn btn-sm btn-default'],'label' => 'Cargar contratos'];
        $arrBtnEliminarTodos = ['attr' => ['class' => 'btn btn-sm btn-danger'],'label' => 'Eliminar todos'];
        $arrBtnEliminar = ['attr' => ['class' => 'btn btn-sm btn-danger'],'label' => 'Eliminar'];
        $form = Estandares::botonera($arProgramacion->getEstadoAutorizado(), $arProgramacion->getEstadoAprobado(),$arProgramacion->getEstadoAnulado());
        $form->add('btnCargarContratos',SubmitType::class, $arrBtnCargarContratos);
        $form->add('btnEliminar',SubmitType::class, $arrBtnEliminar);
        $form->add('btnEliminarTodos',SubmitType::class, $arrBtnEliminarTodos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnCargarContratos')->isClicked()) {
                $em->getRepository(RhuProgramacion::class)->cargarContratos($arProgramacion);
            }
            if($form->get('btnEliminar')->isClicked()){
                $em->getRepository(RhuProgramacionDetalle::class)->eliminar($arrSeleccionados);
                $em->getRepository(RhuProgramacion::class)->setCantidadRegistros($arProgramacion);
            }
            if($form->get('btnAutorizar')->isClicked()){
                set_time_limit(0);
                ini_set("memory_limit", -1);

                    $strResultado = $em->getRepository(RhuProgramacion::class)->autorizar($arProgramacion);
                    if ($strResultado == "") {
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_detalle', ['id' => $id]));
                    } else {
                        Mensajes::error($strResultado);
                    }

            }
            if($form->get('btnEliminarTodos')->isClicked()){
                $em->getRepository(RhuProgramacionDetalle::class)->eliminarTodoDetalles($arProgramacion);
                $em->getRepository(RhuProgramacion::class)->setCantidadRegistros($arProgramacion);
            }
        }
        $arProgramacion = $em->getRepository($this->clase)->find($id);
        $arProgramacionDetalles = $em->getRepository(RhuProgramacionDetalle::class)->findBy(['codigoProgramacionFk' => $arProgramacion->getCodigoProgramacionPk()]);
        return $this->render('recursoHumano/movimiento/nomina/programacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arProgramacion' => $arProgramacion,
            'arProgramacionDetalles' => $arProgramacionDetalles
        ]);
    }
}

