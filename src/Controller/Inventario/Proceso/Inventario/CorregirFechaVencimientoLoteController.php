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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CorregirFechaVencimientoLoteController extends MaestroController
{


    public $tipo = "proceso";
    public $proceso = "invp0002";





    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/proceso/inventario/corregirfechavencimientolote/lista", name="inventario_proceso_inventario_corregirfechavencimientolote_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnCorregir', SubmitType::class, ['label' => 'Corregir', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnCorregir')->isClicked()) {
                $em->getRepository(InvLote::class)->corregirFechaVencimiento();
            }
        }
        return $this->render('inventario/proceso/inventario/corregirFechaVencimientoLote.html.twig', [
            'form' => $form->createView()
        ]);
    }

}