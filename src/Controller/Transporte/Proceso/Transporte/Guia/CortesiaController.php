<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class CortesiaController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "ttep0011";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/cortesia", name="transporte_proceso_transporte_guia_cortesia")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class)
            ->add('btnCortesia', SubmitType::class, ['label' => 'Cortesia', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnCortesia')->isClicked()) {
                $codigoGuia = $form->get('txtCodigo')->getData();
                if($codigoGuia != "") {
                    $em->getRepository(TteGuia::class)->cortesia($codigoGuia);
                }
            }
        }
        return $this->render('transporte/proceso/transporte/guia/cortesia.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

