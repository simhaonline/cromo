<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Aspirante;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAspirante;
use App\Form\Type\RecursoHumano\AspiranteType;
use App\General\General;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AspiranteController extends ControllerListenerGeneral
{

    protected $clase = RhuAspirante::class;
    protected $claseFormulario = AspiranteType::class;
    protected $claseNombre = "RhuAspirante";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Aspirante";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/aspirante/lista", name="recursohumano_movimiento_seleccion_aspirante_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Aspirante");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuAspirante::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_programacion_lista'));
            }
        }
        return $this->render('recursoHumano/movimiento/seleccion/aspirante/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("rhu/mov/seleccion/aspirante/nuevo/{id}", name="recursoHumano_movimiento_seleccion_aspirante_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAspirante = new RhuAspirante();
        if ($id != 0) {
            $arAspirante = $em->getRepository('App:RecursoHumano\RhuAspirante')->find($id);
            if (!$arAspirante) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'aspirante']));
            }
        }
        $form = $this->createForm(AspiranteType::class, $arAspirante);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAspirante->setFecha(new \DateTime('now'));
                $em->persist($arAspirante);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano','entidad' => 'aspirante']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arAspirante);
                $em->flush($arAspirante);
                return $this->redirect($this->generateUrl('recursoHumano_movimiento_seleccion_aspirante_nuevo', ['codigoAspirante' => 0]));
            }
        }
        return $this->render('recursoHumano/movimiento/seleccion/aspirante/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arAspirante
        ]);
    }
}

