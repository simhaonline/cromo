<?php

namespace App\Controller\Inventario\Informe\Inventario\Movimiento;

use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class KardexController extends Controller
{
   /**
    * @Route("/inventario/informe/inventario/movimiento/kardex", name="inventario_informe_inventario_movimiento_kardex")
    */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, ['required' => false, 'data' => $session->get('filtroInvItemCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
            }
        }
        $arMovimientosDetalles = $paginator->paginate($em->getRepository(InvMovimientoDetalle::class)->listaKardex(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/movimiento/kardex.html.twig', [
            'arMovimientosDetalles' => $arMovimientosDetalles,
            'form' => $form->createView()
        ]);
    }

}

