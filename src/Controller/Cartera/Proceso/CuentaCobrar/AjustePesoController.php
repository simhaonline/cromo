<?php

namespace App\Controller\Cartera\Proceso\CuentaCobrar;

use App\Controller\MaestroController;
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

class AjustePesoController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "carp0003";

    /**
     * @Route("/cartera/proceso/cuentacobrar/ajustepeso/lista", name="cartera_proceso_cuentacobrar_ajustepeso_lista")
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
                $em->getRepository(CarCuentaCobrar::class)->ajustePesoCorreccion();
                return $this->redirect($this->generateUrl('cartera_proceso_cuentacobrar_ajustepeso_lista'));
            }
        }
        return $this->render('cartera/proceso/cuentacobrar/ajustePeso.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
