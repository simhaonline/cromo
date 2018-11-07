<?php

namespace App\Controller\Inventario\Movimiento\Extranjero;

use App\Controller\Estructura\ControllerListenerPermisosFunciones;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvImportacion;
use App\Entity\Inventario\InvImportacionDetalle;
use App\Entity\Inventario\InvImportacionTipo;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Entity\Inventario\InvTercero;
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\ImportacionType;

class ImportacionController extends ControllerListenerPermisosFunciones
{
    protected $class= InvImportacion::class;
    protected $claseNombre = "InvImportacion";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Extranjero";
    protected $nombre = "Importacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/movimiento/extranjero/importacion/lista", name="inventario_movimiento_extranjero_importacion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('cboImportacionTipo', EntityType::class, $em->getRepository(InvImportacionTipo::class)->llenarCombo())
            ->add('numero', TextType::class, array('data' => $session->get('filtroInvImportacionImportacionNumero')))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                    $session->set('filtroInvImportacionImportacionNumero', $form->get('numero')->getData());
                    $session->set('filtroInvCodigoTercero', $form->get('txtCodigoTercero')->getData());
                    $importacionTipo = $form->get('cboImportacionTipo')->getData();
                    if($importacionTipo != ''){
                        $session->set('filtroInvImportacionTipo', $form->get('cboImportacionTipo')->getData()->getCodigoImportacionTipoPk());
                    } else {
                        $session->set('filtroInvImportacionTipo', null);
                    }
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->createQuery($em->getRepository(InvImportacion::class)->lista())->execute(), "Importacions");
                }
            }
        }
        $arImportacions = $paginator->paginate($this->getDoctrine()->getRepository(InvImportacion::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/extranjero/importacion/lista.html.twig', [
            'arImportacions' => $arImportacions,
            'form' => $form->createView()]);
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
                        $arImportacion->setTerceroRel($arTercero);
                        $arImportacion->setFecha(new \DateTime('now'));
                        if ($id == 0) {
                            $arImportacion->setFecha(new \DateTime('now'));
                            $arImportacion->setUsuario($this->getUser()->getUserName());
                        }
                        $em->persist($arImportacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_movimiento_extranjero_importacion_detalle', ['id' => $arImportacion->getCodigoImportacionPk()]));
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
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
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
        if ($arImportacion->getEstadoAutorizado()) {
            $arrBtnActualizarDetalle['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
        }
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
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
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                $em->getRepository(InvImportacion::class)->actualizarDetalles($id, $arrControles);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_extranjero_importacion_detalle', ['id' => $id]));
        }
        $arImportacionDetalles = $paginator->paginate($em->getRepository(InvImportacionDetalle::class)->importacion($id), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/extranjero/importacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arImportacionDetalles' => $arImportacionDetalles,
            'arImportacion' => $arImportacion
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoImportacion
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
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
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombreItem')->getData());
                $session->set('filtroInvBuscarReferenciaNombre', $form->get('txtReferenciaItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $precioVenta = $em->getRepository(InvPrecioDetalle::class)->obtenerPrecio($arImportacion->getTerceroRel()->getCodigoPrecioVentaFk(), $codigoItem);
                            $arImportacionDetalle = new InvImportacionDetalle();
                            $arImportacionDetalle->setImportacionRel($arImportacion);
                            $arImportacionDetalle->setItemRel($arItem);
                            $arImportacionDetalle->setCantidad($cantidad);
                            $arImportacionDetalle->setCantidadPendiente($cantidad);
                            $arImportacionDetalle->setVrPrecio(is_array($precioVenta) ? $precioVenta['precio'] : 0);
                            $arImportacionDetalle->setPorcentajeIva($arItem->getPorcentajeIva());
                            $em->persist($arImportacionDetalle);
                        }
                    }
                    $em->flush();
                    $em->getRepository(InvImportacion::class)->liquidar($codigoImportacion);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/movimiento/extranjero/importacion/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

}
