<?php

namespace App\Controller\Transporte\Informe\Control\Comercial;

use App\Controller\Estructura\MensajesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Formato\Transporte\ControlFactura;
use App\Formato\Transporte\FacturaInforme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FacturaController extends MaestroController
{

    public $tipo = "informe";
    public $proceso = "ttei0028";




    /**
     * @Route("/transporte/informe/control/comercial/factura", name="transporte_informe_control_comercial_factura")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        if(!$session->get('filtroTteFecha')) {
            $fecha =  new \DateTime('now');
            $session->set('filtroTteFecha', $fecha->format('Y-m-d'));
        }
        $form = $this->createFormBuilder()
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('fecha', DateType::class, ['label' => 'Fecha: ',  'required' => false, 'data' => date_create($session->get('filtroTteFecha'))])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteFecha',  $form->get('fecha')->getData()->format('Y-m-d'));
            }
            if ($form->get('btnPdf')->isClicked()) {
                $formato = new ControlFactura();
                $formato->Generar($em, $session->get('filtroTteFecha') );
            }
        }
        $arFacturasTipos = $this->getDoctrine()->getRepository(TteFacturaTipo::class)->controlFactura($session->get('filtroTteFecha'));
        $arFacturas = $this->getDoctrine()->getRepository(TteFactura::class)->controlFactura();
        return $this->render('transporte/informe/control/factura.html.twig', [
            'arFacturas' => $arFacturas,
            'arFacturasTipos' => $arFacturasTipos,
            'form' => $form->createView() ]);
    }
}

