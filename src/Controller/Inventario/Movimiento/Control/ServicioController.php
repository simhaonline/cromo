<?php

namespace App\Controller\Inventario\Movimiento\Control;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvServicio;
use App\Form\Type\Inventario\ServicioType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServicioController extends ControllerListenerGeneral
{
    protected $clase= InvServicio::class;
    protected $claseFormulario = ServicioType::class;
    protected $claseNombre = "InvServicio";
    protected $modulo   = "Inventario";
    protected $funcion  = "Movimiento";
    protected $grupo    = "Control";
    protected $nombre   = "Servicio";
    /**
     * @Route("/inventario/movimiento/control/servicio/lista", name="inventario_movimiento_control_servicio_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Servicio");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvServicio')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_control_servicio_lista'));
            }
        }
        return $this->render('inventario/movimiento/control/servicio/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/inventario/movimiento/servicio/control/nuevo/{id}", name="inventario_movimiento_control_servicio_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $arServicio = new InvServicio();
            $arServicio->setFecha(new \DateTime('now'));
        } else {
            $arServicio = $em->getRepository(InvServicio::class)->find($id);
        }
        $form = $this->createForm(ServicioType::class, $arServicio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arServicio->setComentario($form->get('comentario')->getData());
                $em->persist($arServicio);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_movimiento_control_servicio_lista'));
            }
        }
        return $this->render('inventario/movimiento/control/servicio/nuevo.html.twig', [
            'arServicio' => $arServicio,
            'form' => $form->createView()]);
    }


    /**
     * @param Request $request
     * @param $id
     * @Route("/inventario/movimiento/control/servicio/detalle/{id}", name="inventario_movimiento_control_servicio_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arServicio = $em->getRepository(InvServicio::class)->find($id);
        $form = Estandares::botonera($arServicio->getEstadoAutorizado(),$arServicio->getEstadoAprobado(),$arServicio->getEstadoAnulado());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new InvServicio();
//                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->autorizar($arServicio);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->desautorizar($arServicio);
            }
            if ($form->get('btnAprobar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->aprobar($arServicio);
            }
            if ($form->get('btnAnular')->isClicked()) {
//                $em->getRepository(InvServicio::class)->anular($arServicio);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_control_servicio_detalle',['id' => $arServicio->getCodigoServicioPk()]));
        }

        return $this->render('inventario/movimiento/control/servicio/detalle.html.twig', [
            'arServicio' => $arServicio,
            'form' => $form->createView()]);

    }
}
