<?php

namespace App\Controller\Inventario\Administracion;

use App\Controller\General\FuncionesGeneralesController;
use App\Entity\Inventario\InvItem;
use App\Form\Type\Inventario\ItemType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ItemController extends Controller
{
    var $query = '';

    /**
     * @Route("/inv/adm/item/lista", name="inv_adm_item_lista")
     */
    public function lista(Request $request)
    {
        $nombre = 'Item';
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesGeneralesController();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listado($em);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:Inventario\InvItem')->eliminar($arrSeleccionados);
                $objFunciones->validarRespuesta($respuesta, $em);
                return $this->redirect($this->generateUrl('inv_adm_item_lista'));
            }
            if ($form->get('btnExcel')->isClicked()) {
                $this->listado($em);
                $objFunciones->generarExcel($this->query,$nombre);
            }
        }
        $arItems = $paginator->paginate($this->query, $request->query->getInt('page', 1), 10);
        return $this->render('inventario/administracion/item/lista.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $em EntityManager
     */
    public function listado($em)
    {
        $this->query = $em->getRepository('App:Inventario\InvItem')->lista();
    }

    public function formularioLista()
    {
        return $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
    }

    /**
     * @Route("/inv/adm/item/nuevo/{id}",name="inv_adm_item_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arItem = new InvItem();
        if ($id != 0) {
            $arItem = $em->getRepository('App:Inventario\InvItem')->find($id);
            if (!$arItem) {
                return $this->redirect($this->generateUrl('inv_adm_item_lista'));
            }
        }
        $form = $this->createForm(ItemType::class, $arItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arItem);
                $em->flush();
                return $this->redirect($this->generateUrl('inv_adm_item_lista'));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arItem);
                $em->flush();
                return $this->redirect($this->generateUrl('inv_adm_item_nuevo', ['codigoItem' => 0]));
            }
        }
        return $this->render('inventario/administracion/item/nuevo.html.twig', [
            'form' => $form->createView(),
            'arItem' => $arItem
        ]);
    }

    /**
     * @Route("inv/adm/item/agregar", name="inv_adm_item_agregar")
     */
    public function agregarItem(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioAgregar();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {

            }
            if ($form->get('filtrar')->isClicked()) {

            }
        }
        return $this->render('inventario/administracion/item/agregarItem.html.twig', [
            $form->createView()
        ]);
    }

    public function formularioAgregar()
    {
        return $this->createFormBuilder()
            ->add('nombre', TextType::class, ['required' => false])
            ->add('codigoBarras', TextType::class, ['required' => false])
            ->add('codigoItem', TextType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar'])
            ->add('filtrar', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();
    }

    public function filtrar($form)
    {
        $session = new Session();
        $session->set('filtroInvNombreItem', $form->get('nombre')->getData());
        $session->set('filtroInvCodigoBarras', $form->get('codigoBarras')->getData());
        $session->set('filtroInvCodigoItem', $form->get('codigoItem')->getData());
    }
}
