<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Seleccion;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Form\Type\RecursoHumano\SeleccionType;
use App\General\General;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SeleccionController extends ControllerListenerGeneral
{
    protected $clase = RhuSeleccion::class;
    protected $claseFormulario = SeleccionType::class;
    protected $claseNombre = "RhuSeleccion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Seleccion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/seleccion/lista", name="recursohumano_movimiento_seleccion_seleccion_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Selecciones");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuSeleccion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_seleccion_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }


    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/nuevo/{id}", name="recursohumano_movimiento_seleccion_seleccion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = new RhuSeleccion();
        if ($id != 0) {
            $arSeleccion = $em->getRepository('App:RecursoHumano\RhuSeleccion')->find($id);
            if (!$arSeleccion) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'seleccion']));
            }
        }
        $form = $this->createForm(SeleccionType::class, $arSeleccion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSeleccion->setFecha(new \DateTime('now'));
                $em->persist($arSeleccion);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano','entidad' => 'seleccion']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSeleccion);
                $em->flush($arSeleccion);
                return $this->redirect($this->generateUrl('recursoHumano_movimiento_seleccion_seleccion_nuevo', ['codigoSeleccion' => 0]));
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/nuevo.html.twig', [
            'form' => $form->createView(), 'arSeleccion' => $arSeleccion
        ]);
    }
}

