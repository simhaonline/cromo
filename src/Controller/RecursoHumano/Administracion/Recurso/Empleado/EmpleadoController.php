<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Empleado;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Form\Type\RecursoHumano\ContratoType;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoPk', TextType::class, array('required' => false))
            ->add('nombreCorto', TextType::class, array('required' => false))
            ->add('numeroIdentificacion', IntegerType::class, array('required' => false))
            ->add('estadoContrato', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuEmpleado::class)->listaProvicional($raw)->getQuery()->execute(), "Empleados");
            }
            if ($form->get('btnEliminar')->isClicked()) {
            }
        }
        $arEmpleados = $paginator->paginate($em->getRepository(RhuEmpleado::class)->listaProvicional($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/recurso/empleado/lista.html.twig', [
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/empleado/nuevo/{id}/{codigoSeleccion}",requirements={"codigoEmpleado":"\d+","codigoSeleccion":"\d+"},  defaults={"codigoSeleccion"=0}, name="recursohumano_administracion_recurso_empleado_nuevo")
     */
    public function nuevo(Request $request, $id, $codigoSeleccion =0)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new RhuEmpleado();
        if ($id != 0) {
            $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($id);
            if (!$arEmpleado) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_empleado_lista'));
            }
        }
        if ($codigoSeleccion != 0){
            $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($codigoSeleccion);
            $arEmpleado->setIdentificacionRel($arSeleccion->getIdentificacionRel());
            $arEmpleado->setNumeroIdentificacion($arSeleccion->getNumeroIdentificacion());
            $arEmpleado->setNombre1($arSeleccion->getnombre1());
            $arEmpleado->setNombre2($arSeleccion->getnombre2());
            $arEmpleado->setApellido1($arSeleccion->getApellido1());
            $arEmpleado->setApellido2($arSeleccion->getApellido2());
            $arEmpleado->setEstadoCivilRel($arSeleccion->getEstadoCivilRel());
            $arEmpleado->setFechaExpedicionIdentificacion($arSeleccion->getFechaExpedicion());
            $arEmpleado->setFechaNacimiento($arSeleccion->getFechaNacimiento());
            $arEmpleado->setTelefono($arSeleccion->getTelefono());
            $arEmpleado->setCelular($arSeleccion->getCelular());
            $arEmpleado->setCorreo($arSeleccion->getCorreo());
            $arEmpleado->setDireccion($arSeleccion->getDireccion());
            $arEmpleado->setBarrio($arSeleccion->getBarrio());
            $arEmpleado->setFechaExpedicionIdentificacion($arSeleccion->getFechaExpedicion());
            $arEmpleado->setRhRel($arSeleccion->getRhRel());
            $arEmpleado->setCiudadRel($arSeleccion->getCiudadRel());
            $arEmpleado->setCiudadExpedicionRel($arSeleccion->getCiudadExpedicionRel());
            $arEmpleado->setCiudadNacimientoRel($arSeleccion->getCiudadNacimientoRel());
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
                $arContrato->setIndefinido($arContrato->getContratoClaseRel()->getIndefinido());
                $arContrato->setFactorHorasDia($arContrato->getTiempoRel()->getFactorHorasDia());
                if ($arContrato->getTiempoRel()->getFactorHorasDia() > 0) {
                    $factorLocal = 8;
                    $partes =  $arContrato->getTiempoRel()->getFactorHorasDia() / $factorLocal;
                    $salario = $partes * $arContrato->getVrSalario();
                    $arContrato->setVrSalarioPago($salario);
                } else {
                    $arContrato->setVrSalarioPago($arContrato->getVrSalario());
                }

                if ($arContrato->getVrSalario() <= ($arConfiguracion->getVrSalarioMinimo() * 2)) {
                    $arContrato->setAuxilioTransporte(true);
                } else {
                    $arContrato->setAuxilioTransporte(false);
                }
                if ($id == 0) {
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/administracion/recurso/empleado/nuevo/enlazar/seleccion", name="recursohumano_administracion_recurso_empleado_enlazar_seleccion")
     */
    public function EnlazarSeleccion(Request $request){
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this-> createFormBuilder()
            ->add('identificacion', TextType::class,['required' => false, 'data' => $session->get('filtroIdentificacion')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $arseleccion = null;
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->get('btnFiltrar')->isClicked()) {
                $identificacion = $form->get('identificacion')->getData();
                $arseleccion =$paginator->paginate($em->getRepository(RhuSeleccion::class)->findBy(['numeroIdentificacion'=>$identificacion]), $request->query->getInt('page',1), 30);
                $session->set('arseleccion', $em->getRepository(RhuSeleccion::class)->findBy(['numeroIdentificacion'=>$identificacion]));
            }
        }

        return $this->render('recursohumano/administracion/recurso/empleado/enlaceSeleccion.html.twig', [
            'form'=>$form->createView(),
            'arseleccion'=>$arseleccion
        ]);
    }

    public function getFiltros($form)
    {
        return $filtro = [
            'codigoEmpleadoPk' => $form->get('codigoEmpleadoPk')->getData(),
            'nombreCorto' => $form->get('nombreCorto')->getData(),
            'numeroIdentificacion' => $form->get('numeroIdentificacion')->getData(),
            'estadoContrato' => $form->get('estadoContrato')->getData(),
        ];
    }
}

