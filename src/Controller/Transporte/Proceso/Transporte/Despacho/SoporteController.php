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

class SoporteController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "ttep0005";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/despacho/soporte", name="transporte_proceso_transporte_despacho_soporte")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $codigoDespacho = 0;
        $form = $this->createFormBuilder()
            ->add('txtDespachoCodigo', TextType::class, array('data' => $session->get('filtroTteDespachoCodigo')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnSoporte', SubmitType::class, array('label' => 'Soporte'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session = new session;
                $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
            if ($form->get('btnSoporte')->isClicked()) {
                $arrDespachos = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteDespacho::class)->soporte($arrDespachos[0]);
                $codigoDespacho = $form->get('txtDespachoCodigo')->getData();
            }
        }
        $arDespachos = $paginator->paginate($em->getRepository(TteDespacho::class)->listaSoporte($codigoDespacho), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/proceso/transporte/despacho/soporte.html.twig', [
            'arDespachos' => $arDespachos,
            'form' => $form->createView()
        ]);
    }

}

