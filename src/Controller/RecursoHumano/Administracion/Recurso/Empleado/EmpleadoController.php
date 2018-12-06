<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Empleado;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\ContratoType;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmpleadoController extends BaseController
{
    protected $clase = RhuEmpleado::class;
    protected $claseFormulario = EmpleadoType::class;
    protected $claseNombre = "RhuEmpleado";
    protected $modulo = "RecursoHumano";
    protected $funcion = "administracion";
    protected $grupo = "Recurso";
    protected $nombre = "Empleado";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/administracion/recurso/empleado/lista", name="recursohumano_administracion_recurso_empleado_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        return $this->render('recursohumano/administracion/recurso/empleado/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/empleado/nuevo/{id}", name="recursohumano_administracion_recurso_empleado_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new RhuEmpleado();
        if ($id != 0) {
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($id);
            if (!$arEmpleado) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_empleado_lista'));
            }
        }
        $form = $this->createForm(EmpleadoType::class, $arEmpleado);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleadoBuscar = $em->getRepository($this->clase)->findOneBy(['codigoIdentificacionFk' => $arEmpleado->getIdentificacionRel()->getCodigoIdentificacionPk(), 'numeroIdentificacion' => $arEmpleado->getNumeroIdentificacion()]);
                if ((!is_null($arEmpleado->getCodigoEmpleadoPk()) && $arEmpleado->getCodigoEmpleadoPk() == $arEmpleadoBuscar->getCodigoEmpleadoPk()) || is_null($arEmpleadoBuscar)) {
                    $nombreCorto = $arEmpleado->getNombre1();
                    if ($arEmpleado->getNombre2()) {
                        $nombreCorto .= " " . $arEmpleado->getNombre2();
                    }
                    $nombreCorto .= " " . $arEmpleado->getApellido1();
                    if ($arEmpleado->getApellido2()) {
                        $nombreCorto .= " " . $arEmpleado->getApellido2();
                    }
                    $arEmpleado->setNombreCorto($nombreCorto);
                    $em->persist($arEmpleado);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_empleado_detalle', ['id' => $arEmpleado->getCodigoEmpleadoPk()]));
                } else {
                    Mensajes::error('Ya existe un empleado con la identificación ingresada.');
                }
            }
        }
        return $this->render('recursohumano/administracion/recurso/empleado/nuevo.html.twig', [
            'form' => $form->createView(), 'arEmpleado' => $arEmpleado
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/empleado/detalle/{id}", name="recursohumano_administracion_recurso_empleado_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new RhuEmpleado();
        if ($id != 0) {
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($id);
            if (!$arEmpleado) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_empleado_lista'));
            }
        }
        $arContratos = $em->getRepository(RhuContrato::class)->contratosEmpleado($arEmpleado->getCodigoEmpleadoPk());
        return $this->render('recursohumano/administracion/recurso/empleado/detalle.html.twig', [
            'arEmpleado' => $arEmpleado,
            'arContratos' => $arContratos
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoEmpleado
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/empleado/nuevo/contrato/{codigoEmpleado}/{id}", name="recursohumano_administracion_recurso_empleado_nuevo_contrato")
     */
    public function nuevoContrato(Request $request, $codigoEmpleado, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado);
        $arConfiguracion = $em->find(RhuConfiguracion::class, 1);
        $arContrato = new RhuContrato();
        if ($id != 0) {
            $arContrato = $em->getRepository(RhuContrato::class)->find($id);
        } else {
            $arContrato->setVrSalario($arConfiguracion->getVrSalarioMinimo());
            $arContrato->setFecha(new \DateTime('now'));
            $arContrato->setFechaDesde(new \DateTime('now'));
            $arContrato->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(ContratoType::class, $arContrato);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arContrato->setEmpleadoRel($arEmpleado);
                $arContrato->setEstadoTerminado(false);
                $arContrato->setContratoClaseRel($arContrato->getContratoTipoRel()->getContratoClaseRel());
                $arContrato->setIndefinido($arContrato->getContratoTipoRel()->getContratoClaseRel()->getIndefinido());
                $arContrato->setFactorHorasDia($arContrato->getTiempoRel()->getFactorHorasDia());
                if ($id == 0) {
                    if ($arContrato->getVrSalario() <= ($arConfiguracion->getVrSalarioMinimo() * 2)) {
                        $arContrato->setAuxilioTransporte(true);
                    }
                    $arContrato->setFechaUltimoPago($arContrato->getFechaDesde());
                    $arContrato->setFechaUltimoPagoCesantias($arContrato->getFechaDesde());
                    $arContrato->setFechaUltimoPagoPrimas($arContrato->getFechaDesde());
                    $arContrato->setFechaUltimoPagoVacaciones($arContrato->getFechaDesde());
                }
                $em->persist($arContrato);
                $em->flush();

                $arEmpleado->setCodigoContratoFk($arContrato->getCodigoContratoPk());
                $arEmpleado->setEstadoContrato(true);
                $arEmpleado->setCargoRel($arContrato->getCargoRel());
                $em->persist($arEmpleado);
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('recursohumano/administracion/recurso/empleado/nuevoContrato.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

