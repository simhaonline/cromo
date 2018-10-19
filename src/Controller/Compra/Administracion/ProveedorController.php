<?php

namespace App\Controller\Compra\Administracion;

use App\Controller\BaseController;
use App\Entity\Compra\ComProveedor;
use App\Form\Type\Compra\ProveedorType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProveedorController extends BaseController
{
    protected $clase = ComProveedor::class;
    protected $claseNombre = "ComProveedor";
    protected $modulo = "Compra";
    protected $funcion = "Administracion";
    protected $grupo = "Proveedor";
    protected $nombre = "Proveedor";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/compra/administracion/proveedor/proveedor/lista", name="compra_administracion_proveedor_proveedor_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Compras");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        return $this->render('compra/administracion/proveedor/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/compra/administracion/proveedor/proveedor/nuevo/{id}", name="compra_administracion_proveedor_proveedor_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arProveedor = new ComProveedor();
        if ($id != 0) {
            $arCompra = $em->getRepository(ComProveedor::class)->find($id);
            if (!$arCompra) {
                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_lista'));
            }
        }
        $form = $this->createForm(ProveedorType::class, $arProveedor);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arProveedor = $form->getData();
//                dump($arProveedor,$arrControles);
//                exit();
                $nombreCorto = $form->get('nombre1')->getData();
                $arProveedor->setNombreCorto($nombreCorto);
                $em->persist($arProveedor);
                $em->flush();
                return $this->redirect($this->generateUrl('compra_administracion_proveedor_proveedor_lista'));
            }
        }
        return $this->render('compra/administracion/proveedor/nuevo.html.twig', [
            'arProveedor' => $arProveedor,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/compra/administracion/proveedor/proveedor/detalle/{id}", name="compra_administracion_proveedor_proveedor_detalle")
     */
    public function detalle(Request $request, $id)
    {
//        $em = $this->getDoctrine()->getManager();
//        $arCotizacion = $em->getRepository(ComProveedor::class)->find($id);
//        $paginator = $this->get('knp_paginator');
//        $form = Estandares::botonera($arCotizacion->getEstadoAutorizado(), $arCotizacion->getEstadoAprobado(), $arCotizacion->getEstadoAnulado());
//        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
//        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
//        if ($arCotizacion->getEstadoAutorizado()) {
//            $arrBtnEliminar['disabled'] = true;
//            $arrBtnActualizar['disabled'] = true;
//        }
//        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
//            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $arrIva = $request->request->get('arrIva');
//            $arrValor = $request->request->get('arrValor');
//            $arrCantidad = $request->request->get('arrCantidad');
//            $arrDescuento = $request->request->get('arrDescuento');
//            if ($form->get('btnAutorizar')->isClicked()) {
//                $em->getRepository(ComProveedor::class)->autorizar($arCotizacion);
//            }
//            if ($form->get('btnDesautorizar')->isClicked()) {
//                $em->getRepository(ComProveedor::class)->desautorizar($arCotizacion);
//            }
//            if ($form->get('btnAprobar')->isClicked()) {
//                $em->getRepository(ComProveedor::class)->aprobar($arCotizacion);
//            }
//            if ($form->get('btnImprimir')->isClicked()) {
//                $objFormatoCotizacion = new ComProveedor();
//                $objFormatoCotizacion->Generar($em, $id);
//            }
//            if ($form->get('btnAnular')->isClicked()) {
//                $respuesta = $em->getRepository(ComProveedor::class)->anular($arCotizacion);
//                if (count($respuesta) > 0) {
//                    foreach ($respuesta as $error) {
//                        Mensajes::error($error);
//                    }
//                }
//            }
//            if ($form->get('btnActualizar')->isClicked()) {
//                $em->getRepository(ComProveedor::class)->actualizar($arCotizacion, $arrValor, $arrCantidad, $arrIva, $arrDescuento);
//                return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_detalle', ['id' => $id]));
//            }
//            if ($form->get('btnEliminar')->isClicked()) {
//                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(ComProveedor::class)->eliminar($arCotizacion, $arrDetallesSeleccionados);
//            }
//            return $this->redirect($this->generateUrl('inventario_movimiento_comercial_cotizacion_detalle', ['id' => $id]));
//        }
//        $arCotizacionDetalles = $paginator->paginate($em->getRepository(ComProveedor::class)->lista($arCotizacion->getCodigoCotizacionPk()), $request->query->getInt('page', 1), 30);
//        return $this->render('inventario/movimiento/comercial/cotizacion/detalle.html.twig', [
//            'arCotizacionDetalles' => $arCotizacionDetalles,
//            'arCotizacion' => $arCotizacion,
//            'form' => $form->createView()
//        ]);
    }

}
