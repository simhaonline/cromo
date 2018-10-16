<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;


use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContratoController extends Controller
{
    /**
     * @Route("recursohumano/administracion/contrato/contrato/lista", name="recursohumano_administracion_contrato_contrato_lista")
     */
    public function lista(Request $request)
    {
        $clase = RhuContrato::class;
        $em = $this->getDoctrine()->getManager();
        $arrParametrosLista = $em->getRepository($clase)->parametrosLista();
        $formBotonera = Estandares::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($clase)->parametrosExcel(), "Embargos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        return $this->render('recursoHumano/administracion/empleado/lista.html.twig', [
            'arrParametrosLista' => $arrParametrosLista,
            'request' => $request,
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/empleado/empleado/detalle/{id}", name="recursohumano_administracion_empleado_empleado_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new RhuEmpleado();
        if ($id != 0) {
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($id);
            if (!$arEmpleado) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_empleado_empleado_lista'));
            }
        }
        $form = $this->createForm(EmpleadoType::class, $arEmpleado);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arEmpleado);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_empleado_empleado_detalle'));
            }
        }
        $arContratos = $em->getRepository(RhuContrato::class)->contratosEmpleado($arEmpleado->getCodigoEmpleadoPk());
        return $this->render('recursoHumano/administracion/empleado/detalle.html.twig', [
            'form' => $form->createView(),
            'arEmpleado' => $arEmpleado,
            'arContratos' => $arContratos
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/empleado/empleado/nuevo/{id}", name="recursohumano_administracion_empleado_empleado_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new RhuEmpleado();
        if ($id != 0) {
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($id);
            if (!$arEmpleado) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_empleado_empleado_lista'));
            }
        }
        $form = $this->createForm(EmpleadoType::class, $arEmpleado);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arEmpleado);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_empleado_empleado_detalle'));
            }
        }
        return $this->render('recursoHumano/administracion/empleado/nuevo.html.twig', [
            'form' => $form->createView(), 'arEmpleado' => $arEmpleado
        ]);
    }
}

