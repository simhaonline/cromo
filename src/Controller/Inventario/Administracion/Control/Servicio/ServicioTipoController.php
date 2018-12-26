<?php

namespace App\Controller\Inventario\Administracion\Control\Servicio;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\GeneralEntityListener;
use App\Entity\Inventario\InvServicioTipo;
use App\Form\Type\Inventario\ServicioTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServicioTipoController extends ControllerListenerGeneral
{
    protected $clase= InvServicioTipo::class;
    protected $claseFormulario = ServicioTipoType::class;
    protected $claseNombre = "InvServicioTipo";
    protected $modulo   = "Inventario";
    protected $funcion  = "Administracion";
    protected $grupo    = "Control";
    protected $nombre   = "ServicioTipo";
    /**
     * @Route("/inventario/administracion/control/serviciotipo/lista", name="inventario_administracion_control_serviciotipo_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Servicio tipo");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvServicioTipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_administracion_control_serviciotipo_lista'));
            }
        }
        return $this->render('inventario/administracion/control/serviciotipo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/inventario/administracion/control/servicio/nuevo/{id}", name="inventario_administracion_control_serviciotipo_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        if ($id == "0" ) {
            $arServicioTipo = new InvServicioTipo();
        } else {
            $arServicioTipo = $em->getRepository(InvServicioTipo::class)->find($id);
        }
        $form = $this->createForm(ServicioTipoType::class, $arServicioTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($em->getRepository('App:Inventario\InvServicioTipo')->find($form->get('codigoServicioTipoPk')->getData()) && $id==0){
                    Mensajes::error("Ya existe un Servicio tipo con ese mismo nombre");
                }
                else{

                $arServicioTipo->setCodigoServicioTipoPk($form->get('codigoServicioTipoPk')->getData());
                $arServicioTipo->setNombre($form->get('nombre')->getData());
                $em->persist($arServicioTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_administracion_control_serviciotipo_lista'));
                }
            }
        }
        return $this->render('inventario/administracion/control/serviciotipo/nuevo.html.twig', [
            'arServicio' => $arServicioTipo,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/inventario/administracion/control/servicio/detalle/{id}", name="inventario_administracion_control_serviciotipo_detalle")
     */
    public function detalle(Request $request, $id){
        return $this->redirect($this->generateUrl('inventario_administracion_control_serviciotipo_lista'));

    }

}
