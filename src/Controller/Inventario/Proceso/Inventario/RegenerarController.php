<?php

namespace App\Controller\Inventario\Proceso\Inventario;

use App\Controller\MaestroController;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegenerarController extends MaestroController
{



    public $tipo = "proceso";
    public $proceso = "invp0001";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/proceso/inventario/regenerar/lista", name="inventario_proceso_inventario_regenerar_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnRegenerarExistencias', SubmitType::class, ['label' => '2-Kardex', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnRegenerarRemisiones', SubmitType::class, ['label' => '1-Remisiones', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnRegenerarCostos', SubmitType::class, ['label' => '3-Costos', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnRegenerarPedidos', SubmitType::class, ['label' => '4-Pedidos', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnRegenerarSolicitudes', SubmitType::class, ['label' => '5-Solicitudes', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnRegenerarOrdenes', SubmitType::class, ['label' => '6-Ordenes', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRegenerarExistencias')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->regenerarExistencia();
            }
            if ($form->get('btnRegenerarCostos')->isClicked()) {
                $em->getRepository(InvMovimientoDetalle::class)->regenerarCosto();
            }
            if ($form->get('btnRegenerarRemisiones')->isClicked()) {
                $em->getRepository(InvRemisionDetalle::class)->regenerarCantidadAfectada();
            }
            if ($form->get('btnRegenerarPedidos')->isClicked()) {
                $em->getRepository(InvPedidoDetalle::class)->regenerarCantidadAfectada();
            }
            if ($form->get('btnRegenerarSolicitudes')->isClicked()) {
                $em->getRepository(InvSolicitudDetalle::class)->regenerarCantidadAfectada();
            }
            if ($form->get('btnRegenerarOrdenes')->isClicked()) {
                $em->getRepository(InvOrdenDetalle::class)->regenerarCantidadAfectada();
            }
        }
        return $this->render('inventario/proceso/inventario/regenerar.html.twig', [
            'form' => $form->createView()
        ]);
    }

}