<?php

namespace App\Controller\Transporte\Movimiento\Comercial\Factura;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaConcepto;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaDetalleConcepto;
use App\Entity\Transporte\TteFacturaDetalleReliquidar;
use App\Entity\Transporte\TteFacturaOtro;
use App\Entity\Transporte\TteFacturaPlanilla;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;

use App\Entity\Transporte\TtePrecio;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Form\Type\Transporte\FacturaDetalleConceptoType;
use App\Form\Type\Transporte\FacturaNotaCreditoType;
use App\Form\Type\Transporte\FacturaPlanillaType;
use App\Form\Type\Transporte\FacturaType;
use App\Formato\Transporte\Factura;

use App\Formato\Transporte\Factura2;
use App\Formato\Transporte\ListaFactura;
use App\Formato\Transporte\NotaCredito;
use App\General\General;
use App\Utilidades\Estandares;

use Doctrine\ORM\EntityRepository;
use PhpParser\Node\Stmt\Echo_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Utilidades\Mensajes;

class FacturaController extends ControllerListenerGeneral
{
    protected $clase = TteFactura::class;
    protected $claseNombre = "TteFactura";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Factura";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/comercial/factura/lista", name="transporte_movimiento_comercial_factura_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Facturas");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_lista'));
            }
        }

        return $this->render('transporte/movimiento/comercial/factura/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/comercial/factura/detalle/{id}", name="transporte_movimiento_comercial_factura_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($id);
        $paginator = $this->get('knp_paginator');
        $form = Estandares::botonera($arFactura->getEstadoAutorizado(), $arFactura->getEstadoAprobado(), $arFactura->getEstadoAnulado());
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnRetirarPlanilla = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnRetirarConcepto = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBotonActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        if ($arFactura->getEstadoAutorizado()) {
            $arrBtnRetirar['disabled'] = true;
            $arrBtnRetirarPlanilla['disabled'] = true;
            $arrBtnRetirarConcepto['disabled'] = true;
            $arrBotonActualizar['disabled'] = true;
        }
        if ($arFactura->getCodigoFacturaClaseFk() == 'NC') {
            $arrBotonActualizar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar)
            ->add('btnRetirarPlanilla', SubmitType::class, $arrBtnRetirarPlanilla)
            ->add('btnActualizar', SubmitType::class, $arrBotonActualizar)
            ->add('btnRetirarConcepto', SubmitType::class, $arrBtnRetirarConcepto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $codigoFactura = $em->getRepository(TteConfiguracion::class)->find(1)->getCodigoFormato();
                if ($arFactura->getCodigoFacturaTipoFk() == 'NC') {
                    $formato = new NotaCredito();
                    $formato->Generar($em, $id);
                } else {
                    if ($codigoFactura == 1) {
                        $formato = new Factura();
                        $formato->Generar($em, $id);
                    } else {
                        $formato = new Factura2();
                        $formato->Generar($em, $id);
                    }
                }
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $arrControles = $request->request->all();
                $em->getRepository(TteFactura::class)->autorizar($arFactura);
                $em->getRepository(TteFacturaDetalle::class)->actualizarDetalles($arrControles, $form, $arFactura);
                $this->getDoctrine()->getRepository(TteFactura::class)->liquidar($id);
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
                if (isset($arrControles['arrCodigo'])) {
                    $em->getRepository(TteFacturaDetalle::class)->actualizarDetalles($arrControles, $form, $arFactura);
                    $this->getDoctrine()->getRepository(TteFactura::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->retirarDetalle($arrGuias, $arFactura);
                if ($respuesta) {
                    $em->getRepository(TteFactura::class)->liquidar($id);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarConcepto')->isClicked()) {
                $arrConceptos = $request->request->get('ChkSeleccionarConcepto');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->retirarConcepto($arrConceptos, $arFactura);
                if ($respuesta) {
                    $em->getRepository(TteFactura::class)->liquidar($id);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }

            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteFacturaDetalle::class)->factura($id), "Facturas $id");
            }
            if ($form->get('btnRetirarPlanilla')->isClicked()) {
                $arrPlanillas = $request->request->get('ChkSeleccionarPlanilla');
                $this->getDoctrine()->getRepository(TteFacturaDetalle::class)->retirarPlanilla($arrPlanillas);
                return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', ['id' => $id]));
            }
        }
        $query = $this->getDoctrine()->getRepository(TteFacturaPlanilla::class)->listaFacturaDetalle($id);
        $arFacturaPlanillas = $paginator->paginate($query, $request->query->getInt('page', 1), 50);
        $query = $this->getDoctrine()->getRepository(TteFacturaDetalleConcepto::class)->listaFacturaDetalle($id);
        $arFacturaDetallesConceptos = $paginator->paginate($query, $request->query->getInt('page', 1), 50);
        $arFacturaDetalles = $this->getDoctrine()->getRepository(TteFacturaDetalle::class)->factura($id);
        return $this->render('transporte/movimiento/comercial/factura/detalle.html.twig', [
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles,
            'arFacturaPlanillas' => $arFacturaPlanillas,
            'arFacturaDetallesConceptos' => $arFacturaDetallesConceptos,
            'clase' => array('clase' => 'TteFactura', 'codigo' => $id),
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/{codigoFactura}", name="transporte_movimiento_comercial_factura_detalle_adicionar_guia")
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
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/cumplido/{codigoFactura}/{codigoFacturaPlanilla}", name="transporte_movimiento_comercial_factura_detalle_adicionar_guia_cumplido")
     */
    public function detalleAdicionarGuiaCumplido(Request $request, $codigoFactura, $codigoFacturaPlanilla)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arFacturaPlanilla = NULL;
        if ($codigoFacturaPlanilla != 0) {
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
                        if ($arGuias) {
                            foreach ($arGuias as $arGuiaCumplido) {
                                $arGuia = $em->getRepository(TteGuia::class)->find($arGuiaCumplido->getCodigoGuiaPk());
                                if ($arGuia->getEstadoFacturaGenerada() == 0 && $arGuia->getEstadoFacturado() == 0) {
                                    if ($arFacturaPlanilla) {
                                        $arGuia->setFacturaPlanillaRel($arFacturaPlanilla);
                                    }
                                    $arGuia->setFacturaRel($arFactura);
                                    $arGuia->setEstadoFacturaGenerada(1);
                                    $em->persist($arGuia);

                                    $arFacturaDetalle = new TteFacturaDetalle();
                                    $arFacturaDetalle->setFacturaRel($arFactura);
                                    if ($arFacturaPlanilla) {
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
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/archivo/{codigoFactura}/{codigoFacturaPlanilla}", name="transporte_movimiento_comercial_factura_detalle_adicionar_guia_archivo")
     */
    public function detalleAdicionarGuiaArchivo(Request $request, $codigoFactura, $codigoFacturaPlanilla)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arFacturaPlanilla = NULL;
        if ($codigoFacturaPlanilla != 0) {
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
                            $guia = $arrCarga['codigoGuia'] . " " . $arrCarga['documento'];
                            $arGuia = null;
                            if ($arrCarga['codigoGuia'] != "") {
                                $arGuia = $em->getRepository(TteGuia::class)->findOneBy(array(
                                    'codigoGuiaPk' => $arrCarga['codigoGuia'],
                                    'codigoClienteFk' => $arFactura->getCodigoClienteFk(),
                                    'estadoFacturaGenerada' => 0,
                                    'codigoFacturaFk' => null,
                                    'estadoAnulado' => 0));
                            } elseif ($arrCarga['documento'] != "") {
                                $arGuia = $em->getRepository(TteGuia::class)->findOneBy(array(
                                    'documentoCliente' => $arrCarga['documento'],
                                    'codigoClienteFk' => $arFactura->getCodigoClienteFk(),
                                    'estadoFacturaGenerada' => 0,
                                    'codigoFacturaFk' => null,
                                    'estadoAnulado' => 0));
                            }
                            if ($arGuia) {
                                $arGuia = $em->getRepository(TteGuia::class)->find($arGuia->getCodigoGuiaPk());
                                if ($arFacturaPlanilla) {
                                    $arGuia->setFacturaPlanillaRel($arFacturaPlanilla);
                                }
                                $arGuia->setFacturaRel($arFactura);
                                $arGuia->setEstadoFacturaGenerada(1);
                                $em->persist($arGuia);

                                $arFacturaDetalle = new TteFacturaDetalle();
                                $arFacturaDetalle->setFacturaRel($arFactura);
                                if ($arFacturaPlanilla) {
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
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/planilla/guia/{codigoFactura}/{codigoFacturaPlanilla}", name="transporte_movimiento_comercial_factura_detalle_adicionar_planilla_guia")
     */
    public function detalleAdicionarGuiaPlanilla(Request $request, $codigoFactura, $codigoFacturaPlanilla)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arFacturaPlanilla = $em->getRepository(TteFacturaPlanilla::class)->find($codigoFacturaPlanilla);
        $form = $this->createFormBuilder()
            ->add('btnRetirar', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                echo "<script language='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if ($form->get('btnRetirar')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->retirarDetallePlanilla($arrGuias, $arFactura);
                if ($respuesta) {
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
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/planilla/{codigoFactura}/{id}", name="transporte_movimiento_comercial_factura_detalle_adicionar_planilla")
     */
    public function detalleAdicionarPlanilla(Request $request, $codigoFactura, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFacturaPlanilla = new TteFacturaPlanilla();
        if ($id != 0) {
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
                echo "<script language='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarPlanilla.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/nuevo/{id}/{clase}", name="transporte_movimiento_comercial_factura_nuevo")
     */
    public function nuevo(Request $request, $id, $clase)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arFactura = new TteFactura();
        if ($id != 0) {
            $arFactura = $em->getRepository(TteFactura::class)->find($id);
        } else {
            $arFactura->setCodigoFacturaClaseFk($clase);
        }

        if ($clase == "FA") {
            $form = $this->createForm(FacturaType::class, $arFactura);
        }
        if ($clase == "NC") {
            $form = $this->createForm(FacturaNotaCreditoType::class, $arFactura);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if ($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if ($arCliente) {
                    $arFactura->setClienteRel($arCliente);
                    $arFactura->setOperacionRel($arCliente->getOperacionRel());
                    if ($id == 0) {
                        $arFactura->setUsuario($this->getUser()->getUsername());
                    }
                    if ($arFactura->getPlazoPago() <= 0) {
                        $arFactura->setPlazoPago($arFactura->getClienteRel()->getPlazoPago());
                    }
                    $fecha = new \DateTime('now');
                    $arFactura->setFecha($fecha);
                    $arFactura->setFechaVence($arFactura->getPlazoPago() == 0 ? $fecha : $objFunciones->sumarDiasFecha($fecha, $arFactura->getPlazoPago()));
                    $arFactura->setOperacionComercial($arFactura->getFacturaTipoRel()->getOperacionComercial());
                    $em->persist($arFactura);
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', array('id' => $arFactura->getCodigoFacturaPk())));
                }
            }
        }
        if ($clase == "FA") {
            return $this->render('transporte/movimiento/comercial/factura/nuevo.html.twig', [
                'arFactura' => $arFactura,
                'form' => $form->createView()]);
        }
        if ($clase == "NC") {
            return $this->render('transporte/movimiento/comercial/factura/nuevoNotaCredito.html.twig', [
                'arFactura' => $arFactura,
                'form' => $form->createView()]);
        }

    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/nc/{codigoFactura}", name="transporte_movimiento_comercial_factura_detalle_adicionar_nc_guia")
     */
    public function detalleAdicionarGuiaNc(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
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
        $arFacturas = $paginator->paginate($this->getDoctrine()->getRepository(TteFactura::class)->notaCredito($arFactura->getCodigoClienteFk()), $request->query->getInt('page', 1), 50);
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarFactura.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/concepto/{codigoFactura}/{codigoFacturaDetalleConcepto}", name="transporte_movimiento_comercial_factura_detalle_adicionar_concepto")
     */
    public function detalleAdicionarConcepto(Request $request, $codigoFactura, $codigoFacturaDetalleConcepto = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arFacturaDetalleConcepto = new TteFacturaDetalleConcepto();
        if ($codigoFacturaDetalleConcepto != 0) {
            $arFacturaDetalleConcepto = $em->getRepository(TteFactura::class)->find($codigoFacturaDetalleConcepto);
        }
        $form = $this->createForm(FacturaDetalleConceptoType::class, $arFacturaDetalleConcepto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arFacturaDetalleConcepto->setFacturaRel($arFactura);
            $subtotal = $arFacturaDetalleConcepto->getCantidad() * $arFacturaDetalleConcepto->getVrPrecio();
            $porcentajeIva = $arFacturaDetalleConcepto->getFacturaConceptoDetalleRel()->getImpuestoIvaVentaRel()->getPorcentaje();
            $iva = $subtotal * $porcentajeIva / 100;
            $total = $subtotal + $iva;
            $arFacturaDetalleConcepto->setPorcentajeIva($porcentajeIva);
            $arFacturaDetalleConcepto->setVrSubtotal($subtotal);
            $arFacturaDetalleConcepto->setVrIva($iva);
            $arFacturaDetalleConcepto->setVrTotal($total);
            $arFacturaDetalleConcepto->setCodigoImpuestoRetencionFk($arFacturaDetalleConcepto->getCodigoImpuestoRetencionFk());
            $arFacturaDetalleConcepto->setCodigoImpuestoIvaFk($arFacturaDetalleConcepto->getCodigoImpuestoIvaFk());
            $em->persist($arFacturaDetalleConcepto);
            $em->flush();
            $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarConcepto.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoFactura
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/movimiento/comercial/factura/detalle/reliquidar/{codigoFactura}", name="transporte_movimiento_comercial_factura_detalle_reliquidar")
     */
    public function reliquidar(Request $request, $codigoFactura)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        if($arFactura->getEstadoAutorizado()) {
            Mensajes::error("Las facturas autorizadas no se pueden reliquidar");
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            exit;
        }
        $form = $this->createFormBuilder()
            ->add('btnReliquidar', SubmitType::class, array('label' => 'Reliquidar'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->add('tipoLiquidacion', ChoiceType::class, [
                'choices' => [
                    'peso' => 'K',
                    'unidad' => 'U',
                    'adicional' => 'A',
                ]])
            ->add('cboCondicion', EntityType::class,
                ['class' => TteClienteCondicion::class,
                    'query_builder' => function (EntityRepository $er) use ($arFactura) {
                         return $er->createQueryBuilder('cc')
                             ->leftJoin('cc.condicionRel' , 'c')
                             ->andWhere('cc.codigoClienteFk = ' . $arFactura->getCodigoClienteFk());
                        },
                    'choice_label' => 'codigoCondicionFk',
                    'required' => false,
                    'empty_data' => "",
                    'placeholder' => "TODOS",
                    'data' => ""])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnReliquidar')->isClicked()) {
                $em->getRepository(TteFacturaDetalleReliquidar::class)->limpiarTabla($codigoFactura);
                $arCondicion = $arFactura->getClienteRel()->getCondicionRel();
                $arCondicionCliente = $form->get('cboCondicion')->getData();
                if($arCondicionCliente) {
                    $arCondicion = $arCondicionCliente->getCondicionRel();
                }
                $precio = $arCondicion->getCodigoPrecioFk();
                $descuentoPeso = $arCondicion->getDescuentoPeso();
                $porcentajeManejo = $arCondicion->getPorcentajeManejo();
                $manejoMinimoUnidad = $arCondicion->getManejoMinimoUnidad();
                $manejoMinimoDespacho = $arCondicion->getManejoMinimoDespacho();

                $arFacturaDetalles = $em->getRepository(TteFacturaDetalle::class)->findBy(['codigoFacturaFk' => $codigoFactura]);
                foreach ($arFacturaDetalles as $arFacturaDetalle){
                    //$arGuia = new TteGuia();
                    $arGuia = $arFacturaDetalle->getGuiaRel();
                    $tipoliquidacion = $form->get('tipoLiquidacion')->getData();
                    $flete = 0;
                    $manejo = 0;
                    $arPrecioDetalle = $em->getRepository(TtePrecioDetalle::class)->precio($precio, $arGuia->getCodigoProductoFk(), $arGuia->getCodigoCiudadOrigenFk(), $arGuia->getCodigoCiudadDestinoFk());
                    if($arPrecioDetalle) {
                        if($tipoliquidacion == "K") {
                            $peso = $arGuia->getPesoReal();
                            if($arGuia->getPesoVolumen() > $peso) {
                                $peso = $arGuia->getPesoVolumen();
                            }

                            $flete = $peso * $arPrecioDetalle->getVrPeso();
                            $descuento = $flete * $descuentoPeso / 100;
                            $flete -= $descuento;
                        }
                        if($tipoliquidacion == "U") {
                            $unidades = $arGuia->getUnidades();
                            $flete = $unidades * $arPrecioDetalle->getVrUnidad();
                        }
                    }
                    $declara = $arGuia->getVrDeclara();
                    if($declara > 0)
                    {
                        $manejo = $declara * $porcentajeManejo / 100;
                        if($manejoMinimoDespacho > $manejo) {
                            $manejo = $manejoMinimoDespacho;
                        }
                        if($manejoMinimoUnidad * $arGuia->getUnidades() > $manejo)
                        {
                            $manejo = $manejoMinimoUnidad * $arGuia->getUnidades();
                        }
                    }
                    $arFacturaDetalleReliquidar =  new TteFacturaDetalleReliquidar;
                    $arFacturaDetalleReliquidar->setFacturaDetalle($arFacturaDetalle);
                    $arFacturaDetalleReliquidar->setCodigoGuiaFk($arFacturaDetalle->getCodigoGuiaFk());
                    $arFacturaDetalleReliquidar->setCodigoFacturaFk($codigoFactura);
                    $arFacturaDetalleReliquidar->setVrFlete($arFacturaDetalle->getVrFlete());
                    $arFacturaDetalleReliquidar->setVrManejo($arFacturaDetalle->getVrManejo());
                    $arFacturaDetalleReliquidar->setVrFleteNuevo($flete);
                    $arFacturaDetalleReliquidar->setVrManejoNuevo($manejo);
                    $em->persist($arFacturaDetalleReliquidar);
                    $em->flush();
                }
            }

            if ($form->get('btnGuardar')->isClicked()) {
                $arFacturaDetallesReliqudiarActualizar = $em->getRepository(TteFacturaDetalleReliquidar::class)->findBy(array('codigoFacturaFk' => $codigoFactura));
                foreach ($arFacturaDetallesReliqudiarActualizar as $arFacturaDetalleReliqudiar) {
                    $arGuiaActualizar = $em->getRepository(TteGuia::class)->find($arFacturaDetalleReliqudiar->getCodigoGuiaFk());
                    $arGuiaActualizar->setVrFlete($arFacturaDetalleReliqudiar->getVrFleteNuevo());
                    $arGuiaActualizar->setVrManejo($arFacturaDetalleReliqudiar->getVrManejoNuevo());
                    $em->persist($arGuiaActualizar);

                    $arFacturaDetalleActualizar = $em->getRepository(TteFacturaDetalle::class)->find($arFacturaDetalleReliqudiar->getCodigoFacturaDetalleFk());
                    $arFacturaDetalleActualizar->setVrFlete($arFacturaDetalleReliqudiar->getVrFleteNuevo());
                    $arFacturaDetalleActualizar->setVrManejo($arFacturaDetalleReliqudiar->getVrManejoNuevo());
                    $em->persist($arFacturaDetalleActualizar);
                }
                $em->flush();
                $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
                $em->getRepository(TteFacturaDetalleReliquidar::class)->limpiarTabla($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arFacturaDetallesReliqudiar = $this->getDoctrine()->getRepository(TteFacturaDetalleReliquidar::class)->lista($codigoFactura);
        return $this->render('transporte/movimiento/comercial/factura/reliquidar.html.twig', [
            'arFacturaDetallesReliquidar' => $arFacturaDetallesReliqudiar,
            'form' => $form->createView()
        ]);
    }
}


