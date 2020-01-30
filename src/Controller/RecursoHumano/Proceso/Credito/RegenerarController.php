<?php

namespace App\Controller\RecursoHumano\Proceso\Credito;

use App\Controller\MaestroController;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvOrdenCompraDetalle;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvPedidoDetalle;
use App\Entity\Inventario\InvRemisionDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuPago;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegenerarController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "rhup0003";



    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/recursohumano/proceso/credito/regenerar", name="recursohumano_proceso_credito_regenerar")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnRegenerarSaldo', SubmitType::class, ['label' => 'Regenerar saldo', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRegenerarSaldo')->isClicked()) {
                $em->getRepository(RhuCredito::class)->regenerar();
                Mensajes::success("Proceso terminado");
            }
        }
        return $this->render('recursohumano/proceso/credito/regenerar.html.twig', [
            'form' => $form->createView()
        ]);
    }


}