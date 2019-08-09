<?php


namespace App\Controller\RecursoHumano\Informe\Nomina;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\General\GenModulo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuRequisito;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class pagoDetalleController extends  Controller
{

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/recursohumano/informe/nomina/pagodetalle/lista", name="recursohumano_informe_nomina_pagodetalle_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtEmpleado', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoDetalleFechaDesde') ? date_create($session->get('filtroRhuInformePagoDetalleFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoDetalleFechaHasta') ? date_create($session->get('filtroRhuInformePagoDetalleFechaHasta')): null])
            ->add('concepto', EntityType::class, array(
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoConceptoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'attr' => ['class' => 'form-control to-select-2'],
                'data' => $session->get('arSeguridadUsuarioProcesofiltroModulo')||""
            ))
            ->add('pagoTipo', EntityType::class, array(
                'class' => RhuPagoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'attr' => ['class' => 'form-control to-select-2'],
                'data' => $session->get('arSeguridadUsuarioProcesofiltroModulo')||""
            ))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnExcelEmpleado', SubmitType::class, ['label'=>'Excel empleado', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnExcelConcepto', SubmitType::class, ['label'=>'Excel concepto', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked() || $form->get('btnExcelEmpleado')->isClicked() || $form->get('btnExcelConcepto')->isClicked()) {
                $arConcepto = $form->get('concepto')->getData();
                if($arConcepto) {
                    $session->set('filtroRhuInformePagoDetalleConcepto', $arConcepto->getCodigoConceptoPk());
                } else {
                    $session->set('filtroRhuInformePagoDetalleConcepto', null);
                }
                $arPagoTipo = $form->get('pagoTipo')->getData();
                if($arPagoTipo) {
                    $session->set('filtroRhuInformePagoDetalleTipo', $arPagoTipo->getCodigoPagoTipoPk());
                } else {
                    $session->set('filtroRhuInformePagoDetalleTipo', null);
                }
                $session->set('filtroRhuInformePagoDetalleCodigoEmpleado',  $form->get('txtEmpleado')->getData());
                $session->set('filtroRhuInformePagoDetalleFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuInformePagoDetalleFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuPagoDetalle::class)->informe()->getQuery()->getResult(), "Pagos detalle");
            }
            if ($form->get('btnExcelEmpleado')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuPagoDetalle::class)->excelResumenEmpleado()->getQuery()->getArrayResult(), "pagoDetalleEmpleado");
            }
            if ($form->get('btnExcelConcepto')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuPagoDetalle::class)->excelResumenConcepto()->getQuery()->getArrayResult(), "pagoDetalleEmpleado");
            }
        }
        $arPagoDetalles = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/informe/nomina/pagodetalle/lista.html.twig', [
            'arPagoDetalles' => $arPagoDetalles,
            'form' => $form->createView()
        ]);
	}
}