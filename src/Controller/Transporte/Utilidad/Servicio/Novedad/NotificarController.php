<?php

namespace App\Controller\Transporte\Utilidad\Servicio\Novedad;

use App\Entity\Transporte\TteNovedad;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class NotificarController extends Controller
{
   /**
    * @Route("/tte/uti/servicio/novedad/notificar", name="transporte_uti_servicio_novedad_notificar")
    */    
    public function lista(Request $request, \Swift_Mailer $mailer)
    {
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($request->request->get('OpSinReportar')) {


                    $codigo = $request->request->get('OpSinReportar');
                    //$arCierreMes = $em->getRepository('BrasaTurnoBundle:TurCierreMes')->find($codigoCierreMes);
                    //$this->generarDistribucion($arCierreMes);//Ejecutar funcion que realiza el proceso de distribucion de centro de costo.
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('informacionsemantica@gmail.com')
                        ->setTo('maestradaz3@gmail.com')
                        ->setBody(
                            "Hola mundo",
                            'text/html'
                        );
                    $mailer->send($message);

                    return $this->redirect($this->generateUrl('transporte_uti_servicio_novedad_notificar'));

                }
            }
        }
        $query = $this->getDoctrine()->getRepository(TteNovedad::class)->pendienteSolucionarCliente();
        $arNovedades = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('transporte/utilidad/servicio/novedad/notificar.html.twig', [
            'arNovedades' => $arNovedades,
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

