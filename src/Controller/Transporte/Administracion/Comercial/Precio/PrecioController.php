<?php

namespace App\Controller\Transporte\Administracion\Comercial\Precio;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteProducto;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Transporte\TtePrecio;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Form\Type\Transporte\PrecioDetalleType;
use App\Form\Type\Transporte\PrecioType;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PrecioController extends ControllerListenerGeneral
{
    protected $class = TtePrecio::class;
    protected $claseNombre = "TtePrecio";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Comercial";
    protected $nombre = "Precio";

    /**
     * @Route("/transporte/administracion/comercial/precio/lista", name="transporte_administracion_comercial_precio_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteNombrePrecio')])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTteNombrePrecio', $form->get('txtNombre')->getData());
        }
        if ($form->get('btnEliminar')->isClicked()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $em->getRepository(TtePrecio::class)->eliminar($arrSeleccionados);
            return $this->redirect($this->generateUrl('transporte_administracion_comercial_precio_lista'));
        }
        $arPrecios = $paginator->paginate($em->getRepository(TtePrecio::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('transporte/administracion/comercial/precio/lista.html.twig',
            ['arPrecios' => $arPrecios,
                'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/administracion/comercial/precio/nuevo/{id}", name="transporte_administracion_comercial_precio_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecio = new TtePrecio();
        if ($id != '0') {
            $arPrecio = $em->getRepository(TtePrecio::class)->find($id);
            if (!$arPrecio) {
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_precio_lista'));
            }
        } else {
            $arPrecio->setFechaVence(new \DateTime('now'));
        }
        $form = $this->createForm(PrecioType::class, $arPrecio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arPrecio);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_precio_detalle', ['id' => $arPrecio->getCodigoPrecioPk()]));
            }
        }
        return $this->render('transporte/administracion/comercial/precio/nuevo.html.twig', [
            'arPrecio' => $arPrecio,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/administracion/comercial/precio/detalle/{id}", name="transporte_administracion_comercial_precio_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arPrecio = $em->getRepository(TtePrecio::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnEliminarDetalle', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnEliminarDetalle')->isClicked()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $em->getRepository(TtePrecioDetalle::class)->eliminar($arrSeleccionados);
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TtePrecioDetalle::class)->lista($id), "Precio detalle $id");
        }
        $arPrecioDetalles = $paginator->paginate($em->getRepository(TtePrecioDetalle::class)->lista($id), $request->query->getInt('page', 1), 70);
        return $this->render('transporte/administracion/comercial/precio/detalle.html.twig', array(
            'arPrecio' => $arPrecio,
            'arPrecioDetalles' => $arPrecioDetalles,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $codigoPrecio
     * @param $id
     * @return Response
     * @Route("/transporte/administracion/comercial/precio/detalle/nuevo/{codigoPrecio}/{id}", name="transporte_administracion_comercial_precio_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoPrecio, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecioDetalle = new TtePrecioDetalle();
        $arPrecio = $em->getRepository(TtePrecio::class)->find($codigoPrecio);
        if ($id != '0') {
            $arPrecioDetalle = $em->getRepository(TtePrecioDetalle::class)->find($id);
        }
        $form = $this->createForm(PrecioDetalleType::class, $arPrecioDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arPrecioDetalleExistente = $em->getRepository(TtePrecioDetalle::class)
                ->findBy(['ciudadOrigenRel' => $arPrecioDetalle->getCiudadOrigenRel(), 'ciudadDestinoRel' => $arPrecioDetalle->getCiudadDestinoRel(), 'productoRel' => $arPrecioDetalle->getProductoRel()]);
            if (!$arPrecioDetalleExistente) {
                if ($form->get('guardar')->isClicked()) {
                    $arPrecioDetalle->setPrecioRel($arPrecio);
                    $em->persist($arPrecioDetalle);
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                Mensajes::error('Ya existe un producto con la misma ciudad de origen y destino');
            }
        }
        return $this->render('transporte/administracion/comercial/precio/detalleNuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @Route("/transporte/administracion/comercial/precio/importar/{codigoPrecio}", name="transporte_administracion_comercial_precio_importar")
     */
    public function importarPrecios(Request $request, $codigoPrecio)
    {
        $em = $this->getDoctrine()->getManager();
        $arPrecio = $em->getRepository(TtePrecio::class)->find($codigoPrecio);
        $form = $this->createFormBuilder()
            ->add('flArchivo', FileType::class)
            ->add('btnImportarPrecio', SubmitType::class, ['label' => 'Importar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImportarPrecio')->isClicked()) {
                $ruta = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
                //$ruta = "/var/www/temporal/";
                if (!$ruta) {
                    Mensajes::error('Debe de ingresar una ruta temporal en la configuracion general del sistema');
                    echo "<script language='Javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
                $form['flArchivo']->getData()->move($ruta, "archivo.xls");
                $rutaArchivo = $ruta . "archivo.xls";
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
                $arrCargas = [];
                $i = 0;
                if ($reader->getSheetCount() > 1) {
                    Mensajes::error('El documento debe contener solamente una hoja');
                    echo "<script language='Javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    foreach ($reader->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        for ($row = 2; $row <= $highestRow; ++$row) {
                            $cell = $worksheet->getCellByColumnAndRow(3, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['codigoCiudadOrigenFk'] = $cell->getValue();
                            }
                            $cell = $worksheet->getCellByColumnAndRow(5, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['codigoCiudadDestinoFk'] = $cell->getValue();
                            }
                            $cell = $worksheet->getCellByColumnAndRow(7, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['codigoProductoFk'] = $cell->getValue();
                            }
                            $cell = $worksheet->getCellByColumnAndRow(8, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['vrPeso'] = $cell->getValue();
                            } else {
                                $arrCargas [$i]['vrPeso'] = 0;
                            }
                            $cell = $worksheet->getCellByColumnAndRow(9, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['vrUnidad'] = $cell->getValue();
                            } else {
                                $arrCargas [$i]['vrUnidad'] = 0;
                            }
                            $cell = $worksheet->getCellByColumnAndRow(10, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['pesoTope'] = $cell->getValue();
                            } else {
                                $arrCargas [$i]['pesoTope'] = 0;
                            }
                            $cell = $worksheet->getCellByColumnAndRow(11, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['vrPesoTope'] = $cell->getValue();
                            } else {
                                $arrCargas [$i]['vrPesoTope'] = 0;
                            }
                            $cell = $worksheet->getCellByColumnAndRow(12, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['vrPesoTopeAdicional'] = $cell->getValue();
                            } else {
                                $arrCargas [$i]['vrPesoTopeAdicional'] = 0;
                            }
                            $cell = $worksheet->getCellByColumnAndRow(13, $row);
                            if ($cell->getValue() != '') {
                                $arrCargas [$i]['minimo'] = $cell->getValue();
                            } else {
                                $arrCargas [$i]['minimo'] = 0;
                            }
                            $i++;
                        }
                    }
                    //leercargas
                    foreach ($arrCargas as $arrCarga) {
                        $arPrecioDetalle = $em->getRepository(TtePrecioDetalle::class)->findOneBy(array('codigoPrecioFk' => $codigoPrecio, 'codigoCiudadOrigenFk' => $arrCarga['codigoCiudadOrigenFk'], 'codigoCiudadDestinoFk' => $arrCarga['codigoCiudadDestinoFk'], 'codigoProductoFk' => $arrCarga['codigoProductoFk']));
                        if ($arPrecioDetalle) {
                            $arPrecioDetalle->setVrPeso($arrCarga['vrPeso']);
                            $arPrecioDetalle->setVrUnidad($arrCarga['vrUnidad']);
                            $arPrecioDetalle->setPesoTope($arrCarga['pesoTope']);
                            $arPrecioDetalle->setVrPesoTope($arrCarga['vrPesoTope']);
                            $arPrecioDetalle->setVrPesoTopeAdicional($arrCarga['vrPesoTopeAdicional']);
                            $arPrecioDetalle->setMinimo($arrCarga['minimo']);
                            $em->persist($arPrecioDetalle);
                        } else {
                            if ($arrCarga['codigoCiudadOrigenFk'] && $arrCarga['codigoCiudadDestinoFk'] && $arrCarga['codigoProductoFk']) {
                                $arCiudadOrigen = $em->getRepository(TteCiudad::class)->find($arrCarga['codigoCiudadOrigenFk']);
                                $arCiudadDestino = $em->getRepository(TteCiudad::class)->find($arrCarga['codigoCiudadDestinoFk']);
                                $arProducto = $em->getRepository(TteProducto::class)->find($arrCarga['codigoProductoFk']);
                                if ($arCiudadOrigen && $arCiudadDestino && $arProducto) {
                                    $arPrecioDetalleNuevo = new TtePrecioDetalle();
                                    $arPrecioDetalleNuevo->setPrecioRel($arPrecio);
                                    $arPrecioDetalleNuevo->setCiudadOrigenRel($arCiudadOrigen);
                                    $arPrecioDetalleNuevo->setCiudadDestinoRel($arCiudadDestino);
                                    $arPrecioDetalleNuevo->setProductoRel($arProducto);
                                    $arPrecioDetalleNuevo->setVrPeso($arrCarga['vrPeso']);
                                    $arPrecioDetalleNuevo->setVrUnidad($arrCarga['vrUnidad']);
                                    $arPrecioDetalleNuevo->setPesoTope($arrCarga['pesoTope']);
                                    $arPrecioDetalleNuevo->setVrPesoTope($arrCarga['vrPesoTope']);
                                    $arPrecioDetalleNuevo->setVrPesoTopeAdicional($arrCarga['vrPesoTopeAdicional']);
                                    $arPrecioDetalleNuevo->setMinimo($arrCarga['minimo']);
                                    $em->persist($arPrecioDetalleNuevo);
                                }
                            }
                        }
                    }
                    $em->flush();
                    echo "<script language='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('transporte/administracion/comercial/precio/importarPreciosArchivo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

