<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Form\Type\Transporte\GuiaType;
use App\Form\Type\Transporte\NovedadType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class GuiaController extends Controller
{
   /**
    * @Route("/tte/mto/transporte/guia/lista", name="tte_mto_transporte_guia_lista")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                }
            }
        }
        $query = $this->getDoctrine()->getRepository(TteGuia::class)->lista();
        $arGuias = $paginator->paginate($query, $request->query->getInt('page', 1),10);
        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/transporte/guia/nuevo/{codigoGuia}", name="tte_mto_transporte_guia_nuevo")
     */
    public function nuevo(Request $request, $codigoGuia)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = new TteGuia();
        if($codigoGuia == 0) {
            $arGuia->setFechaIngreso(new \DateTime('now'));
        }

        $form = $this->createForm(GuiaType::class, $arGuia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arGuia = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if ($arCliente) {
                    $error = "";
                    if($arGuia->getGuiaTipoRel()->getExigeNumero()) {
                        if($arGuia->getNumero() == "") {
                            $error = "Debe diligenciar el numero de la guia";
                        }
                    } else {
                        $arGuia->setNumero(NULL);
                    }
                    if($error == "") {
                        $arGuia->setClienteRel($arCliente);
                        $arGuia->setOperacionIngresoRel($this->getUser()->getOperacionRel());
                        $arGuia->setOperacionCargoRel($this->getUser()->getOperacionRel());
                        $arGuia->setFactura($arGuia->getGuiaTipoRel()->getFactura());
                        $arGuia->setCiudadOrigenRel($this->getUser()->getOperacionRel()->getCiudadRel());
                        $em->persist($arGuia);
                        $em->flush();
                        if ($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('tte_mto_transporte_guia_nuevo', array('codigoGuia' => 0)));
                        } else {
                            return $this->redirect($this->generateUrl('tte_mto_transporte_guia_lista'));
                        }
                    }
                }
            }

        }
        return $this->render('transporte/movimiento/transporte/guia/nuevo.html.twig', ['arGuia' => $arGuia,'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/transporte/guia/detalle/{codigoGuia}", name="tte_mto_transporte_guia_detalle")
     */
    public function detalle(Request $request, $codigoGuia)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $form = $this->createFormBuilder()
            ->add('btnRetirarNovedad', SubmitType::class, array('label' => 'Retirar'))
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $respuesta = $em->getRepository(TteGuia::class)->imprimir($codigoGuia);
                if($respuesta) {
                    $em->flush();
                    return $this->redirect($this->generateUrl('tte_mto_transporte_guia_detalle', array('codigoGuia' => $codigoGuia)));
                    //$formato = new \App\Formato\TteDespacho();
                    //$formato->Generar($em, $codigoGuia);
                }

            }
        }
        $arNovedades = $this->getDoctrine()->getRepository(TteNovedad::class)->guia($codigoGuia);
        return $this->render('transporte/movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'arNovedades' => $arNovedades,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/tte/mto/trasnporte/guia/detalle/adicionar/novedad/{codigoGuia}/{codigoNovedad}", name="tte_mto_transporte_guia_detalle_adicionar_novedad")
     */
    public function detalleAdicionarNovedad(Request $request, $codigoGuia, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $arNovedad = new TteNovedad();
        if($codigoNovedad == 0) {
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
            if($codigoNovedad == 0) {
                $arNovedad->setFechaRegistro(new \DateTime('now'));
                $arNovedad->setFechaAtendido(new \DateTime('now'));
                $arNovedad->setFechaSolucion(new \DateTime('now'));
            }

            $em->persist($arNovedad);
            $em->flush();

            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }

        return $this->render('transporte/movimiento/transporte/guia/detalleAdicionarNovedad.html.twig', [
            'arGuia' => $arGuia,
            'form' => $form->createView()]);
    }

    private function filtrar($form)
    {
        $session = new session;
        $arRuta = $form->get('guiaTipoRel')->getData();
        if ($arRuta) {
            $session->set('filtroTteCodigoGuiaTipo', $arRuta->getCodigoGuiaTipoPk());
        } else {
            $session->set('filtroTteCodigoGuiaTipo', null);
        }
        $arServicio = $form->get('servicioRel')->getData();
        if ($arServicio) {
            $session->set('filtroTteCodigoServicio', $arServicio->getCodigoServicioPk());
        } else {
            $session->set('filtroTteCodigoServicio', null);
        }
        $session->set('filtroTteDocumento', $form->get('documento')->getData());
        $session->set('filtroTteNumeroGuia', $form->get('numero')->getData());
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $arrayPropiedadesGuiaTipo = array(
            'class' => 'App\Entity\Transporte\TteGuiaTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('gt')
                    ->orderBy('gt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroTteCodigoGuiaTipo')) {
            $arrayPropiedadesGuiaTipo['data'] = $em->getReference("App\Entity\Transporte\TteGuiaTipo", $session->get('filtroTteCodigoGuiaTipo'));
        }
        $arrayPropiedadesServicio = array(
            'class' => 'App\Entity\Transporte\TteServicio',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroTteCodigoServicio')) {
            $arrayPropiedadesServicio['data'] = $em->getReference("App\Entity\Transporte\TteServicio", $session->get('filtroTteCodigoServicio'));
        }
        $form = $this->createFormBuilder()
            ->add('guiaTipoRel', EntityType::class, $arrayPropiedadesGuiaTipo)
            ->add('servicioRel', EntityType::class, $arrayPropiedadesServicio)
            ->add('documento', TextType::class, array('data' => $session->get('filtroTteDocumento')))
            ->add('numero', TextType::class, array('data' => $session->get('filtroTteNumeroGuia')))
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }


}

