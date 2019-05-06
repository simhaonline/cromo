<?php


namespace App\Controller\Transporte\Administracion\Guia;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteDestinatario;
use App\Entity\Transporte\TteZona;
use App\Form\Type\Transporte\DestinatarioType;
use App\Form\Type\Transporte\ZonaType;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;


class DestinatarioController extends ControllerListenerGeneral
{
    protected $clase= TteDestinatario::class;
    protected $claseFormulario = DestinatarioType::class;
    protected $claseNombre = "TteDestinatario";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Guia";
    protected $nombre = "Destinatario";

    /**
     * @Route("/transporte/administracion/destinatario/lista", name="transporte_administracion_guia_destinatario_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Poseedor");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TteDestinatario::class, $arData);
            }
        }
        return $this->render('transporte/administracion/destinatario/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/transporte/administracion/destinatario/detalle/{id}", name="transporte_administracion_guia_destinatario_detalle")
     */
    public function detalle(Request $request, $id){
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arDestinatario = $em->getRepository(TteDestinatario::class)->find($id);
        return $this->render('transporte/administracion/destinatario/detalle.html.twig', [
            'arDestinatario' => $arDestinatario,
            'clase' => array('clase' => 'TteDestinatario', 'codigo' => $id),
        ]);
    }

    /**
     * @Route("/turno/administracion/destinatario/nuevo/{id}", name="transporte_administracion_guia_destinatario_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDestinatario = new TteDestinatario();
        if ($id != '0') {
            $arDestinatario = $em->getRepository(TteDestinatario::class)->find($id);
            if (!$arDestinatario) {
                return $this->redirect($this->generateUrl('transporte_administracion_guia_destinatario_lista'));
            }
        }
        $form = $this->createForm(DestinatarioType::class, $arDestinatario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arDestinatario);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_guia_destinatario_detalle', ['id'=>$arDestinatario->getCodigoDestinatarioPk()]));
            }
        }
        return $this->render('transporte/administracion/destinatario/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }



}