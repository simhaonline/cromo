<?php

namespace App\Controller\Transporte\Movimiento\Comercial\NotaCredito;



use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\DataFixtures\GenConfiguracion;
use App\DataFixtures\TteConfiguracion;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaOtro;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Form\Type\Transporte\FacturaNotaCreditoType;
use App\Form\Type\Transporte\FacturaPlanillaType;
use App\Formato\Transporte\Factura;
use App\Formato\Transporte\NotaCredito;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class NotaCreditoController extends ControllerListenerGeneral
{
    protected $clase= TteFactura::class;
    protected $claseNombre = "TteFactura";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Factura";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/movimiento/comercial/notaCredito/lista", name="transporte_movimiento_comercial_notaCredito_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);


        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
//            if ($formBotonera->get('btnExcel')->isClicked()) {
//                General::get()->setExportar($em->createQuery($em->getRepository(TteGuia::class)->lista())->execute(), "Guias");
//            }
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
        $datos['ruta']=strtolower($this->modulo) . "_" . strtolower($this->funcion) . "_" . strtolower($this->grupo) . "_" . "notaCredito";
        return $this->render('transporte/movimiento/comercial/notaCredito/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);

    }

    /**
     * @Route("/transporte/movimiento/comercial/notaCredito/nuevo/{id}/", name="transporte_movimiento_comercial_notaCredito_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arFactura = new TteFactura();
        if($id != 0) {
            $arFactura = $em->getRepository(TteFactura::class)->find($id);
        } else {
            $arFactura->setCodigoFacturaClaseFk("NC");
        }

        $form = $this->createForm(FacturaNotaCreditoType::class, $arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arFactura->setClienteRel($arCliente);
                    if ($id == 0) {
                        $arFactura->setUsuario($this->getUser()->getUsername());
                        $arFactura->setOperacionRel($this->getUser()->getOperacionRel());
                    }
                    if ($arFactura->getPlazoPago() <= 0) {
                        $arFactura->setPlazoPago($arFactura->getClienteRel()->getPlazoPago());
                    }
                    $fecha = new \DateTime('now');
                    $arFactura->setFecha($fecha);
                    $arFactura->setFechaVence($arFactura->getPlazoPago() == 0 ? $fecha : $objFunciones->sumarDiasFecha($fecha,$arFactura->getPlazoPago()));
                    $arFactura->setOperacionComercial($arFactura->getFacturaTipoRel()->getOperacionComercial());
                    $em->persist($arFactura);
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', array('id' => $arFactura->getCodigoFacturaPk())));
                }
            }
        }
        return $this->render('transporte/movimiento/comercial/factura/nuevoNotaCredito.html.twig', [
            'arFactura' => $arFactura,
            'form' => $form->createView()]);

    }

    /**
     * @Route("/transporte/movimiento/comercial/notaCredito/detalle/{id}", name="transporte_movimiento_comercial_notaCredito_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($id);
        $paginator  = $this->get('knp_paginator');
        $form = Estandares::botonera($arFactura->getEstadoAutorizado(),$arFactura->getEstadoAprobado(),$arFactura->getEstadoAnulado());
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnRetirarPlanilla = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBotonActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        if($arFactura->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
            $arrBtnRetirarPlanilla['disabled'] = true;
            $arrBotonActualizar['disabled'] = true;
        }
        if($arFactura->getCodigoFacturaClaseFk() == 'NC') {
            $arrBotonActualizar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar)
            ->add('btnRetirarPlanilla', SubmitType::class, $arrBtnRetirarPlanilla)
            ->add('btnActualizar', SubmitType::class, $arrBotonActualizar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                if($arFactura->getCodigoFacturaTipoFk() == 'NC'){
                    $formato = new NotaCredito();
                    $formato->Generar($em, $id);
                } else {
                    $formato = new Factura();
                    $formato->Generar($em, $id);
                }
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $this->getDoctrine()->getRepository(TteFactura::class)->liquidar($id);
                $em->getRepository(TteFactura::class)->autorizar($arFactura);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteFactura::class)->desAutorizar($arFactura);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }

            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteFactura::class)->Aprobar($arFactura);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(TteFactura::class)->Anular($arFactura);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $arrControles = $request->request->all();
                $em->getRepository(TteFacturaDetalle::class)->actualizarDetalles($arrControles, $form, $arFactura);
                $this->getDoctrine()->getRepository(TteFactura::class)->liquidar($id);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->retirarDetalle($arrGuias, $arFactura);
                if($respuesta) {
                    $em->getRepository(TteFactura::class)->liquidar($id);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteFacturaDetalle::class)->factura($id), "Facturas $id");
            }
        }
        $query = $this->getDoctrine()->getRepository(TteFacturaPlanilla::class)->listaFacturaDetalle($id);
        $arFacturaPlanillas = $paginator->paginate($query, $request->query->getInt('page', 1),50);
        $query = $this->getDoctrine()->getRepository(TteFacturaOtro::class)->listaFacturaDetalle($id);
        $arFacturaOtros = $paginator->paginate($query, $request->query->getInt('page', 1),50);
        $arFacturaDetalles = $this->getDoctrine()->getRepository(TteFacturaDetalle::class)->factura($id);
        return $this->render('transporte/movimiento/comercial/factura/detalle.html.twig', [
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles,
            'arFacturaPlanillas' => $arFacturaPlanillas,
            'arFacturaOtros' => $arFacturaOtros,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/{codigoFactura}", name="transporte_movimiento_notaCredito_factura_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->retencionTransporte();
                    foreach ($arrSeleccionados AS $codigo) {
                        $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                        $arGuia->setFacturaRel($arFactura);
                        $arGuia->setEstadoFacturaGenerada(1);
                        $em->persist($arGuia);

                        $arFacturaDetalle = new TteFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setGuiaRel($arGuia);
                        $arFacturaDetalle->setVrDeclara($arGuia->getVrDeclara());
                        $arFacturaDetalle->setVrFlete($arGuia->getVrFlete());
                        $arFacturaDetalle->setVrManejo($arGuia->getVrManejo());
                        $arFacturaDetalle->setUnidades($arGuia->getUnidades());
                        $arFacturaDetalle->setPesoReal($arGuia->getPesoReal());
                        $arFacturaDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                        $arFacturaDetalle->setCodigoImpuestoRetencionFk($arrConfiguracion['codigoImpuestoRetencionTransporteFk']);
                        $em->persist($arFacturaDetalle);
                    }
                    $em->flush();
                    $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->facturaPendiente($arFactura->getCodigoClienteFk()), "Facturas");
            }
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->facturaPendiente($arFactura->getCodigoClienteFk());
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/cumplido/{codigoFactura}/{codigoFacturaPlanilla}", name="transporte_movimiento_comercial_notaCredito_detalle_adicionar_guia_cumplido")
     */
    public function detalleAdicionarGuiaCumplido(Request $request, $codigoFactura, $codigoFacturaPlanilla)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arFacturaPlanilla = NULL;
        if($codigoFacturaPlanilla != 0) {
            $arFacturaPlanilla = $em->getRepository(TteFacturaPlanilla::class)->find($codigoFacturaPlanilla);
        }

        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->retencionTransporte();
                    foreach ($arrSeleccionados AS $codigo) {
                        $arGuias = $em->getRepository(TteGuia::class)->findBy(array('codigoCumplidoFk' => $codigo, 'estadoFacturaGenerada' => 0, 'estadoFacturado' => 0));
                        if($arGuias) {
                            foreach ($arGuias as $arGuiaCumplido) {
                                $arGuia = $em->getRepository(TteGuia::class)->find($arGuiaCumplido->getCodigoGuiaPk());
                                if($arGuia->getEstadoFacturaGenerada() == 0 && $arGuia->getEstadoFacturado() == 0) {
                                    if($arFacturaPlanilla) {
                                        $arGuia->setFacturaPlanillaRel($arFacturaPlanilla);
                                    }
                                    $arGuia->setFacturaRel($arFactura);
                                    $arGuia->setEstadoFacturaGenerada(1);
                                    $em->persist($arGuia);

                                    $arFacturaDetalle = new TteFacturaDetalle();
                                    $arFacturaDetalle->setFacturaRel($arFactura);
                                    if($arFacturaPlanilla) {
                                        $arFacturaDetalle->setFacturaPlanillaRel($arFacturaPlanilla);
                                    }
                                    $arFacturaDetalle->setGuiaRel($arGuia);
                                    $arFacturaDetalle->setVrDeclara($arGuia->getVrDeclara());
                                    $arFacturaDetalle->setVrFlete($arGuia->getVrFlete());
                                    $arFacturaDetalle->setVrManejo($arGuia->getVrManejo());
                                    $arFacturaDetalle->setUnidades($arGuia->getUnidades());
                                    $arFacturaDetalle->setPesoReal($arGuia->getPesoReal());
                                    $arFacturaDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                                    $arFacturaDetalle->setCodigoImpuestoRetencionFk($arrConfiguracion['codigoImpuestoRetencionTransporteFk']);
                                    $em->persist($arFacturaDetalle);
                                }
                            }
                        }
                    }
                    $em->flush();
                    $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arCumplidos = $paginator->paginate($this->getDoctrine()->getRepository(TteCumplido::class)->factura($arFactura->getCodigoClienteFk()), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarGuiaCumplido.html.twig', [
            'arCumplidos' => $arCumplidos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/archivo/{codigoFactura}/{codigoFacturaPlanilla}", name="transporte_movimiento_comercial_notaCredito_detalle_adicionar_guia_archivo")
     */
    public function detalleAdicionarGuiaArchivo(Request $request, $codigoFactura, $codigoFacturaPlanilla)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arFacturaPlanilla = NULL;
        if($codigoFacturaPlanilla != 0) {
            $arFacturaPlanilla = $em->getRepository(TteFacturaPlanilla::class)->find($codigoFacturaPlanilla);
        }

        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class, array('attr' => ['class' => 'btn btn-sm btn-default']))
            ->add('btnCargar', SubmitType::class, array('label' => 'Cargar', 'attr' => ['class' => 'btn btn-sm btn-primary']))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnCargar')->isClicked()) {
                $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->retencionTransporte();
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
                $ruta = $arConfiguracion->getRutaTemporal();
                if (!$ruta) {
                    Mensajes::error('Debe de ingresar una ruta temporal en la configuracion general del sistema');
                }
                $form['attachment']->getData()->move($ruta, "archivo.xls");
                $rutaArchivo = $ruta . "archivo.xls";
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
                $arrCargas = [];
                $i = 0;
                if ($reader->getSheetCount() > 1) {
                    Mensajes::error('El documento debe contener solamente una hoja');
                } else {
                    foreach ($reader->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow(); // e.g. 10
                        for ($row = 2; $row <= $highestRow; ++$row) {
                            $cell = $worksheet->getCellByColumnAndRow(1, $row);
                            $arrCargas [$i]['codigoGuia'] = $cell->getValue();
                            $cell = $worksheet->getCellByColumnAndRow(2, $row);
                            $arrCargas [$i]['documento'] = $cell->getValue();
                            $i++;
                        }
                    }
                    if (count($arrCargas) > 0) {
                        foreach ($arrCargas as $arrCarga) {
                            $guia = $arrCarga['codigoGuia'] . " " .  $arrCarga['documento'];
                            $arGuia = null;
                            if($arrCarga['codigoGuia'] != "") {
                                $arGuia = $em->getRepository(TteGuia::class)->findOneBy(array(
                                    'codigoGuiaPk' => $arrCarga['codigoGuia'],
                                    'codigoClienteFk' => $arFactura->getCodigoClienteFk(),
                                    'estadoFacturaGenerada' => 0,
                                    'codigoFacturaFk' => null,
                                    'estadoAnulado' => 0));
                            } elseif ($arrCarga['documento'] != ""){
                                $arGuia = $em->getRepository(TteGuia::class)->findOneBy(array(
                                    'documentoCliente' => $arrCarga['documento'],
                                    'codigoClienteFk' => $arFactura->getCodigoClienteFk(),
                                    'estadoFacturaGenerada' => 0,
                                    'codigoFacturaFk' => null,
                                    'estadoAnulado' => 0));
                            }
                            if($arGuia) {
                                $arGuia = $em->getRepository(TteGuia::class)->find($arGuia->getCodigoGuiaPk());
                                if($arFacturaPlanilla) {
                                    $arGuia->setFacturaPlanillaRel($arFacturaPlanilla);
                                }
                                $arGuia->setFacturaRel($arFactura);
                                $arGuia->setEstadoFacturaGenerada(1);
                                $em->persist($arGuia);

                                $arFacturaDetalle = new TteFacturaDetalle();
                                $arFacturaDetalle->setFacturaRel($arFactura);
                                if($arFacturaPlanilla) {
                                    $arFacturaDetalle->setFacturaPlanillaRel($arFacturaPlanilla);
                                }
                                $arFacturaDetalle->setGuiaRel($arGuia);
                                $arFacturaDetalle->setVrDeclara($arGuia->getVrDeclara());
                                $arFacturaDetalle->setVrFlete($arGuia->getVrFlete());
                                $arFacturaDetalle->setVrManejo($arGuia->getVrManejo());
                                $arFacturaDetalle->setUnidades($arGuia->getUnidades());
                                $arFacturaDetalle->setPesoReal($arGuia->getPesoReal());
                                $arFacturaDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                                $arFacturaDetalle->setCodigoImpuestoRetencionFk($arrConfiguracion['codigoImpuestoRetencionTransporteFk']);
                                $em->persist($arFacturaDetalle);
                            }
                        }
                        $em->flush();
                        $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arCumplidos = $paginator->paginate($this->getDoctrine()->getRepository(TteCumplido::class)->factura($arFactura->getCodigoClienteFk()), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarGuiaArchivo.html.twig', [
            'arCumplidos' => $arCumplidos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/planilla/guia/{codigoFactura}/{codigoFacturaPlanilla}", name="transporte_movimiento_comercial_notaCredito_detalle_adicionar_planilla_guia")
     */
    public function detalleAdicionarGuiaPlanilla(Request $request, $codigoFactura, $codigoFacturaPlanilla)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arFacturaPlanilla = $em->getRepository(TteFacturaPlanilla::class)->find($codigoFacturaPlanilla);
        $form = $this->createFormBuilder()
            ->add('btnRetirar', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('btnRetirar')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->retirarDetallePlanilla($arrGuias, $arFactura);
                if($respuesta) {
                    $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle_adicionar_planilla_guia', [
                    'codigoFactura' => $codigoFactura,
                    'codigoFacturaPlanilla' => $codigoFacturaPlanilla]));
            }

        }
        $arFacturaDetalles = $paginator->paginate($this->getDoctrine()->getRepository(TteFacturaDetalle::class)->facturaPlanilla($codigoFacturaPlanilla), $request->query->getInt('page', 1), 1000);
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarGuiaPlanilla.html.twig', [
            'arFactura' => $arFactura,
            'arFacturaPlanilla' => $arFacturaPlanilla,
            'arFacturaDetalles' => $arFacturaDetalles,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/planilla/{codigoFactura}/{id}", name="transporte_movimiento_comercial_notaCredito_detalle_adicionar_planilla")
     */
    public function detalleAdicionarPlanilla(Request $request, $codigoFactura, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $arFacturaPlanilla = new TteFacturaPlanilla();
        if($id != 0) {
            $arFacturaPlanilla = $em->getRepository(TteFacturaPlanilla::class)->find($id);
        } else {
            $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
            $arFacturaPlanilla->setFacturaRel($arFactura);
        }
        $form = $this->createForm(FacturaPlanillaType::class, $arFacturaPlanilla);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arFacturaPlanilla);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarPlanilla.html.twig',
            ['form' => $form->createView()]);
    }



    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/nc/{codigoFactura}", name="transporte_movimiento_comercial_notaCredito_detalle_adicionar_nc_guia")
     */
    public function detalleAdicionarGuiaNc(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
                    $arrConfiguracion = $em->getRepository(TteConfiguracion::class)->retencionTransporte();
                    foreach ($arrSeleccionados AS $codigo) {
                        $arFactura->setCodigoFacturaReferenciaFk($codigo);
                        $em->persist($arFactura);
                        //$arFacturaReferencia = $em->getRepository(TteFactura::class)->find($codigo);
                        $arFacturaDetallesReferencia = $em->getRepository(TteFacturaDetalle::class)->findBy(array('codigoFacturaFk' => $codigo));
                        foreach ($arFacturaDetallesReferencia as $arFacturaDetalleReferencia) {
                            $arFacturaDetalle = new TteFacturaDetalle();
                            $arFacturaDetalle->setFacturaRel($arFactura);
                            $arFacturaDetalle->setFacturaDetalleRel($arFacturaDetalleReferencia);
                            $arFacturaDetalle->setGuiaRel($arFacturaDetalleReferencia->getGuiaRel());
                            $arFacturaDetalle->setVrDeclara($arFacturaDetalleReferencia->getVrDeclara());
                            $arFacturaDetalle->setVrFlete($arFacturaDetalleReferencia->getVrFlete());
                            $arFacturaDetalle->setVrManejo($arFacturaDetalleReferencia->getVrManejo());
                            $arFacturaDetalle->setUnidades($arFacturaDetalleReferencia->getUnidades());
                            $arFacturaDetalle->setPesoReal($arFacturaDetalleReferencia->getPesoReal());
                            $arFacturaDetalle->setPesoVolumen($arFacturaDetalleReferencia->getPesoVolumen());
                            $arFacturaDetalle->setCodigoImpuestoRetencionFk($arrConfiguracion['codigoImpuestoRetencionTransporteFk']);
                            $em->persist($arFacturaDetalle);

                            $arGuia = $em->getRepository(TteGuia::class)->find($arFacturaDetalleReferencia->getCodigoGuiaFk());
                            $arGuia->setFacturaRel($arFactura);
                            $em->persist($arGuia);
                        }
                    }
                    $em->flush();
                    $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arFacturas = $paginator->paginate($this->getDoctrine()->getRepository(TteFactura::class)->notaCredito($arFactura->getCodigoClienteFk()), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarFactura.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView()]);
    }


}
