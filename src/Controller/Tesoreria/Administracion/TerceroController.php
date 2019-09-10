<?php

namespace App\Controller\Tesoreria\Administracion;

use App\Controller\BaseController;
use App\Entity\Compra\ComProveedor;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Compra\ProveedorType;
use App\Form\Type\Tesoreria\TerceroType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TerceroController extends BaseController
{
    protected $clase = TesTercero::class;
    protected $claseNombre = "TesTercero";
    protected $modulo = "Tesoreria";
    protected $funcion = "Administracion";
    protected $grupo = "Tercero";
    protected $nombre = "Tercero";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/administracion/tercero/tercero/lista", name="tesoreria_administracion_tercero_tercero_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {

            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Tercero");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeccion = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Tesoreria\TesTercero')->eliminar($arrSeccion);
            }
        }
        return $this->render('tesoreria/administracion/tercero/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/compra/administracion/proveedor/proveedor/nuevo/{id}", name="tesoreria_administracion_tercero_tercero_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTercero = new TesTercero();
        if ($id != 0) {
            $arTercero = $em->getRepository(TesTercero::class)->find($id);
            if (!$arTercero) {
                return $this->redirect($this->generateUrl('tesoreria_administracion_tercero_tercero_lista'));
            }
        }
        $form = $this->createForm(TerceroType::class, $arTercero);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arTercero = $form->getData();
                $em->persist($arTercero);
                $em->flush();
                return $this->redirect($this->generateUrl('tesoreria_administracion_tercero_tercero_lista'));
            }
        }
        return $this->render('tesoreria/administracion/tercero/nuevo.html.twig', [
            'arTercero' => $arTercero,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/compra/administracion/proveedor/proveedor/detalle/{id}", name="tesoreria_administracion_tercero_tercero_detalle")
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
