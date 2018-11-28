<?php

namespace App\Controller\Transporte\Proceso\Transporte\General;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteIntermediacion;
use App\Entity\Transporte\TteIntermediacionDetalle;
use App\Entity\TteGuia;
use App\Form\Type\Transporte\IntermediacionType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IntermediacionController extends Controller
{
   /**
    * @Route("/transporte/proceso/transporte/general/intermediacion", name="transporte_proceso_transporte_general_intermediacion")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteIntermediacion::class)->eliminar($arrSeleccionados);
            }
        }
        $query = $this->getDoctrine()->getRepository(TteIntermediacion::class)->lista();
        $arIntermediacions = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/proceso/transporte/general/intermediacion/lista.html.twig', [
            'arIntermediacions' => $arIntermediacions,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/proceso/transporte/general/intermediacion/nuevo/{id}", name="transporte_proceso_transporte_general_intermediacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIntermediacion = new TteIntermediacion();
        if($id != 0) {
            $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($id);
        }
        $form = $this->createForm(IntermediacionType::class, $arIntermediacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ultimoDia = date("d", (mktime(0, 0, 0, $arIntermediacion->getMes() + 1, 1, $arIntermediacion->getAnio()) - 1));
            $fecha = date_create($arIntermediacion->getAnio() . "-" . $arIntermediacion->getMes() . "-" . $ultimoDia);
            $arIntermediacion->setFecha($fecha);
            $em->persist($arIntermediacion);
            $em->flush();
            return $this->redirect($this->generateUrl('transporte_proceso_transporte_general_intermediacion_detalle', array('id'=> $arIntermediacion->getCodigoIntermediacionPk())));

        }
        return $this->render('transporte/proceso/transporte/general/intermediacion/nuevo.html.twig', [
            'arIntermediacion' => $arIntermediacion,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/proceso/transporte/general/intermediacion/detalle/{id}", name="transporte_proceso_transporte_general_intermediacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arIntermediacion = $em->getRepository(TteIntermediacion::class)->find($id);

        $form = Estandares::botonera($arIntermediacion->getEstadoAutorizado(),$arIntermediacion->getEstadoAprobado(), false);
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteIntermediacion::class)->autorizar($arIntermediacion);
                return $this->redirect($this->generateUrl('transporte_proceso_transporte_general_intermediacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteIntermediacion::class)->desAutorizar($arIntermediacion);
                return $this->redirect($this->generateUrl('transporte_proceso_transporte_general_intermediacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteIntermediacion::class)->aprobar($arIntermediacion);
                return $this->redirect($this->generateUrl('transporte_proceso_transporte_general_intermediacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteIntermediacionDetalle::class)->detalle($id), "Intermediacion");
            }
        }
        $arIntermediacionDetalles = $this->getDoctrine()->getRepository(TteIntermediacionDetalle::class)->detalle($id);
        return $this->render('transporte/proceso/transporte/general/intermediacion/detalle.html.twig', [
            'arIntermediacion' => $arIntermediacion,
            'arIntermediacionDetalles' => $arIntermediacionDetalles,
            'form' => $form->createView()]);
    }

}

