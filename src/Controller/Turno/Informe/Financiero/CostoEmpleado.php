<?php


namespace App\Controller\Turno\Informe\Financiero;


use App\Controller\Estructura\FuncionesController;

use App\Entity\Turno\TurCostoEmpleadoServicio;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CostoEmpleado extends Controller
{
    /**
     * @Route("/turno/informe/financiero/costo/empleado/lista", name="turno_informe_financiero_costo_empleado_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $dateFecha = (new \DateTime('now'));
        $arrDiaSemana = "";
        $session->set('filtroTurProgramacionAnio', $dateFecha->format("Y"));
        $session->set('filtroTurProgramacionMes', $dateFecha->format("m"));
        $form = $this->createFormBuilder()
            ->add('txtAnio', TextType::class, ['required' => false, 'data' => $session->get('filtroTurCostoEmpleadoServicioAnio'), 'attr' => ['class' => 'form-control']])
            ->add('txtMes', TextType::class, ['required' => false, 'data' => $session->get('filtroTurCostoEmpleadoServicioMes'), 'attr' => ['class' => 'form-control']])
            ->add('txtEmpleado', TextType::class, ['required' => false, 'data' => $session->get('filtroTurCostoEmpleadoServicioCodigoEmpleado'), 'attr' => ['class' => 'form-control']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurProgramacionAnio', $form->get('txtAnio')->getData());
                $session->set('filtroTurProgramacionMes', $form->get('txtMes')->getData());
                $session->set('filtroRhuEmpleadoCodigoEmpleado', $form->get('txtEmpleado')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurProgramacion::class)->programaciones(), "programaciones");
            }
        }
        $arCostosEmpleadoServicio = $paginator->paginate($em->getRepository(TurCostoEmpleadoServicio::class)->informe(), $request->query->getInt('page', 1), 1000);
        return $this->render('turno/informe/financiero/costoEmpleado.html.twig', [
            'arCostosEmpleadoServicio' => $arCostosEmpleadoServicio,
            'form' => $form->createView()
        ]);
    }

}