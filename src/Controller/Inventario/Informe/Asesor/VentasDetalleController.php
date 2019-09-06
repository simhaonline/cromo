<?php

namespace App\Controller\Inventario\Informe\Asesor;

use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Formato\Cartera\Recaudo;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VentasDetalleController extends Controller
{
    protected $proceso = "0010";
    protected $procestoTipo= "I";
    protected $nombreProceso = "Ventas de asesor detalle";
    protected $modulo = "Inventario";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/asesor/ventasdetalle", name="inventario_informe_inventario_asesor_ventas_detalle")
     */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $asesor = $this->getUser()->getCodigoAsesorFk();
        if($asesor == null){
            Mensajes::error("El usuario no tiene asesor asignado");
        }
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasDetalleFechaDesde') ? date_create($session->get('filtroInvInformeAsesorVentasDetalleFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroInvInformeAsesorVentasDetalleFechaHasta') ? date_create($session->get('filtroInvInformeAsesorVentasDetalleFechaHasta')): null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
            $session->set('filtroInvInformeAsesorVentasDetalleFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
            $session->set('filtroInvInformeAsesorVentasDetalleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(InvMovimientoDetalle::class)->ventasSoloAsesor($asesor)->getQuery()->getResult(), "Ventas por asesor");
        }
        $arFacturas = $paginator->paginate($em->getRepository(InvMovimientoDetalle::class)->ventasSoloAsesor($asesor), $request->query->getInt('page', 1), 500);
        return $this->render('inventario/informe/asesor/ventasDetalle.twig', [
            'arFacturas' => $arFacturas,
            'form' => $form->createView()
        ]);
    }
}

