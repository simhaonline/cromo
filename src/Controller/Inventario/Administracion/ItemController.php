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
}
