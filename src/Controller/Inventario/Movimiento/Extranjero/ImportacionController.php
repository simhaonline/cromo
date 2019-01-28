<?php

namespace App\Controller\Inventario\Movimiento\Extranjero;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvImportacionCosto;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvImportacion;
use App\Entity\Inventario\InvImportacionDetalle;
use App\Entity\Inventario\InvImportacionTipo;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\ImportacionCostoType;
use App\Formato\Inventario\Importacion;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\Inventario\ImportacionType;

class ImportacionController extends ControllerListenerGeneral
{
    protected $class = InvImportacion::class;
    protected $claseNombre = "InvImportacion";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Extranjero";
    protected $nombre = "Importacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/extranjero/importacion/lista", name="inventario_movimiento_extranjero_importacion_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Importacion");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvImportacion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        }
        return $this->render('inventario/movimiento/extranjero/importacion/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/extranjero/importacion/nuevo/{id}", name="inventario_movimiento_extranjero_importacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arImportacion = new InvImportacion();
        if ($id != 0) {
            $arImportacion = $em->getRepository(InvImportacion::class)->find($id);
        }
        $form = $this->createForm(ImportacionType::class, $arImportacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        if ($arImportacion->getImportacionTipoRel()) {
                            $arImportacion->setTerceroRel($arTercero);
                            $arImportacion->setFecha(new \DateTime('now'));
                            if ($id == 0) {
                                $arImportacion->setFecha(new \DateTime('now'));
                                $arImportacion->setUsuario($this->getUser()->getUserName());
                            }
                            $em->persist($arImportacion);
                            $em->flush();
                            return $this->redirect($this->generateUrl('inventario_movimiento_extranjero_importacion_detalle', ['id' => $arImportacion->getCodigoImportacionPk()]));
                        } else {
                            Mensajes::error('Debe seleccionar un tipo de importación');
                        }
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('inventario/movimiento/extranjero/importacion/nuevo.html.twig', [
            'form' => $form->createView(),
            'arImportacion' => $arImportacion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/extranjero/importacion/detalle/{id}", name="inventario_movimiento_extranjero_importacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arImportacion = $em->getRepository(InvImportacion::class)->find($id);
        $form = Estandares::botonera($arImportacion->getEstadoAutorizado(), $arImportacion->getEstadoAprobado(), $arImportacion->getEstadoAnulado());
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnEliminarCosto = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arImportacion->getEstadoAutorizado()) {
            $arrBtnActualizarDetalle['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
        }
        if($arImportacion->getEstadoContabilizado()) {
            $arrBtnEliminarCosto['disabled'] = true;
        }
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->add('btnEliminarCosto', SubmitType::class, $arrBtnEliminarCosto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvImportacion::class)->actualizarDetalles($id, $arrControles);
                $em->getRepository(InvImportacion::class)->autorizar($arImportacion);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvImportacion::class)->desautorizar($arImportacion);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatoimportacion = new Importacion();
                $objFormatoimportacion->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvImportacion::class)->aprobar($arImportacion);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(InvImportacion::class)->anular($arImportacion);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvImportacionDetalle::class)->eliminar($arImportacion, $arrDetallesSeleccionados);
                $em->getRepository(InvImportacion::class)->liquidar($id);
            }
            if ($form->get('btnEliminarCosto')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionarCosto');
                $em->getRepository(InvImportacionCosto::class)->eliminar($arImportacion, $arrDetallesSeleccionados);
                $em->getRepository(InvImportacion::class)->liquidar($id);
            }
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                $em->getRepository(InvImportacion::class)->actualizarDetalles($id, $arrControles);
                $em->getRepository(InvImportacion::class)->liquidar($id);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_extranjero_importacion_detalle', ['id' => $id]));
        }
        $arImportacionDetalles = $paginator->paginate($em->getRepository(InvImportacionDetalle::class)->importacion($id), $request->query->getInt('page', 1), 10);
        $arImportacionCostos = $paginator->paginate($em->getRepository(InvImportacionCosto::class)->lista($id), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/extranjero/importacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arImportacionDetalles' => $arImportacionDetalles,
            'arImportacion' => $arImportacion,
            'arImportacionCostos' => $arImportacionCostos,
            'clase' => array('clase'=>'InvImportacion', 'codigo' => $id),
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoImportacion
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/extranjero/importacion/detalle/nuevo/{codigoImportacion}", name="inventario_movimiento_extranjero_importacion_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoImportacion)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arImportacion = $em->getRepository(InvImportacion::class)->find($codigoImportacion);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('txtReferenciaItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardarCerrar', SubmitType::class, ['label' => 'Guardar y cerrar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
                $session->set('filtroInvBuscarItemReferencia', $form->get('txtReferenciaItem')->getData());
            }
            if ($form->get('btnGuardarCerrar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $arImportacionDetalle = new InvImportacionDetalle();
                            $arImportacionDetalle->setImportacionRel($arImportacion);
                            $arImportacionDetalle->setItemRel($arItem);
                            $arImportacionDetalle->setCantidad($cantidad);
                            $arImportacionDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arImportacionDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvImportacion::class)->liquidar($codigoImportacion);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $arImportacionDetalle = new InvImportacionDetalle();
                            $arImportacionDetalle->setImportacionRel($arImportacion);
                            $arImportacionDetalle->setItemRel($arItem);
                            $arImportacionDetalle->setCantidad($cantidad);
                            $arImportacionDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arImportacionDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvImportacion::class)->liquidar($codigoImportacion);
                    echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/extranjero/importacion/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $codigoImportacion
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/inventario/movimiento/extranjero/importacion/costo/nuevo/{id}/{codigoImportacion}", name="inventario_movimiento_extranjero_importacion_costo_nuevo")
     */
    public function costoNuevo(Request $request, $id, $codigoImportacion)
    {
        $em = $this->getDoctrine()->getManager();
        $arImportacionCosto = new InvImportacionCosto();
        if ($id != 0) {
            $arImportacionCosto = $em->getRepository(InvImportacionCosto::class)->find($id);
        }
        $form = $this->createForm(ImportacionCostoType::class, $arImportacionCosto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arImportacionCosto->setTerceroRel($arTercero);
                    }
                }
                $arImportacionCosto->setImportacionRel($em->find(InvImportacion::class, $codigoImportacion));
                $em->persist($arImportacionCosto);
                $em->flush();
                $em->getRepository(InvImportacion::class)->liquidar($codigoImportacion);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('inventario/movimiento/extranjero/importacion/costoNuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
