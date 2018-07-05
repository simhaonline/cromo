<?php

namespace App\Controller\Documental\Movimiento\Masivo;


use App\Entity\Documental\DocRegistro;
use App\Entity\Documental\DocRegistroCarga;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class MasivoController extends Controller
{
   /**
    * @Route("/documental/movimiento/masivo/registro/lista", name="documental_movimiento_masivo_registro_lista")
    */    
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtIdentificador', TextType::class, ['required' => false, 'data' => $session->get('filtroDocRegistroIdentificador')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroDocRegistroIdentificador', $form->get('txtNumero')->getData());
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository(DocRegistro::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('documental/movimiento/masivo/registro/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/documental/movimiento/masivo/registro/carga", name="documental_movimiento_masivo_registro_carga")
     */
    public function carga(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtIdentificador', TextType::class, ['required' => false, 'data' => $session->get('filtroDocRegistroIdentificador')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnAnalizarBandeja', SubmitType::class, ['label' => 'Analizar bandeja', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroDocRegistroIdentificador', $form->get('txtNumero')->getData());
            }
            if ($form->get('btnAnalizarBandeja')->isClicked()) {
                $directorioBandeja = "/bandeja";
                $ficheros  = scandir($directorioBandeja);
                foreach ($ficheros as $fichero) {
                    if($fichero != "." && $fichero != "..") {
                        $partes = explode(".", $fichero);
                        if(count($partes) == 2 ) {
                            print_r($partes);
                            $arRegistroCarga = new DocRegistroCarga();
                            $arRegistroCarga->setIdentificador($partes[0]);
                            $em->persist($arRegistroCarga);
                        }
                    }
                }
                $em->flush();
            }
        }
        $arRegistrosCargas = $paginator->paginate($em->getRepository(DocRegistroCarga::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('documental/movimiento/masivo/registro/carga.html.twig', [
            'arRegistrosCargas' => $arRegistrosCargas,
            'form' => $form->createView()
        ]);
    }



}

