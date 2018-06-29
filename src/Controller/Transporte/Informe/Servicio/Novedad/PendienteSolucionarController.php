<?php

namespace App\Controller\Transporte\Informe\Servicio\Novedad;

use App\Entity\Transporte\TteNovedad;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class PendienteSolucionarController extends Controller
{
   /**
    * @Route("/transporte/inf/servicio/novedad/pendiente/solucionar", name="transporte_inf_servicio_novedad_pendiente_solucionar")
    */    
    public function lista(Request $request)
    {
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnReportar')->isClicked()) {
                    $arrNovedades = $request->request->get('chkSeleccionar');
                    $arrControles = $request->request->All();
                    $respuesta = $this->getDoctrine()->getRepository(TteNovedad::class)->setReportar($arrNovedades, $arrControles);
                }
            }
        }
        $query = $this->getDoctrine()->getRepository(TteNovedad::class)->pendienteSolucionar();
        $arNovedades = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('transporte/informe/servicio/novedad/pendienteSolucionar.html.twig', [
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
            ->add('btnReportar', SubmitType::class, array('label' => 'Reportar'))
            ->getForm();
        return $form;
    }


}

