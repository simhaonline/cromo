<?php

namespace App\Controller\Transporte\Administracion\General\Ciudad;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCiudad;
use App\Form\Type\Transporte\CiudadType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CiudadController extends ControllerListenerGeneral
{
    protected $clase= TteCiudad::class;
    protected $claseNombre = "TteCiudad";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Ciudad";

    /**
     * @Route("/transporte/administracion/general/ciudad/lista", name="transporte_administracion_general_ciudad_lista")
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
//                $datos = $this->getDatosLista();
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
//                General::get()->setExportarE($em->createQuery($datos['queryBuilder'])->execute(), "Ciudad");//cambiar
                $this->exportarExcel($datos['queryBuilder']);
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteCiudad::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_administracion_general_ciudad_lista'));
            }
        }
        return $this->render('transporte/administracion/general/ciudad/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @Route("/transporte/administracion/general/ciudad/nuevo/{id}", name="transporte_administracion_general_ciudad_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCiudad = new TteCiudad();
        if ($id != 0) {
            $arCiudad = $em->getRepository(TteCiudad::class)->find($id);
        }
        $form = $this->createForm(CiudadType::class, $arCiudad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCiudad);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_general_ciudad_detalle',['id'=>$arCiudad->getCodigoCiudadPk()]));

            }
            if($form->get('guardarnuevo')->isClicked()){
                $em->persist($arCiudad);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_general_ciudad_nuevo',['id'=>0]));

            }
        }
        return $this->render('transporte/administracion/general/ciudad/nuevo.html.twig',
            ['arCiudad' => $arCiudad, 'form' => $form->createView()]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/transporte/administracion/general/ciudad/detalle/{id}", name="transporte_administracion_general_ciudad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCiudad = $em->getRepository(TteCiudad::class)->find($id);

        return $this->render('transporte/administracion/general/ciudad/detalle.html.twig',[
            'arCiudad'=>$arCiudad,
        ]);
    }
}
