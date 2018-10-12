<?php

namespace App\Controller\Inventario\Administracion;

use App\Controller\General\FuncionesGeneralesController;
use App\Entity\Inventario\InvItem;
use App\Form\Type\Inventario\ItemType;
use App\General\General;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/administracion/inventario/item/lista", name="inventario_administracion_inventario_item_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroInvBuscarItemNombre')])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnFiltrar')->isClicked()){
                $session->set('filtroInvBuscarItemNombre', $form->get('txtNombre')->getData());
            }
            if($form->get('btnExcel')->isClicked()){
                General::get()->setExportar($this->getDoctrine()->getRepository(InvItem::class)->lista()->getQuery()->execute(), 'Items');
            }
        }
        $arItems = $paginator->paginate($this->getDoctrine()->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('inventario/administracion/item/lista.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/inventario/administracion/inventario/item/nuevo/{id}",name="inventario_administracion_inventario_item_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arItem = new InvItem();
        if ($id != 0) {
            $arItem = $em->getRepository('App:Inventario\InvItem')->find($id);
            if (!$arItem) {
                return $this->redirect($this->generateUrl('inventario_administracion_inventario_item_lista'));
            }
        }
        $form = $this->createForm(ItemType::class, $arItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arItem);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_administracion_inventario_item_lista'));
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
}
