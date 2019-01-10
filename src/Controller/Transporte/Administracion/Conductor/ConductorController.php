<?php

namespace App\Controller\Transporte\Administracion\Conductor;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteConductor;
use App\Form\Type\Transporte\ConductorType;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ConductorController extends ControllerListenerGeneral
{
    protected $class= TteConductor::class;
    protected $claseNombre = "TteConductor";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Transporte";
    protected $nombre = "Conductor";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/administracion/conductor/lista", name="transporte_administracion_transporte_conductor_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Conductores");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(TteFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_conductor_lista'));
            }

        }
        return $this->render('transporte/administracion/conductor/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/transporte/administracion/conductor/nuevo/{id}", name="transporte_administracion_transporte_conductor_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConductor = new TteConductor();
        if ($id != '0') {
            $arConductor = $em->getRepository(TteConductor::class)->find($id);
            if (!$arConductor) {
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_conductor_lista'));
            }
        }
        $form = $this->createForm(ConductorType::class, $arConductor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arConductor->setNombreCorto($arConductor->getNombre1() . " " . $arConductor->getNombre2() . " " . $arConductor->getApellido1() . " " . $arConductor->getApellido2());
                $em->persist($arConductor);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_conductor_lista'));
            }
            if($form->get('guardarnuevo')->isClicked()){
                $arConductor->setNombreCorto($arConductor->getNombre1() . " " . $arConductor->getNombre2() . " " . $arConductor->getApellido1() . " " . $arConductor->getApellido2());
                $em->persist($arConductor);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_conductor_nuevo',['id'=>0]));
            }

        }
        return $this->render('transporte/administracion/conductor/nuevo.html.twig', [
            'arConductor' => $arConductor,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/administracion/conductor/detalle/{id}", name="transporte_administracion_transporte_conductor_detalle")
     */
    public function detalle(){
        return $this->redirect($this->generateUrl('transporte_administracion_transporte_conductor_lista'));
    }

}

