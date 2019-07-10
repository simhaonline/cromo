<?php


namespace App\Controller\Turno\Informe\Juridico;


use App\Entity\Turno\TurContrato;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class contratoController extends  Controller
{
    /**
     * @Route("/turno/informe/juridico/contrato/lista", name="turno_informe_juridico_contrato_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtEmpleado', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurInformeContratoFechaDesde') ? date_create($session->get('filtroTurInformeContratoFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTurInformeContratoFechaHasta') ? date_create($session->get('filtroTurInformeContratoFechaHasta')): null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurInformeContratoCodigoEmpleado',  $form->get('txtEmpleado')->getData());
                $session->set('filtroTurInformeContratoFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroTurInformeContratoFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
        }
        $arContratos = $paginator->paginate($em->getRepository(TurContrato::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/juridico/contrato/lista.html.twig', [
                'arContratos' => $arContratos,
                'form' => $form->createView()
            ]
        );
    }
}