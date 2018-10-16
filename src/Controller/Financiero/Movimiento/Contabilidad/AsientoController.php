<?php

namespace App\Controller\Financiero\Movimiento\Contabilidad;

use App\Controller\Financiero\BaseController;
use App\Entity\Financiero\FinAsiento;
use App\Form\Type\Financiero\AsientoType;
use App\General\General;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AsientoController extends BaseController
{
    protected $clase = FinAsiento::class;
    protected $modulo = "Financiero";
    protected $grupo = "Contabilidad";
    protected $claseNombre = "FinAsiento";
    protected $claseFormulario = "Asiento";
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/movimiento/contabilidad/asiento/lista", name="financiero_movimiento_contabilidad_asiento_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if($formBotonera->get('btnExcel')->isClicked()){
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Asientos");
            }
            if($formBotonera->get('btnEliminar')->isClicked()){

            }
        }
        return $this->render('financiero/movimiento/contabilidad/asiento/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/asiento/nuevo/{id}", name="financiero_movimiento_contabilidad_asiento_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAsiento = new FinAsiento();
        if ($id != 0) {
            $arAsiento = $em->getRepository(FinAsiento::class)->find($id);
        } else {
            $arAsiento->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(AsientoType::class, $arAsiento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arAsiento);
                $em->flush();
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/asiento/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/financiero/movimiento/contabilidad/asiento/detalle/{id}", name="financiero_movimiento_contabilidad_asiento_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAsiento = $em->getRepository(FinAsiento::class)->find($id);
        if(!$arAsiento){
            return $this->redirect($this->generateUrl('financiero_movimiento_nomina_asiento_lista'));
        }
        return $this->render('recursoHumano/movimiento/nomina/asiento/detalle.html.twig',[
            'arAsiento' => $arAsiento
        ]);
    }


}