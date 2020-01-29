<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteRedespachoMotivo;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class RedespachoController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "ttep0009";



    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/redespacho", name="transporte_proceso_transporte_guia_redespacho")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
           //$arGuia = $em->getRepository(TteGuia::class)->findAll();
        $form = $this->createFormBuilder()
            ->add('redespachoMotivoRel',EntityType::class,[
                'required' => true,
                'class' => TteRedespachoMotivo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rm')
                        ->orderBy('rm.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Motivo:'
            ])
            ->add('txtCodigo', TextType::class)
            ->add('btnRedespacho', SubmitType::class, ['label' => 'Redespacho', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRedespacho')->isClicked()) {
                $codigoGuia = $form->get('txtCodigo')->getData();
                $arrRedespachoMotivo = $form->get('redespachoMotivoRel')->getData();
                if($codigoGuia != "") {
                    $em->getRepository(TteGuia::class)->redespacho($codigoGuia, $arrRedespachoMotivo);
                }
            }
        }
        return $this->render('transporte/proceso/transporte/guia/redespacho.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

