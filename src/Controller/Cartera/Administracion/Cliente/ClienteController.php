<?php

namespace App\Controller\Cartera\Administracion\Cliente;

use App\Entity\Cartera\CarCliente;
use App\Form\Type\Cartera\ClienteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClienteController extends Controller
{
    var $query = '';


    /**
     * @Route("/car/adm/cliente/nuevo/{id}", name="car_adm_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new CarCliente();
        if ($id != 0) {
            $arCliente = $em->getRepository('App:Cartera\CarCliente')->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('car_adm_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente = $form->getData();
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('listado',['entidad' => 'CarCliente']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $arCliente = $form->getData();
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('car_adm_cliente_nuevo', ['codigoCliente' => 0]));
            }
        }
        return $this->render('cartera/administracion/cliente/nuevo.html.twig',
            ['arCliente' => $arCliente, 'form' => $form->createView()]);
    }

}