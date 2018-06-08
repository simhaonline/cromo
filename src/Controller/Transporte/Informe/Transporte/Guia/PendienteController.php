<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteGuia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class PendienteController extends Controller
{
   /**
    * @Route("/tte/inf/transporte/guia/pendiente", name="tte_inf_transporte_guia_pendiente")
    */    
    public function lista(Request $request)
    {
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('BtnFiltrar')->isClicked()) {
                    $this->filtrar($form);
                    $form = $this->formularioFiltro();
                }
            }
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->pendienteConductor();
        return $this->render('transporte/informe/transporte/guia/pendiente.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    private function filtrar($form)
    {
        $session = new session;
        //$session->set('filtroTteCodigoConductor', $form->get('txtCodigoConductor')->getData());
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;

        $form = $this->createFormBuilder()

            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }


}

