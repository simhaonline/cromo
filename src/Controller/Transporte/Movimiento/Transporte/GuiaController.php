<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedad;
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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GuiaController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/movimiento/transporte/guia/lista", name="transporte_movimiento_transporte_guia_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('cboGuiaTipoRel', EntityType::class, $em->getRepository(TteGuiaTipo::class)->llenarCombo())
            ->add('cboServicioRel', EntityType::class, $em->getRepository(TteServicio::class)->llenarCombo())
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroTteGuiaCodigo')))
            ->add('txtDocumento', TextType::class, array('data' => $session->get('filtroTteGuiaDocumento')))
            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroTteGuiaNumero')))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                    $session = new session;
                    $arRuta = $form->get('cboGuiaTipoRel')->getData();
                    if ($arRuta) {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', $arRuta->getCodigoGuiaTipoPk());
                    } else {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', null);
                    }
                    $arServicio = $form->get('cboServicioRel')->getData();
                    if ($arServicio) {
                        $session->set('filtroTteGuiaCodigoServicio', $arServicio->getCodigoServicioPk());
                    } else {
                        $session->set('filtroTteGuiaCodigoServicio', null);
                    }
                    $session->set('filtroTteGuiaDocumento', $form->get('txtDocumento')->getData());
                    $session->set('filtroTteGuiaNumero', $form->get('txtNumero')->getData());
                    $session->set('filtroTteGuiaCodigo', $form->get('txtCodigo')->getData());
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->createQuery($em->getRepository(TteGuia::class)->lista())->execute(), "Guias");
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
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
        }
        $arNovedades = $this->getDoctrine()->getRepository(TteNovedad::class)->guia($id);
        $arDespachoDetalles = $this->getDoctrine()->getRepository(TteDespachoDetalle::class)->guia($id);
        return $this->render('transporte/movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'arNovedades' => $arNovedades,
            'arDespachoDetalles' => $arDespachoDetalles,
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
            $em->persist($arNovedad);
            $arGuia = $em->getRepository(TteGuia::class)->find($arNovedad->getCodigoGuiaFk());
            $arGuia->setEstadoNovedad(0);
            $em->persist($arGuia);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('transporte/movimiento/transporte/guia/novedadSolucion.html.twig', array (
        'form' => $form->createView()));
    }
}

