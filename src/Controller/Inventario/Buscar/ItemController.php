<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvTercero;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemController extends AbstractController
{
   /**
    * @Route("/inventario/buscar/item/{campoCodigo}/{campoNombre}", name="inventario_buscar_item")
    */    
    public function lista(Request $request,  PaginatorInterface $paginator,$campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBucarItemCodigo')])
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarItemNombre')])
            ->add('txtMarca', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarItemMarca')])
            ->add('txtReferencia', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarItemReferencia')])
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroInvBucarItemCodigo',$form->get('txtCodigo')->getData());
                $session->set('filtroInvBuscarItemNombre',$form->get('txtNombre')->getData());
                $session->set('filtroInvBuscarItemMarca',$form->get('txtMarca')->getData());
                $session->set('filtroInvBuscarItemReferencia',$form->get('txtReferencia')->getData());
            }
        }
        $arItemes = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('inventario/buscar/item.html.twig', array(
            'arItemes' => $arItemes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

