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
class PendienteDespachoRutaController extends Controller
{
   /**
    * @Route("/transporte/inf/transporte/guia/pendientedespachoruta", name="transporte_inf_transporte_guia_pendiente_despacho_ruta")
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
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->informeDespachoPendienteRuta();
        return $this->render('transporte/informe/transporte/guia/despachoPendienteRuta.html.twig', [
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
        $arrayPropiedadesRuta = array(
            'class' => 'App\Entity\Transporte\TteRuta',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->orderBy('r.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroTteCodigoRuta')) {
            $arrayPropiedadesRuta['data'] = $em->getReference("App\Entity\Transporte\TteRuta", $session->get('filtroTteCodigoRuta'));
        }
        $form = $this->createFormBuilder()
            ->add('rutaRel', EntityType::class, $arrayPropiedadesRuta)
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }


}

