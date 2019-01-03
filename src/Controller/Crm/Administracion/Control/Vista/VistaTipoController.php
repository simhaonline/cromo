<?php

namespace App\Controller\Crm\Administracion\Control\Vista;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmVistaTipo;
use App\Form\Type\Crm\VistaTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VistaTipoController extends ControllerListenerGeneral
{
    protected $clase= CrmVistaTipo::class;
    protected $claseFormulario = VistaTipoType::class;
    protected $claseNombre = "CrmVistaTipo";
    protected $modulo   = "Crm";
    protected $funcion  = "Administracion";
    protected $grupo    = "Control";
    protected $nombre   = "VistaTipo";
    /**
     * @Route("/crm/administracion/control/vistatipo/lista", name="crm_administracion_control_vistatipo_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Vista tipo");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Crm\CrmVistaTipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('crm_administracion_control_vistatipo_lista'));
            }
        }
        return $this->render('crm/administracion/control/vistatipo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/crm/administracion/control/vistatipo/nuevo/{id}", name="crm_administracion_control_vistatipo_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        if ($id == "0" ) {
            $arVistaTipo = new CrmVistaTipo();
        } else {
            $arVistaTipo = $em->getRepository(CrmVistaTipo::class)->find($id);
        }
        $form = $this->createForm(VistaTipoType::class, $arVistaTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($em->getRepository('App:Crm\CrmVistaTipo')->find($form->get('codigoVistaTipoPk')->getData()) && $id=="0"){
                    Mensajes::error("Ya existe una vista tipo con ese mismo nombre");
                }
                else{
                    if($em->getRepository('App:Crm\CrmVista')->findBy(['codigoVistaTipoFk'=>$id])){
                        Mensajes::error("No se puede actualizar el codigo, la vista tipo esta siendo utilizada");
                    }
                    else {
                        $arVistaTipo->setCodigoVistaTipoPk($form->get('codigoVistaTipoPk')->getData());
                        $arVistaTipo->setNombre($form->get('nombre')->getData());
                        $em->persist($arVistaTipo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('crm_administracion_control_vistatipo_lista'));
                    }
                }
            }
        }
        return $this->render('crm/administracion/control/vistatipo/nuevo.html.twig', [
            'arServicio' => $arVistaTipo,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/crm/administracion/control/vistatipo/detalle/{id}", name="crm_administracion_control_vistatipo_detalle")
     */
    public function detalle(Request $request, $id){
        return $this->redirect($this->generateUrl('crm_administracion_control_vistatipo_lista'));

    }
}
