<?php

namespace App\Controller\Inventario\Buscar;

use App\Entity\Inventario\InvContacto;
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

class ContactoController extends AbstractController
{
   /**
    * @Route("/inventario/buscar/contacto/lista/{campoCodigo}/{campoNombre}/{codigoTercero}", name="inventario_buscar_contacto_lista", defaults={"codigoTercero":0})
    */
    public function lista(Request $request, PaginatorInterface $paginator ,$campoCodigo, $campoNombre, $codigoTercero)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, ['required'  => false,'data' => $session->get('filtroInvBuscarContactoNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label'  => 'Filtrar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvBuscarContactoNombre',$form->get('txtNombre')->getData());
            }
        }
        $arContactos = $paginator->paginate($em->getRepository(InvContacto::class)->listaTercero($codigoTercero), $request->query->get('page', 1), 20);
        return $this->render('inventario/buscar/contacto.html.twig', array(
            'arContactos' => $arContactos,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }

}

