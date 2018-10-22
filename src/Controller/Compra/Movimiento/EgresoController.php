<?php

namespace App\Controller\Compra\Movimiento;

use App\Controller\BaseController;
use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoDetalle;
use App\Entity\Compra\ComProveedor;
use App\Form\Type\Compra\CompraType;
use App\Form\Type\Compra\EgresoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class EgresoController extends BaseController
{
    protected $clase = ComEgreso::class;
    protected $claseNombre = "ComEgreso";
    protected $modulo = "Compra";
    protected $funcion = "Movimiento";
    protected $grupo = "Egreso";
    protected $nombre = "Egreso";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/compra/movimiento/egreso/egreso/lista", name="compra_movimiento_egreso_egreso_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Egreso");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(ComEgreso::class)->eliminar($arrSeleccionados);
            }
        }
        return $this->render('compra/movimiento/Egreso/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/compra/movimiento/egreso/egreso/nuevo/{id}", name="compra_movimiento_egreso_egreso_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = new ComEgreso();
        if ($id != 0) {
            $arEgreso = $em->getRepository(ComEgreso::class)->find($id);
            if (!$arEgreso) {
                return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_lista'));
            }
        }
        $form = $this->createForm(EgresoType::class, $arEgreso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoProveedor = $request->request->get('txtCodigoProveedor');
                if ($txtCodigoProveedor != '') {
                    $arProveedor = $em->getRepository(ComProveedor::class)->find($txtCodigoProveedor);
                    if ($arProveedor) {
                        $arEgreso->setProveedorRel($arProveedor);
                        if ($id == 0) {
                            $arEgreso->setFecha(new \DateTime('now'));
                        }
                        $em->persist($arEgreso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $arEgreso->getCodigoEgresoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('compra/movimiento/Egreso/nuevo.html.twig', [
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/compra/movimiento/egreso/egreso/detalle/{id}", name="compra_movimiento_egreso_egreso_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = $em->getRepository(ComEgreso::class)->find($id);
        $paginator = $this->get('knp_paginator');
        $form = Estandares::botonera($arEgreso->getEstadoAutorizado(), $arEgreso->getEstadoAprobado(), $arEgreso->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arEgreso->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        }
        $form
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrIva = $request->request->get('arrIva');
            $arrValor = $request->request->get('arrValor');
            $arrCantidad = $request->request->get('arrCantidad');
            $arrDescuento = $request->request->get('arrDescuento');
//            if ($form->get('btnAutorizar')->isClicked()) {
//                $em->getRepository(ComCompra::class)->autorizar($arCompra);
//            }
//            if ($form->get('btnDesautorizar')->isClicked()) {
//                $em->getRepository(ComCompra::class)->desautorizar($arCompra);
//            }
//            if ($form->get('btnAprobar')->isClicked()) {
//                $em->getRepository(ComCompra::class)->aprobar($arCompra);
//            }
//            if ($form->get('btnImprimir')->isClicked()) {
//                $objFormatoCotizacion = new Cotizacion();
//                $objFormatoCotizacion->Generar($em, $id);
//            }
//            if ($form->get('btnAnular')->isClicked()) {
//                $respuesta = $em->getRepository(ComCompra::class)->anular($arCompra);
//                if (count($respuesta) > 0) {
//                    foreach ($respuesta as $error) {
//                        Mensajes::error($error);
//                    }
//                }
//            }
//            if ($form->get('btnActualizar')->isClicked()) {
//                $em->getRepository(ComCompra::class)->actualizar($arCompra, $arrValor, $arrCantidad, $arrIva, $arrDescuento);
//                return $this->redirect($this->generateUrl('compra_movimiento_compra_compra_detalle', ['id' => $id]));
//            }
//            if ($form->get('btnEliminar')->isClicked()) {
//                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(ComCompraDetalle::class)->eliminar($arCompra, $arrDetallesSeleccionados);
//            }
            return $this->redirect($this->generateUrl('compra_movimiento_compra_compra_detalle', ['id' => $id]));
        }
        $arEgresoDetalles = $paginator->paginate($em->getRepository(ComEgresoDetalle::class)->lista($arEgreso->getCodigoEgresoPk()), $request->query->getInt('page', 1), 30);
        return $this->render('compra/movimiento/Egreso/detalle.html.twig', [
            'arEgresoDetalles' => $arEgresoDetalles,
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/compra/movimiento/egreso/egreso/detalle/nuevo/{id}", name="compra_movimiento_egreso_egreso_detalle_nuevo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arCompra = $em->getRepository(ComCompra::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvItemNombre', $form->get('txtNombreItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrConceptos = $request->request->get('conceptoCantidad');
                if (count($arrConceptos) > 0) {
                    foreach ($arrConceptos as $codigoConcepto => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arConcepto = $em->getRepository(ComConcepto::class)->find($codigoConcepto);
                            $arCompraDetalle = new ComCompraDetalle();
                            $arCompraDetalle->setCompraRel($arCompra);
                            $arCompraDetalle->setConceptoRel($arConcepto);
                            $arCompraDetalle->setCantidad($cantidad);
                            $arCompraDetalle->setPorIva($arConcepto->getPorIva());
                            $em->persist($arCompraDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arConceptos = $paginator->paginate($em->getRepository(ComConcepto::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('compra/movimiento/detalleNuevo.html.twig', [
            'arConceptos' => $arConceptos,
            'form' => $form->createView()
        ]);
    }
}
