<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Embargo;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Form\Type\RecursoHumano\EmbargoType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmbargoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/recursohumano/movimiento/nomina/embargo/lista", name="recursohumano_movimiento_nomina_embargo_lista")
     */
    public function lista(Request $request)
    {
        $clase = RhuEmbargo::class;
        $em = $this->getDoctrine()->getManager();
        $arrParametrosLista = $em->getRepository($clase)->parametrosLista();
        $formBotonera = Estandares::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if($formBotonera->get('btnExcel')->isClicked()){
                General::get()->setExportar($em->getRepository($clase)->parametrosExcel(), "Embargos");
            }
            if($formBotonera->get('btnEliminar')->isClicked()){

            }
        }
        return $this->render('recursoHumano/movimiento/nomina/embargo/lista.html.twig', [
            'arrParametrosLista' => $arrParametrosLista,
            'request' => $request,
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recursohumano/movimiento/embargo/embargo/nuevo/{id}", name="recursohumano_movimiento_embargo_embargo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargo = new RhuEmbargo();
        if ($id != 0) {
            $arEmbargo = $em->getRepository(RhuEmbargo::class)->find($id);
        } else {
            $arEmbargo->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(EmbargoType::class, $arEmbargo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arEmbargo);
                $em->flush();
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/embargo/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/recursohumano/movimiento/embargo/embargo/detalle/{id}", name="recursohumano_movimiento_embargo_embargo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargo = $em->getRepository(RhuEmbargo::class)->find($id);
        if(!$arEmbargo){
            return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_embargo_lista'));
        }
        return $this->render('recursoHumano/movimiento/nomina/embargo/detalle.html.twig',[
            'arEmbargo' => $arEmbargo
        ]);
    }


}