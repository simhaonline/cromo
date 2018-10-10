<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Reclamo;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuReclamo;
use App\Form\Type\RecursoHumano\EmbargoType;
use App\Form\Type\RecursoHumano\ReclamoType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReclamoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/recursohumano/movimiento/nomina/reclamo/lista", name="recursohumano_movimiento_nomina_reclamo_lista")
     */
    public function lista(Request $request)
    {
        $clase = RhuReclamo::class;
        $em = $this->getDoctrine()->getManager();
        $arrParametrosLista = $em->getRepository($clase)->parametrosLista();
        $formBotonera = Estandares::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if($formBotonera->get('btnExcel')->isClicked()){
                General::get()->setExportar($em->getRepository($clase)->parametrosExcel(), "Reclamos");
            }
            if($formBotonera->get('btnEliminar')->isClicked()){

            }
        }
        return $this->render('recursoHumano/movimiento/nomina/reclamo/lista.html.twig', [
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
        $clase = RhuReclamo::class;
        $arRegistro = new $clase;
        if ($id != 0) {
            $arRegistro = $em->getRepository($clase)->find($id);
        } else {
            $arRegistro->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(ReclamoType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arRegistro);
                $em->flush();
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/reclamo/nuevo.html.twig', [
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
        $arEmbargo = $em->getRepository(RhuReclamo::class)->find($id);
        if(!$arEmbargo){
            return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_reclamo_lista'));
        }
        return $this->render('recursoHumano/movimiento/nomina/reclamo/detalle.html.twig',[
            'arEmbargo' => $arEmbargo
        ]);
    }


}