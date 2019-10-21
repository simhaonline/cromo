<?php

namespace App\Controller\Tesoreria\Proceso\General;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UnifircarClienteController extends AbstractController
{
    /**
     * @Route("/tesoreria/proceso/general/unificarcliente/lista", name="tesoreria_proceso_general_unificarcliente_lista")
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
                $em->getRepository(TesTercero::class)->unificar();
                Mensajes::success('El proceso se ejecuto con exito');
                return $this->redirect($this->generateUrl('tesoreria_proceso_general_unificarcliente_lista'));
            }
        }
        return $this->render('tesoreria/proceso/general/unificarCliente.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
