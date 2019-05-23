<?php

namespace App\Controller\RecursoHumano\Movimiento\Examen;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Form\Type\RecursoHumano\ExamenControlType;
use App\Form\Type\RecursoHumano\ExamenType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExamenController extends ControllerListenerGeneral
{
    protected $clase = RhuExamen::class;
    protected $claseNombre = "RhuExamen";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Examen";
    protected $nombre = "Examen";


    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/examen/examen/lista", name="recursohumano_movimiento_examen_examen_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Examenes");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuExamen::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_examen_examen_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/examen/examen/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/examen/examen/nuevo{id}", name="recursohumano_movimiento_examen_examen_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arExamen = new RhuExamen();
        if ($id != '0') {
            $arExamen = $em->getRepository(RhuExamen::class)->find($id);
            if (!$arExamen) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_examen_examen_lista'));
            }
        } else {
            $arExamen->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(ExamenType::class, $arExamen);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoEmpleado = $request->request->get('txtCodigoEmpleado');
                if ($txtCodigoEmpleado != '') {
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($txtCodigoEmpleado);
                    if ($arEmpleado) {
                        $arExamen->setEmpleadoRel($arEmpleado);
                        $arExamen->setFecha(new \DateTime('now'));
                        $arExamen->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arExamen->setNombreCorto($arEmpleado->getNombreCorto());
                        $arExamen->setCiudadRel($arEmpleado->getCiudadRel());
                        $arExamen->setEmpleadoRel($arEmpleado);
                        $arExamen->setCargoRel($arEmpleado->getCargoRel());
                        $arExamen->setCodigoSexoFk($arEmpleado->getCodigoSexoFk());
                        if ($id == 0) {
                            $arExamen->setFecha(new \DateTime('now'));
                            $arExamen->setUsuario($this->getUser()->getUserName());
                        }
                        $em->persist($arExamen);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_examen_examen_detalle', ['id' => $arExamen->getCodigoExamenPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un Empleado');
                }
            }
        }
        return $this->render('recursohumano/movimiento/examen/examen/nuevo.html.twig', [
            'arExamen' => $arExamen,
            'form' => $form->createView()
        ]);
    }

//    /**
//     * @Route("/recursohumano/movimiento/examen/nuevo/control/{codigoExamen}", name="recursohumano_movimiento_examen_nuevo_control")
//     */
//    public function nuevoControl(Request $request, $codigoExamen)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $arExamen = new RhuExamen();
//
//        $form = $this->createForm(ExamenControlType::class, $arExamen);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $arUsuario = $this->get('security.token_storage')->getToken()->getUser();
//            $arExamen = $form->getData();
//            $arExamen->setClienteRel($arExamen->getCentroCostoRel()->getClienteRel());
//            if ($codigoExamen == 0) {
//                if ($arExamen->getClienteRel()) {
//                    $arExamen->setCobro($arExamen->getClienteRel()->getCobroExamen());
//                } else {
//                    $arExamen->setCobro('N');
//                }
//                $arExamen->setCodigoUsuario($arUsuario->getUserName());
//                if ($arExamen->getExamenClaseRel()->getCodigoExamenClasePk() == 1 && $codigoExamen == 0) {
//                    $arExamenTipos = new RhuExamenTipo();
//                    $arExamenTipos = $em->getRepository(RhuExamenTipo::class)->findBy(array('ingreso' => 1));
//                    foreach ($arExamenTipos as $arExamenTipo) {
//                        $arExamenListaPrecio = $em->getRepository(RhuExamenListaPrecio::class)->findOneBy(array('codigoEntidadExamenFk' => $arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk(), 'codigoExamenTipoFk' => $arExamenTipo->getCodigoExamenTipoPk()));
//                        if ($arExamenListaPrecio) {
//                            $arExamenDetalle = new RhuExamenDetalle();
//                            $arExamenDetalle->setExamenRel($arExamen);
//                            $arExamenDetalle->setExamenTipoRel($arExamenTipo);
//                            $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getPrecio());
//                            $arExamenDetalle->setFechaVence(new \DateTime('now'));
//                            $arExamenDetalle->setFechaExamen(new \DateTime('now'));
//                            $em->persist($arExamenDetalle);
//                        }
//                    }
//                }
//                $arCargo = $form->get('cargoRel')->getData();
//                $arExamenCargo = new RhuCargo();
//                $arExamenCargo = $em->getRepository(RhuCargo::class)->findBy(array('codigoCargoFk' => $arCargo->getCodigoCargoPk()));
//                foreach ($arExamenCargo as $arExamenCargo) {
//                    $arExamenListaPrecio = $em->getRepository(RhuExamenListaPrecio::class)->findOneBy(array('codigoEntidadExamenFk' => $arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk(), 'codigoExamenTipoFk' => $arExamenCargo->getCodigoExamenTipoFk()));
//                    if ($arExamenListaPrecio) {
//                        $arExamenDetalle = new RhuExamenDetalle();
//                        $arExamenDetalle->setExamenRel($arExamen);
//                        $arExamenDetalle->setExamenTipoRel($arExamenCargo->getExamenTipoRel());
//                        $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getPrecio());
//                        $arExamenDetalle->setFechaVence(new \DateTime('now'));
//                        $arExamenDetalle->setFechaExamen(new \DateTime('now'));
//                        $em->persist($arExamenDetalle);
//                    }
//                }
//            }
//
//            $em->persist($arExamen);
//            if($codigoExamen == 0){
//                $validarExamen = $em->getRepository(RhuExamen::class)->validarExamenes($arExamen);
//                if($validarExamen == 1){
//                    Mensajes::error("error","Ya existe una incapacidad con los datos ingresados");
//                }else{
//                    $em->flush();
//                }
//            }else{
//                $em->flush();
//            }
//        }
//        return $this->render('recursohumano/movimiento/examen/examen/nuevoControl.html.twig', array(
//            'arExamen' => $arExamen,
//            'form' => $form->createView()));
//
//    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/recursohumano/movimiento/examen/examen/detalle/{id}", name="recursohumano_movimiento_examen_examen_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arExamenes = $em->getRepository(RhuExamen::class)->find($id);
        $form = Estandares::botonera($arExamenes->getEstadoAutorizado(), $arExamenes->getEstadoAprobado(), $arExamenes->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arExamenes->getEstadoAutorizado()) {
            $arrBtnAutorizar['disable'] = true;
            $arrBtnEliminar['disable'] = true;
            $arrBtnActualizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
            $arrBtnDesautorizar['disable'] = false;
        }
        if ($arExamenes->getEstadoAprobado()) {
            $arrBtnDesautorizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
        }

        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(RhuExamenDetalle::class)->eliminar($arrDetallesSeleccionados, $id);
                $em->getRepository(RhuExamen::class)->liquidar($id);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(RhuExamenDetalle::class)->actualizarDetalles($arrControles, $form, $arExamenes);
            }
            return $this->redirect($this->generateUrl('recursohumano_movimiento_examen_examen_detalle', ['id' => $id]));
        }
        $arExamenDetalles = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $id));
        return $this->render('recursohumano/movimiento/examen/examen/detalle.html.twig', array(
            'form' => $form->createView(),
            'arExamenDetalles' => $arExamenDetalles,
            'arExamenes' => $arExamenes
        ));
    }

    /**
     * @param Request $request
     * @param $codigoExamen
     * @param int $codigoExamenDetalle
     * @return Response
     * @Route("/recursohumano/movimiento/examen/examen/detalle/nuevo/{codigoExamen}/{id}", name="recursohumano_movimiento_examen_examen_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoExamen, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arExamen = new RhuExamen();
        $arExamen = $em->getRepository(RhuExamen::class)->find($codigoExamen);
        $arExamenListaPrecios = $em->getRepository(RhuExamenListaPrecio::class)->findBy(array('codigoEntidadExamenFk' => $arExamen->getCodigoEntidadExamenFk()));
        $form = $this->createFormBuilder()
            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
//        $form = $this->createForm(ExamenDetalleType::class, $arExamenDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($arExamen->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arExamenListaPrecio = new RhuExamenListaPrecio();
                            $arExamenListaPrecio = $em->getRepository(RhuExamenListaPrecio::class)->find($codigo);
                            $arExamenDetalleValidar = new RhuExamenDetalle();
                            $arExamenDetalleValidar = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $codigoExamen, 'codigoExamenTipoFk' => $arExamenListaPrecio->getCodigoExamenTipoFk()));
                            if (!$arExamenDetalleValidar) {
                                $arExamenTipo = $em->getRepository(RhuExamenTipo::class)->find($arExamenListaPrecio->getCodigoExamenTipoFk());
                                $arExamenDetalle = new RhuExamenDetalle();
                                $arExamenDetalle->setExamenTipoRel($arExamenTipo);
                                $arExamenDetalle->setExamenRel($arExamen);
                                $arExamenDetalle->setFechaExamen($arExamen->getFecha());
                                $arExamenDetalle->setFechaVence($arExamen->getFecha());
                                $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getPrecio());
                                $em->persist($arExamenDetalle);
                            }
                        }
                        $em->flush();
//                        $em->getRepository(RhuExamen::class)->liquidar($codigoExamen);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    } else {
                        Mensajes::error("error", "No selecciono ningun dato para guardar");
                    }
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('recursohumano/movimiento/examen/examen/detalleNuevo.html.twig', array(
            'arExamenListaPrecios' => $arExamenListaPrecios,
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }
}

