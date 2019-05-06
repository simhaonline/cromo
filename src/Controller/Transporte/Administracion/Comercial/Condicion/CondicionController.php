<?php
namespace App\Controller\Transporte\Administracion\Comercial\Condicion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteCondicionZona;
use App\Entity\Transporte\TteDescuentoZona;
use App\Form\Type\Transporte\CondicionType;
use App\Form\Type\Transporte\DescuentoZonaType;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class CondicionController extends ControllerListenerGeneral
{
    protected $class= TteCondicion::class;
    protected $claseNombre = "TteCondicion";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Comercial";
    protected $nombre = "Condicion";

    /**
     * @Route("/transporte/administracion/comercial/condicion/lista", name="transporte_administracion_comercial_condicion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroNombreCondicion')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroNombreCondicion', $form->get('txtNombre')->getData());
        }
        $arCondiciones = $paginator->paginate($em->getRepository(TteCondicion::class)->lista(), $request->query->getInt('page', 1),50);
        return $this->render('transporte/administracion/comercial/condicion/lista.html.twig',
            ['arCondiciones' => $arCondiciones,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/transporte/administracion/comercial/condicion/detalle/{id}", name="transporte_administracion_comercial_condicion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCondicion = $em->getRepository(TteCondicion::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnEliminarDetalle', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteDescuentoZona::class)->eliminar($arrSeleccionados);
            }
        }
        $arCondicionesZona = $em->getRepository(TteCondicionZona::class)->condicion($id);
        return $this->render('transporte/administracion/comercial/condicion/detalle.html.twig', array(
            'arCondicion' => $arCondicion,
            'arCondicionesZona' => $arCondicionesZona,
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/transporte/administracio/comercial/condicion/nuevo/{id}", name="transporte_administracion_comercial_condicion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCondicion = new TteCondicion();
        if ($id != '0') {
            $arCondicion = $em->getRepository(TteCondicion::class)->find($id);
            if (!$arCondicion) {
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_condicion_lista'));
            }
        }
        $form = $this->createForm(CondicionType::class, $arCondicion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arCondicion);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_condicion_detalle', ['id' => $arCondicion->getCodigoCondicionPk()]));
            }
        }
        return $this->render('transporte/administracion/comercial/condicion/nuevo.html.twig', [
            'arCondicion' => $arCondicion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/administracion/comercial/condicion/detalle/nuevo/{codigoCondicion}/{id}", name="transporte_administracion_comercial_condicion_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoCondicion, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCondicion = $em->getRepository(TteCondicion::class)->find($codigoCondicion);
        $arDescuentoZona = new TteDescuentoZona();
        if ($id != '0') {
            $arDescuentoZona = $em->getRepository(TteDescuentoZona::class)->find($id);
        } else {
            $arDescuentoZona->setCondicionRel($arCondicion);
        }
        $form = $this->createForm(DescuentoZonaType::class, $arDescuentoZona);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arDescuentoZona);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('transporte/administracion/comercial/condicion/detalleNuevo.html.twig', [
            'arCondicion' => $arDescuentoZona,
            'form' => $form->createView()
        ]);
    }

}

