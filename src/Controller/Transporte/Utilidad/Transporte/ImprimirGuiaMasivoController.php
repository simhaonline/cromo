<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\Formato\Transporte\Guia;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImprimirGuiaMasivoController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "tteu0008";



    /**
    * @Route("/transporte/utilidad/transporte/imprimirGuiaMasivo/lista", name="transporte_utilidad_transporte_imprimirGuiaMasivo_lista")
    */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtGuiaNumeroDesde', TextType::class, array('required' => false, 'data' => $session->get('filtroNumeroDesde')))
            ->add('txtGuiaNumeroHasta', TextType::class, array('required' => false, 'data' => $session->get('filtroNumeroHasta')))
            ->add('txtCodigoDespacho', TextType::class, array('required' => false, 'data' => $session->get('filtroCodigoDespacho')))
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnGenerar')->isClicked()){
                $arrDatos = $form->getData();
                if(($arrDatos['txtGuiaNumeroDesde'] != '' && $arrDatos['txtGuiaNumeroHasta'] != '') || $arrDatos['txtCodigoDespacho'] != ''){
                    $session->set('filtroNumeroDesde', $form->get('txtGuiaNumeroDesde')->getData());
                    $session->set('filtroNumeroHasta', $form->get('txtGuiaNumeroHasta')->getData());
                    $session->set('filtroCodigoDespacho', $form->get('txtCodigoDespacho')->getData());
                    $objFormato = new Guia();
                    $objFormato->Generar($em, '', true);
                } else {
                    Mensajes::error('Debe llenar el filtro por numero o por codigo de despacho');
                }
            }
        }
        return $this->render('transporte/utilidad/transporte/imprimirGuiaMasivo/lista.html.twig', [
            'form' => $form->createView()]);
    }
}

