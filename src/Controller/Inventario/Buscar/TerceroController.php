<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvTercero;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TerceroController extends AbstractController
{
   /**
    * @Route("/inventario/buscar/tercero/{campoCodigo}/{campoNombre}/{tipo}", name="inventario_buscar_tercero")
    */    
    public function lista(Request $request, PaginatorInterface $paginator, $campoCodigo, $campoNombre, $tipo = null)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroInvTerceroNombre')])
            ->add('txtCodigo', TextType::class, ['required'  => false,'data' => $session->get('filtroInvTerceroCodigo')])
            ->add('txtNit', TextType::class, ['required'  => false,'data' => $session->get('filtroInvTerceroIdentificacion')])
            ->add('BtnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $session->set('filtroInvTerceroCodigo',$form->get('txtCodigo')->getData());
                $session->set('filtroInvTerceroNombre',$form->get('txtNombre')->getData());
                $session->set('filtroInvTerceroIdentificacion',$form->get('txtNit')->getData());
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(InvTercero::class)->lista($tipo), $request->query->get('page', 1), 20);
        return $this->render('inventario/buscar/tercero.html.twig', array(
            'arTerceros' => $arTerceros,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

