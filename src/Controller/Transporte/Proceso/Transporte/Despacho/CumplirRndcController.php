<?php

namespace App\Controller\Transporte\Proceso\Transporte\Despacho;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteGuia;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class CumplirRndcController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "ttep0006";



    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/despacho/cumplirrndc", name="transporte_proceso_transporte_despacho_cumplirrndc")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('OpCumplir')) {
                $codigo = $request->request->get('OpCumplir');
                $em->getRepository(TteDespacho::class)->cumplirRndc($codigo);
            }
        }
        $arDespachos = $paginator->paginate($em->getRepository(TteDespacho::class)->pendienteCumplirRndc(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/proceso/transporte/despacho/cumplirRndc.html.twig', [
            'arDespachos' => $arDespachos,
            'form' => $form->createView()
        ]);
    }
}

