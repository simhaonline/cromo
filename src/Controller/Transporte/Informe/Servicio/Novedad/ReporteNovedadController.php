<?php

namespace App\Controller\Transporte\Informe\Servicio\Novedad;

use App\Entity\Transporte\TteNovedad;
use App\General\General;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class ReporteNovedadController extends Controller
{
   /**
    * @Route("/transporte/informe/control/novedad/reporte/novedad", name="transporte_informe_servicio_novedad_reporte_novedad")
    */    
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $fecha = new \DateTime('now');
        if($session->get('filtroFechaDesde') == "") {
            $session->set('filtroFechaDesde', $fecha->format('Y-m-d'));
        }
        if($session->get('filtroFechaHasta') == "") {
            $session->set('filtroFechaHasta', $fecha->format('Y-m-d'));
        }
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFechaHasta'))])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('chkEstadoAtendido', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteNovedadEstadoAtendido'), 'required' => false])
            ->add('chkEstadoSolucionado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroTteNovedadEstadoSolucionado'), 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
            $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
            $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            $session->set('filtroTteNovedadEstadoAtendido', $form->get('chkEstadoAtendido')->getData());
            $session->set('filtroTteNovedadEstadoSolucionado', $form->get('chkEstadoSolucionado')->getData());
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteNovedad::class)->reporteNovedad()->getQuery()->getResult(), "Novedades");
        }
        $query = $this->getDoctrine()->getRepository(TteNovedad::class)->reporteNovedad();
        $arNovedades = $paginator->paginate($query, $request->query->getInt('page', 1),100);
        return $this->render('transporte/informe/servicio/novedad/reporteNovedad.html.twig', [
            'arNovedades' => $arNovedades,
            'form' => $form->createView()]);
    }
}

