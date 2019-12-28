<?php

namespace App\Controller\Turno\Proceso\Venta;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Turno\TurContrato;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class GenerarPedidoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/turno/proceso/ventas/generarpedido", name="turno_proceso_venta_generarpedido")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        //$arContratos = null;
        $fecha = new \DateTime('now');
        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');
        $fecha = $anio . "/" . $mes . "/01";
        $form = $this->createFormBuilder()
            ->add('anio', ChoiceType::class, array(
                'choices' => array(
                    $anio - 1 => $anio - 1, $anio => $anio, $anio + 1 => $anio + 1
                ),
                'data' => $anio,
            ))
            ->add('mes', ChoiceType::class, array(
                'choices' => array(
                    'Enero' => '01', 'Febrero' => '02', 'Marzo' => '03', 'Abril' => '04', 'Mayo' => '05', 'Junio' => '06', 'Julio' => '07',
                    'Agosto' => '08', 'Septiembre' => '09', 'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12',
                ),
                'data' => $mes,
            ))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $anio = $form->get('anio')->getData();
            $mes = $form->get('mes')->getData();
            $fecha = $anio . "/" . $mes . "/01";
            if ($form->get('btnFiltrar')->isClicked()) {
                $arContratos = $paginator->paginate($em->getRepository(TurContrato::class)->listaGenerarPedido($fecha), $request->query->getInt('page', 1),1000);
            }
            if ($form->get('btnGenerar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurContrato::class)->generarPedido($arrSeleccionados, $fecha, $this->getUser()->getUserName());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurContrato::class)->listaGenerarPedido($fecha), "Contratos");
            }
        }
        $arContratos = $paginator->paginate($em->getRepository(TurContrato::class)->listaGenerarPedido($fecha), $request->query->getInt('page', 1),1000);
        return $this->render('turno/proceso/venta/generarPedido.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }

}

