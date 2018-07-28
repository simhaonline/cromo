<?php

namespace App\Controller\Transporte\Movimiento\Comercial\Factura;

use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaOtro;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Form\Type\Transporte\FacturaType;
use App\Formato\Transporte\Factura;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FacturaController extends Controller
{
   /**
    * @Route("/transporte/movimiento/comercial/factura/lista", name="transporte_movimiento_comercial_factura_lista")
    */    
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('txtNumero', TextType::class, ['required' => false, 'data' => $session->get('filtroTteFacturaNumero')])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteFacturaEstadoAprobado'), 'required' => false])
            ->add('chkEstadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteFacturaEstadoAnulado'), 'required' => false])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteFacturaEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
                $session->set('filtroTteFacturaEstadoAnulado', $form->get('chkEstadoAnulado')->getData());
                $session->set('filtroTteFacturaNumero', $form->get('txtNumero')->getData());

                if ($form->get('txtCodigoCliente')->getData() != '') {
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroTteCodigoCliente', null);
                    $session->set('filtroTteNombreCliente', null);
                }
            }
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteFactura::class)->eliminar($arrSeleccionados);
            }
        }
        $arFacturas = $paginator->paginate($this->getDoctrine()->getRepository(TteFactura::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/comercial/factura/lista.html.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView() ]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/{id}", name="transporte_movimiento_comercial_factura_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($id);
        $paginator  = $this->get('knp_paginator');
        $form = Estandares::botonera($arFactura->getEstadoAutorizado(),$arFactura->getEstadoAprobado(),$arFactura->getEstadoAnulado());
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBotonActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($arFactura->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
            $arrBotonActualizar['disabled'] = true;
        }
        if($arFactura->getCodigoFacturaClaseFk() == 'NC') {
            $arrBotonActualizar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar)
            ->add('btnActualizar', SubmitType::class, $arrBotonActualizar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Factura();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
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


        }
        $query = $this->getDoctrine()->getRepository(TteFacturaPlanilla::class)->listaFacturaDetalle($id);
        $arFacturaPlanillas = $paginator->paginate($query, $request->query->getInt('page', 1),10);

        $query = $this->getDoctrine()->getRepository(TteFacturaOtro::class)->listaFacturaDetalle($id);
        $arFacturaOtros = $paginator->paginate($query, $request->query->getInt('page', 1),10);

        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->factura($id);
        $arFacturaDetalles = $this->getDoctrine()->getRepository(TteFacturaDetalle::class)->factura($id);
        return $this->render('transporte/movimiento/comercial/factura/detalle.html.twig', [
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles,
            'arFacturaPlanillas' => $arFacturaPlanillas,
            'arFacturaOtros' => $arFacturaOtros,
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
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if (count($arrSeleccionados) > 0) {
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
                        $em->persist($arFacturaDetalle);
                    }
                    $em->flush();
                    $em->getRepository(TteFactura::class)->liquidar($codigoFactura);
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->facturaPendiente($arFactura->getCodigoClienteFk());
        return $this->render('transporte/movimiento/comercial/factura/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/nuevo/{id}/{clase}", name="transporte_movimiento_comercial_factura_nuevo")
     */
    public function nuevo(Request $request, $id, $clase)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arFactura = new TteFactura();
        if($id != 0) {
            $arFactura = $em->getRepository(TteFactura::class)->find($id);
        } else {
            $arFactura->setCodigoFacturaClaseFk($clase);
        }
        $form = $this->createForm(FacturaType::class, $arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arFactura->setClienteRel($arCliente);
                    if ($arFactura->getPlazoPago() <= 0) {
                        $arFactura->setPlazoPago($arFactura->getClienteRel()->getPlazoPago());
                    }
                    $fecha = new \DateTime('now');
                    $arFactura->setFecha($fecha);
                    $arFactura->setFechaVence($arFactura->getPlazoPago() == 0 ? $fecha : $objFunciones->sumarDiasFecha($fecha,$arFactura->getPlazoPago()));
                    $em->persist($arFactura);
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_movimiento_comercial_factura_detalle', array('id' => $arFactura->getCodigoFacturaPk())));
                }
            }
        }
        return $this->render('transporte/movimiento/comercial/factura/nuevo.html.twig', [
            'arFactura' => $arFactura,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/comercial/factura/detalle/adicionar/guia/nc/{codigoFactura}", name="transporte_movimiento_comercial_factura_detalle_adicionar_nc_guia")
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
                    foreach ($arrSeleccionados AS $codigo) {
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

