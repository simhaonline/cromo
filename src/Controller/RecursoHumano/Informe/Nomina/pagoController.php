<?php


namespace App\Controller\RecursoHumano\Informe\Nomina;


use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class pagoController extends  Controller
{
    /**
     * @Route("/recursohumano/informe/nomina/pago/lista", name="recursohumano_informe_nomina_pago_lista")
     */
    public function lista(Request $request)
    {
        ini_set('memory_limit', '256M');
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtEmpleado', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoFechaDesde') ? date_create($session->get('filtroRhuInformePagoFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuInformePagoFechaHasta') ? date_create($session->get('filtroRhuInformePagoFechaHasta')): null])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuInformePagoCodigoEmpleado',  $form->get('txtEmpleado')->getData());
                $session->set('filtroRhuInformePagoFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuInformePagoFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            }
        }
        $arPagos = $paginator->paginate($em->getRepository(RhuPago::class)->informe(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/informe/nomina/pago/lista.html.twig', [
            'arPagos' => $arPagos,
            'form' => $form->createView()
        ]);
    }
}