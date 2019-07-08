<?php


namespace App\Controller\RecursoHumano\Informe;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\General\GenModulo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuRequisito;
use Doctrine\ORM\EntityRepository;
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
     * @Route("/recursohumano/informe/nomina/pagodetalle/lista", name="recursohumano_informe_nomina_pagodetalle_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtEmpleado', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoFechaDesde') ? date_create($session->get('filtroInvInformeAsesorVentasFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoFechaHasta') ? date_create($session->get('filtroInvInformeAsesorVentasFechaHasta')): null])
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
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arConcepto = $form->get('concepto')->getData();
                if($arConcepto) {
                    $session->set('filtroRhuInformePagoConcepto', $arConcepto->getCodigoConceptoPk());
                } else {
                    $session->set('filtroRhuInformePagoConcepto', null);
                }
                $session->set('filtroRhuInformePagoFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuInformePagoFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
        }
        $arPagoDetalles = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->informe(), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/informe/nomina/pagodetalle/lista.html.twig', [
            'arPagoDetalles' => $arPagoDetalles,
            'form' => $form->createView()
        ]);
	}
}