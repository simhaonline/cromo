<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteServicio;
use App\Form\Type\Transporte\GuiaType;
use App\Form\Type\Transporte\NovedadType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GuiaController extends ControllerListenerGeneral
{
    protected $clase= TteGuia::class;
    protected $claseNombre = "TteGuia";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Guia";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/transporte/guia/lista", name="transporte_movimiento_transporte_guia_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);


        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(TteGuia::class)->lista())->execute(), "Guias");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
//                $datos = $this->getDatosLista();
            }
        }
        $datos = $this->getDatosLista(true);
        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
//        $paginator = $this->get('knp_paginator');
//        $fecha = new \DateTime('now');
//        if($session->get('filtroFechaDesde') == "") {
//            $session->set('filtroFechaDesde', $fecha->format('Y-m-d'));
//        }
//        if($session->get('filtroFechaHasta') == "") {
//            $session->set('filtroFechaHasta', $fecha->format('Y-m-d'));
//        }
//        $form = $this->createFormBuilder()
//            ->add('txtRemitente', TextType::class, array('data' => $session->get('filtroTteGuiaRemitente')))
//            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFecha')))
//            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
//            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
//            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
//            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
//            ->add('cboGuiaTipoRel', EntityType::class, $em->getRepository(TteGuiaTipo::class)->llenarCombo())
//            ->add('cboServicioRel', EntityType::class, $em->getRepository(TteServicio::class)->llenarCombo())
//            ->add('cboOperacionCargoRel', EntityType::class, $em->getRepository(TteOperacion::class)->llenarCombo())
//            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroTteGuiaCodigo')))
//            ->add('txtDocumento', TextType::class, array('data' => $session->get('filtroTteGuiaDocumento')))
//            ->add('txtCodigoFactura', TextType::class, array('data' => $session->get('filtroTteFacturaCodigo')))
//            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroTteGuiaNumero')))
//            ->add('chkEstadoFacturado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteGuiaEstadoFacturado'), 'required' => false])
//            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
//            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
//            ->getForm();
//        $form->handleRequest($request);
//        if ($form->isSubmitted()) {
//            if ($form->isValid()) {
//                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
//                    $session = new session;
//                    $arGuiaTipo = $form->get('cboGuiaTipoRel')->getData();
//                    if ($arGuiaTipo) {
//                        $session->set('filtroTteGuiaCodigoGuiaTipo', $arGuiaTipo->getCodigoGuiaTipoPk());
//                    } else {
//                        $session->set('filtroTteGuiaCodigoGuiaTipo', null);
//                    }
//                    $arServicio = $form->get('cboServicioRel')->getData();
//                    if ($arServicio) {
//                        $session->set('filtroTteGuiaCodigoServicio', $arServicio->getCodigoServicioPk());
//                    } else {
//                        $session->set('filtroTteGuiaCodigoServicio', null);
//                    }
//                    if ($form->get('cboOperacionCargoRel')->getData() != '') {
//                        $session->set('filtroTteGuiaOperacion', $form->get('cboOperacionCargoRel')->getData()->getCodigoOperacionPk());
//                    } else {
//                        $session->set('filtroTteGuiaOperacion', null);
//                    }
//                    $session->set('filtroTteGuiaRemitente', $form->get('txtRemitente')->getData());
//                    $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
//                    $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
//                    $session->set('filtroTteFacturaCodigo', $form->get('txtCodigoFactura')->getData());
//                    $session->set('filtroTteGuiaDocumento', $form->get('txtDocumento')->getData());
//                    $session->set('filtroTteGuiaNumero', $form->get('txtNumero')->getData());
//                    $session->set('filtroTteGuiaCodigo', $form->get('txtCodigo')->getData());
//                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
//                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
//                    $session->set('filtroTteGuiaEstadoFacturado', $form->get('chkEstadoFacturado')->getData());
//                    $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
//                }
//                if ($form->get('btnExcel')->isClicked()) {
//                    General::get()->setExportar($em->createQuery($em->getRepository(TteGuia::class)->lista())->execute(), "Guias");
//                }
//            }
//        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->lista(), $request->query->getInt('page', 1), 30);
//        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', [
//            'arGuias' => $arGuias,
//            'form' => $form->createView()
//        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/transporte/movimiento/transporte/guia/nuevo/{id}", name="transporte_movimiento_transporte_guia_nuevo")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = new TteGuia();
        if ($id != 0) {
            $arGuia = $em->getRepository(TteGuia::class)->find($id);
        }
        $form = $this->createForm(GuiaType::class, $arGuia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        if ($id == 0) {
                            $arGuia->setFechaIngreso(new \DateTime('now'));
                        }
                        /*$arGuia->setClienteRel($arCliente);
                        $arGuia->setOperacionIngresoRel($this->getUser()->getOperacionRel());
                        $arGuia->setOperacionCargoRel($this->getUser()->getOperacionRel());
                        $arGuia->setFactura($arGuia->getGuiaTipoRel()->getFactura());
                        $arGuia->setCiudadOrigenRel($this->getUser()->getOperacionRel() ? $this->getUser()->getOperacionRel()->getCiudadRel() : null);*/
                        $em->persist($arGuia);
                        $em->flush();
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_lista'));
                    } else {
                        Mensajes::error('No se encontro un cliente con el codigo ingresado');
                    }
                } else {
                    Mensajes::error('Debe de seleccionar un cliente');
                }
            }
        }
        return $this->render('transporte/movimiento/transporte/guia/nuevo.html.twig', ['arGuia' => $arGuia, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/transporte/movimiento/transporte/guia/detalle/{id}", name="transporte_movimiento_transporte_guia_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($id);
        $form = Estandares::botonera($arGuia->getEstadoAutorizado(), $arGuia->getEstadoAprobado(), $arGuia->getEstadoAnulado());
        $form->add('btnRetirarNovedad', SubmitType::class, array('label' => 'Retirar'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $respuesta = $em->getRepository(TteGuia::class)->imprimir($id);
                if ($respuesta) {
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_detalle', array('id' => $id)));
                }

            }
            if ($form->get('btnRetirarNovedad')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteNovedad::class)->eliminar($arrSeleccionados);
            }
        }
        $arNovedades = $this->getDoctrine()->getRepository(TteNovedad::class)->guia($id);
        $arDespachoDetalles = $this->getDoctrine()->getRepository(TteDespachoDetalle::class)->guia($id);
        $arFacturaDetalles = $this->getDoctrine()->getRepository(TteFacturaDetalle::class)->guia($id);
        return $this->render('transporte/movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'arNovedades' => $arNovedades,
            'arDespachoDetalles' => $arDespachoDetalles,
            'arFacturaDetalles' => $arFacturaDetalles,
            'clase' => array('clase'=>'tte_guia', 'codigo' => $id),
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/trasnporte/guia/detalle/adicionar/novedad/{codigoGuia}/{codigoNovedad}", name="transporte_movimiento_transporte_guia_detalle_adicionar_novedad")
     */
    public function detalleAdicionarNovedad(Request $request, $codigoGuia, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $arNovedad = new TteNovedad();
        if ($codigoNovedad == 0) {
            $arNovedad->setEstadoAtendido(true);
            $arNovedad->setFechaReporte(new \DateTime('now'));
            $arNovedad->setFecha(new \DateTime('now'));
        } else {
            $arNovedad = $em->getRepository(TteNovedad::class)->find($codigoNovedad);
        }
        $form = $this->createForm(NovedadType::class, $arNovedad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arNovedad = $form->getData();
            $arNovedad->setGuiaRel($arGuia);
            if ($codigoNovedad == 0) {
                $arNovedad->setFechaRegistro(new \DateTime('now'));
                $arNovedad->setFechaAtencion(new \DateTime('now'));
                $arNovedad->setFechaSolucion(new \DateTime('now'));
            }
            $arGuia->setEstadoNovedad(1);
            $em->persist($arGuia);
            $em->persist($arNovedad);
            $em->flush();

            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }

        return $this->render('transporte/movimiento/transporte/guia/detalleAdicionarNovedad.html.twig', [
            'arGuia' => $arGuia,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/novedad/solucion/{codigoNovedad}", name="transporte_movimiento_transporte_novedad_solucion")
     */
    public function novedadSolucion(Request $request, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TteNovedad::class)->find($codigoNovedad);
        $form = $this->createFormBuilder()
            ->add('solucion', TextareaType::class, array('label' => 'Solucion'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arNovedad->setEstadoSolucion(1);
            $arNovedad->setSolucion($form->get('solucion')->getData());
            $arNovedad->setFechaSolucion(new \DateTime('now'));
            $em->persist($arNovedad);
            $arGuia = $em->getRepository(TteGuia::class)->find($arNovedad->getCodigoGuiaFk());
            $arGuia->setEstadoNovedad(0);
            $arGuia->setEstadoNovedadSolucion(1);
            $em->persist($arGuia);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('transporte/movimiento/transporte/guia/novedadSolucion.html.twig', array (
        'form' => $form->createView()));
    }
}

