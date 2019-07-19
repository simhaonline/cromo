<?php


namespace App\Controller\RecursoHumano\Informe\SeguridadSocial;


use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AporteController extends  Controller
{
    /**
     * @Route("/recursohumano/informe/seguridadsocial/aporte/lista", name="recursohumano_informe_seguridadsocial_aporte_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtAnio', TextType::class, ['required' => false])
            ->add('txtMes', ChoiceType::class, [
                'choices' => array(
                    'Enero' => '1', 'Febrero' => '2', 'Marzo' => '3', 'Abril' => '4', 'Mayo' => '5', 'Junio' => '6', 'Julio' => '7',
                    'Agosto' => '8', 'Septiembre' => '9', 'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12',
                ),
                'required'    => false,
                'placeholder' => '',
            ])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add('txtEmpleado', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformeAporteFechaDesde') ? date_create($session->get('filtroRhuInformeAporteFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformeAporteFechaHasta') ? date_create($session->get('filtroRhuInformeAporteFechaHasta')): null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuAporteAnio', $form->get('txtAnio')->getData());
                $session->set('filtroRhuAporteMes', $form->get('txtMes')->getData());
                $session->set('filtroRhuInformeAporteCodigoEmpleado',  $form->get('txtEmpleado')->getData());
                $session->set('filtroRhuInformeAporteFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuInformeAporteFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(RhuAporteDetalle::class)->informe())->execute(), "Aportes");
            }
        }
        $arAportes = $paginator->paginate($em->getRepository(RhuAporteDetalle::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/informe/seguridadsocial/aporte/lista.html.twig', [
            'arAportes' => $arAportes,
            'form' => $form->createView()
        ]);
    }
}