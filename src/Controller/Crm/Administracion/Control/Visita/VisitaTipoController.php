<?php

namespace App\Controller\Crm\Administracion\Control\Visita;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Crm\CrmVisitaTipo;
use App\Form\Type\Crm\VisitaTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VisitaTipoController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "CrmVisitaTipo";


    protected $clase= CrmVisitaTipo::class;
    protected $claseFormulario = VisitaTipoType::class;
    protected $claseNombre = "CrmVisitaTipo";
    protected $modulo   = "Crm";
    protected $funcion  = "Administracion";
    protected $grupo    = "Control";
    protected $nombre   = "VisitaTipo";
    /**
     * @Route("/crm/administracion/control/visitatipo/lista", name="crm_administracion_control_visitatipo_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = MaestroController::botoneraLista();
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Visita tipo");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Crm\CrmVisitaTipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('crm_administracion_control_visitatipo_lista'));
            }
        }
        return $this->render('crm/administracion/control/visitatipo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/crm/administracion/control/visitatipo/nuevo/{id}", name="crm_administracion_control_visitatipo_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        if ($id == "0" ) {
            $arVisitaTipo = new CrmVisitaTipo();
        } else {
            $arVisitaTipo = $em->getRepository(CrmVisitaTipo::class)->find($id);
        }
        $form = $this->createForm(VisitaTipoType::class, $arVisitaTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($em->getRepository('App:Crm\CrmVisitaTipo')->find($form->get('codigoVisitaTipoPk')->getData()) && $id=="0"){
                    Mensajes::error("Ya existe una visita tipo con ese mismo nombre");
                }
                else{
                    if($em->getRepository('App:Crm\CrmVisita')->findBy(['codigoVisitaTipoFk'=>$id])){
                        Mensajes::error("No se puede actualizar el codigo, la visita tipo esta siendo utilizada");
                    }
                    else {
                        $arVisitaTipo->setCodigoVisitaTipoPk($form->get('codigoVisitaTipoPk')->getData());
                        $arVisitaTipo->setNombre($form->get('nombre')->getData());
                        $em->persist($arVisitaTipo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('crm_administracion_control_visitatipo_lista'));
                    }
                }
            }
        }
        return $this->render('crm/administracion/control/visitatipo/nuevo.html.twig', [
            'arServicio' => $arVisitaTipo,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/crm/administracion/control/visitatipo/detalle/{id}", name="crm_administracion_control_visitatipo_detalle")
     */
    public function detalle(Request $request, $id){
        return $this->redirect($this->generateUrl('crm_administracion_control_visitatipo_lista'));

    }
}
