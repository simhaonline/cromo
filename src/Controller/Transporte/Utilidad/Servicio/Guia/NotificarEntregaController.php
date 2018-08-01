<?php

namespace App\Controller\Transporte\Utilidad\Servicio\Guia;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteNovedad;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class NotificarEntregaController extends Controller
{
   /**
    * @Route("/transporte/utilidad/servicio/guia/entrega", name="transporte_utilidad_servicio_guia_entrega")
    */    
    public function lista(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $arGuias = null;
        //$query = $this->getDoctrine()->getRepository(TteNovedad::class)->pendienteSolucionarCliente();
        //$arNovedades = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($request->request->get('OpSinReportar')) {
                    $codigo = $request->request->get('OpSinReportar');
                    $arCliente = $em->getRepository(TteCliente::class)->find($codigo);
                    $destinatario = explode(';', strtolower($arCliente->getCorreo()));
                    $arNovedadesPendientes = $em->getRepository(TteNovedad::class)->utilidadNotificar($codigo);
                    $cuerpo = $this->render('transporte/utilidad/servicio/novedad/correo.html.twig', [
                        'arNovedades' => $arNovedadesPendientes,
                        'form' => $form->createView()]);
                    $message = (new \Swift_Message('Reporte novedades pendientes'))
                        ->setFrom('infologicuartas@gmail.com')
                        ->setTo($destinatario)
                        ->setBody(
                            $cuerpo,
                            'text/html'
                        );
                    $mailer->send($message);

                    return $this->redirect($this->generateUrl('transporte_uti_servicio_novedad_notificar'));

                }
            }
        }

        return $this->render('transporte/utilidad/servicio/guia/notificarEntrega.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    private function filtrar($form)
    {
        $session = new session;
        $arRuta = $form->get('rutaRel')->getData();
        if ($arRuta) {
            $session->set('filtroTteCodigoRuta', $arRuta->getCodigoRutaPk());
        } else {
            $session->set('filtroTteCodigoRuta', null);
        }
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;

        $form = $this->createFormBuilder()

            ->getForm();
        return $form;
    }


}

