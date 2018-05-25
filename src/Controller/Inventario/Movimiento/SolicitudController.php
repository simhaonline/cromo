<?php

namespace App\Controller\Inventario\Movimiento;

use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Estructura\AdministracionController;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class SolicitudController extends Controller
{
    var $query = '';

    /**
     * @Route("/inv/mov/inventario/solicitud/nuevo/{id}", name="inv_mov_inventario_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new InvSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('inv_mto_solicitud_lista'));
            }
        }
        $arSolicitud->setFecha(new \DateTime('now'));
        $arSolicitud->setUsuario($this->getUser()->getUserName());
        $form = $this->createForm(SolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSolicitud->setFecha(new \DateTime('now'));
                $em->persist($arSolicitud);
                $em->flush($arSolicitud);
                return $this->redirect($this->generateUrl('listado', ['entidad' => 'InvSolicitud']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSolicitud);
                $em->flush($arSolicitud);
                return $this->redirect($this->generateUrl('inv_mto_solicitud_nuevo', ['codigoSolicitud' => 0]));
            }
        }
        return $this->render('inventario/movimiento/solicitud/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arSolicitud
        ]);
    }

    /**
     * @Route("/inv/mov/inventario/solicitud/detalle/{id}", name="inv_mov_inventario_solicitud_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
        $arSolicitudDetalles = $em->getRepository('App:Inventario\InvSolicitudDetalle')->findBy(['codigoSolicitudFk' => $id]);
        $form = $this->formularioDetalles();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->autorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle',['id' => $id]));
            }

            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->desautorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle',['id' => $id]));
            }

            if ($form->get('btnImprimir')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->imprimir($arSolicitud);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle',['id' => $id]));
            }

            if ($form->get('btnAprobar')->isClicked()) {

            }

            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository('App:Inventario\InvSolicitud')->anular($arSolicitud);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle',['id' => $id]));
            }

            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvSolicitudDetalle')->eliminar($arSolicitud, $arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('inv_mov_inventario_solicitud_detalle',['id' => $id]));
            }
        }
        return $this->render('inventario/movimiento/solicitud/detalle.html.twig', [
            'form' => $form->createView(),
            'arSolicitudDetalles' => $arSolicitudDetalles,
            'arSolicitud' => $arSolicitud
        ]);
    }

    /**
     * @Route("/inv/mov/inventario/solicitud/detalle/nuevo/{id}", name="inv_mov_inventario_solicitud_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arSolicitud = $em->getRepository('App:Inventario\InvSolicitud')->find($id);
        $form = $this->formularioFiltroItems();
        $form->handleRequest($request);
        $this->listaItems($em, $form);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $this->listaItems($em, $form);
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                foreach ($arrItems as $codigoItem => $cantidad) {
                    if ($cantidad != '' && $cantidad != 0) {
                        $arItem = $em->getRepository('App:Inventario\InvItem')->find($codigoItem);
                        $arSolicitudDetalle = new InvSolicitudDetalle();
                        $arSolicitudDetalle->setSolicitudRel($arSolicitud);
                        $arSolicitudDetalle->setItemRel($arItem);
                        $arSolicitudDetalle->setCantidad($cantidad);
                        $arSolicitudDetalle->setCantidadRestante($cantidad);
                        $em->persist($arSolicitudDetalle);
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arItems = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/solicitud/detalleNuevo.html.twig', [
            'form' => $form->createView(),
            'arItems' => $arItems
        ]);
    }

    public function formularioDetalles()
    {
        return $this
            ->createFormBuilder()
            ->add('btnAutorizar', SubmitType::class, ['label' => 'Autorizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnDesautorizar', SubmitType::class, ['label' => 'Desautorizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnImprimir', SubmitType::class, ['label' => 'Imprimir', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnAprobar', SubmitType::class, ['label' => 'Aprobar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnAnular', SubmitType::class, ['label' => 'Anular', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
    }

    private function formularioFiltroItems()
    {
        return $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
    }

    /**
     * @param $em ObjectManager
     * @param $form \Symfony\Component\Form\FormInterface
     */
    private function listaItems($em, $form)
    {
        $session = new Session();
        $session->set('filtroCodigoItem', $form->get('txtCodigoItem')->getData());
        $session->set('filtroNombreItem', $form->get('txtNombreItem')->getData());
        $this->query = $em->getRepository('App:Inventario\InvItem')->listarItems($session->get('filtroNombreItem'), $session->get('filtroCodigoItem'));
    }
}
