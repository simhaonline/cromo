<?php

namespace App\Controller\RecursoHumano\Administracion\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use App\Form\Type\RecursoHumano\ExamenType;
use App\Form\Type\RecursoHumano\RequisitoCargoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RequisitoCargoController extends ControllerListenerGeneral
{
    protected $clase = RhuExamen::class;
    protected $claseFormulario = RequisitoCargoType::class;
    protected $claseNombre = "RhuRequisitoCargo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Contratacion";
    protected $nombre = "RequisitoCargo";


    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/recursohumano/administracion/contratacion/requisitocargo/lista", name="recursohumano_administracion_contratacion_requisitocargo_lista")
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
                $this->get("UtilidadesModelo")->eliminar(RhuRequisitoCargo::class, $arData);
            }
        }
        $arRequisitosConceptos = $paginator->paginate($em->getRepository(RhuRequisitoCargo::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/administracion/contratacion/requisitocargo/lista.html.twig', [
            'arRequisitosConceptos' => $arRequisitosConceptos,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/recursohumano/administracion/contratacion/requisitocargo/nuevo/{id}", name="recursohumano_administracion_contratacion_requisitocargo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitosConcepto = $em->getRepository(RhuRequisitoConcepto::class)->findAll();
        if (!$arRequisitosConcepto) {
            return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitocargo_lista'));
        }
        $form = $this->createFormBuilder()
            ->add('cargoRel', EntityType::class, array(
                'class' => RhuCargo::class,
                'choice_label' => 'nombre',
            ))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoRequisitoConcepto) {
                        $arRequisitoConcepto = $em->getRepository(RhuRequisitoConcepto::class)->find($codigoRequisitoConcepto);
                        $arCargo = $form->get('cargoRel')->getData();
                        $arRequisitoCargo = new RhuRequisitoCargo();
                        $arRequisitoCargo->setCargoRel($arCargo);
                        $arRequisitoCargo->setRequisitoConceptoRel($arRequisitoConcepto);
                        $em->persist($arRequisitoCargo);
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitocargo_lista'));
            }
        }
        return $this->render('recursohumano/administracion/contratacion/requisitocargo/nuevo.html.twig', array(
            'form' => $form->createView(),
            'arRequisitosConcepto' => $arRequisitosConcepto,
        ));

    }
}

