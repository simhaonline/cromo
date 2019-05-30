<?php

namespace App\Controller\Turno\Administracion\Concepto;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurPuesto;
use App\Form\Type\Turno\ClienteType;
use App\Form\Type\Turno\ConceptoType;
use App\Form\Type\Turno\PuestoType;
use App\General\General;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConceptoController extends ControllerListenerGeneral
{
    protected $clase = TurConcepto::class;
    protected $claseNombre = "TurConcepto";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "Concepto";
    protected $nombre = "Concepto";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/administracion/concepto/concepto/lista", name="turno_administracion_concepto_concepto_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);

        $form = $this->createFormBuilder()
            ->add('txtCodigoConcepto', TextType::class, array('required' => false, 'data' => $session->get('filtroTurConceptoCodigo')))
            ->add('txtNombreConcepto', TextType::class, array('required' => false, 'data' => $session->get('filtroTurConceptoNombre')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtro'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurConceptoCodigo', $form->get('txtCodigoConcepto')->getData());
                $session->set('filtroTurConceptoNombre', $form->get('txtNombreConcepto')->getData());
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arData = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurConcepto::class, $arData);
            }
        }

        $arConceptos = $paginator->paginate($em->getRepository(TurConcepto::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('turno/administracion/concepto/lista.html.twig', [
            'arConceptos' => $arConceptos,
            'arrDatosLista' => $datos,
            'form' => $form->createView(),
            'formBotonera' => $formBotonera->createView(),
        ]);
    }

    /**
     * @Route("/turno/administracion/concepto/nuevo/{id}", name="turno_administracion_concepto_concepto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $arConceptos = new TurConcepto();
        if ($id != 0) {
            $arConceptos = $em->getRepository(TurConcepto::class)->find($id);
            if (!$arConceptos) {
                return $this->redirect($this->generateUrl('turno_administracion_concepto_concepto_lista'));
            }
        }
        $form = $this->createForm(ConceptoType::class, $arConceptos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $horas = $form->get('horas')->getData();
            $horasDiurnas = $form->get('horasDiurnas')->getData();
            $horasNocturnas = $form->get('horasNocturnas')->getData();
            if ($horas == ($horasDiurnas + $horasNocturnas)) {
                $arConceptos = $form->getData();
                $em->persist($arConceptos);
                $em->flush();

                if ($form->get('guardar')->isClicked()) {

                    return $this->redirect($this->generateUrl('turno_administracion_concepto_concepto_lista'));
                }
            } else {
                Mensajes::error("El total de horas no es igual a la sumas entre las horas diurnas y nocturnas");
            }
        }
        return $this->render('turno/administracion/concepto/nuevo.html.twig', array(
            'arConceptos' => $arConceptos,
            'form' => $form->createView()
        ));
    }
}

