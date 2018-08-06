<?php

namespace App\Controller\Inventario\Informe\Inventario\Movimiento;

use App\Entity\Inventario\InvInventarioValorizado;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\General\General;
class InventarioValorizadoController extends Controller
{
   /**
    * @Route("/inventario/informe/inventario/movimiento/inventariovalorizado", name="inventario_informe_inventario_movimiento_inventariovalorizado")
    */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('fechaHasta', DateType::class, array('label' => 'Fecha hasta: ', 'required' => false, 'data' => new \DateTime('now')))
            ->add('txtCodigoItem', TextType::class, ['required' => false, 'data' => $session->get('filtroInvItemCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                $em->getRepository(InvInventarioValorizado::class)->generar($form->get('fechaHasta')->getData()->format('Y-m-d'));

            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(InvInventarioValorizado::class)->lista())->execute(), "InventarioValorizado");
            }
        }
        $arInventarioValorizado = $paginator->paginate($em->getRepository(InvInventarioValorizado::class)->lista(), $request->query->getInt('page', 1), 1000);
        return $this->render('inventario/informe/inventario/movimiento/inventarioValorizado.html.twig', [
            'arInventarioValorizado' => $arInventarioValorizado,
            'form' => $form->createView()
        ]);
    }

}

