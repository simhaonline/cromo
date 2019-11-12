<?php

namespace App\Controller\Cartera\Proceso\CuentaCobrar;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CorregirSaldosController extends AbstractController
{
    /**
     * @Route("/cartera/proceso/cuentacobrar/corregirsaldos/lista", name="cartera_proceso_cuentacobrar_corregirsaldos_lista")
     */
    public function lista(Request $request, TokenStorageInterface $user)
    {
        $em=$this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnGenerar',SubmitType::class,['label' => "Generar", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $em->getRepository(CarCuentaCobrar::class)->corregirSaldos();
                Mensajes::success("Proceso terminado");
                return $this->redirect($this->generateUrl('cartera_proceso_cuentacobrar_corregirsaldos_lista'));
            }
        }
        return $this->render('cartera/proceso/cuentacobrar/corregirSaldos.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
