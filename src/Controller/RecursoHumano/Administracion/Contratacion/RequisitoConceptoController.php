<?php

namespace App\Controller\RecursoHumano\Administracion\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use App\Form\Type\RecursoHumano\RequisitoConceptoType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RequisitoConceptoController extends ControllerListenerGeneral
{
    protected $clase = RhuRequisitoConcepto::class;
    protected $claseFormulario = RequisitoConceptoType::class;
    protected $claseNombre = "RhuRequisitoConcepto";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Contratacion";
    protected $nombre = "RequisitoConcepto";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/recursohumano/administracion/contratacion/requisitoconcepto/lista", name="recursohumano_administracion_contratacion_requisitoconcepto_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn-sm btn btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuRequisitoConcepto::class, $arData);
            }
        }
        $arRequisitosConceptos = $paginator->paginate($em->getRepository(RhuRequisitoConcepto::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/administracion/contratacion/requisitoconcepto/lista.html.twig', [
            'arRequisitosConceptos' => $arRequisitosConceptos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/contratacion/requisitoconcepto/nuevo/{id}", name="recursohumano_administracion_contratacion_requisitoconcepto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitoConceptos = new RhuRequisitoConcepto();
        if ($id != 0) {
            $arRequisitoConceptos = $em->getRepository(RhuRequisitoConcepto::class)->find($id);
            if (!$arRequisitoConceptos) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitoconcepto_lista'));
            }
        }
        $form = $this->createForm(RequisitoConceptoType::class, $arRequisitoConceptos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arRequisitoConceptos);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitoconcepto_lista'));
        }
        return $this->render('recursohumano/administracion/contratacion/requisitoconcepto/nuevo.html.twig', array(
            'form' => $form->createView()
        ));
    }
}

