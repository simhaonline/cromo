<?php

namespace App\Controller\Transporte\Administracion\Comercial\Precio;

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
    protected $class= TtePrecio::class;
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
        $paginator = $this->get('knp_paginator');
        $arPrecios = $paginator->paginate($this->getDoctrine()->getRepository(TtePrecio::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('transporte/administracion/comercial/precio/lista.html.twig', ['arPrecios' => $arPrecios]);
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
     * @Route("/transporte/administracion/comercial/precio/detalle/{id}", name="transporte_administracion_comercial_precio_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arPrecio = $em->getRepository(TtePrecio::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnEliminarDetalle', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnEliminarDetalle')->isClicked()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $em->getRepository(TtePrecioDetalle::class)->eliminar($arrSeleccionados);
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
        if($id != '0'){
            $arPrecioDetalle = $em->getRepository(TtePrecioDetalle::class)->find($id);
        }
        $form = $this->createForm(PrecioDetalleType::class, $arPrecioDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arPrecioDetalleExistente = $em->getRepository(TtePrecioDetalle::class)
                ->findBy(['ciudadOrigenRel' => $arPrecioDetalle->getCiudadOrigenRel(), 'ciudadDestinoRel'=> $arPrecioDetalle->getCiudadDestinoRel(), 'productoRel' => $arPrecioDetalle->getProductoRel()]);
            if(!$arPrecioDetalleExistente){
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
}

