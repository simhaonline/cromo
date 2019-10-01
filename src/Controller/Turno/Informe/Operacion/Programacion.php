<?php


namespace App\Controller\Turno\Informe\Operacion;


use App\Controller\Estructura\FuncionesController;

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

class Programacion extends Controller
{
    /**
     * @Route("/turno/informe/operacion/programacion/lista", name="turno_informe_operacion_programacion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $dateFecha = (new \DateTime('now'));
        $arrDiaSemana = "";
        $form = $this->createFormBuilder()
            ->add('txtAnio', TextType::class, ['required' => false, 'data' => $session->get('filtroTurProgramacionAnio'), 'attr' => ['class' => 'form-control']])
            ->add('txtMes', TextType::class, ['required' => false, 'data' => $session->get('filtroTurProgramacionMes'), 'attr' => ['class' => 'form-control']])
            ->add('txtEmpleado', TextType::class, ['required' => false, 'data' => $session->get('filtroRhuEmpleadoCodigoEmpleado'), 'attr' => ['class' => 'form-control']])
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
                General::get()->setExportar($em->getRepository(TurProgramacion::class)->programaciones()->execute(), "programaciones");
            }
        }
        $arrDiaSemana = FuncionesController::diasMes($dateFecha, $em->getRepository(TurFestivo::class)->festivos($dateFecha->format('Y-m-') . '01', $dateFecha->format('Y-m-t')));
        $arProgramaciones = $paginator->paginate($em->getRepository(TurProgramacion::class)->programaciones(), $request->query->getInt('page', 1), 30);
        return $this->render('turno/informe/operacion/programacion/programacion.html.twig', [
            'arProgramaciones' => $arProgramaciones,
            'arrDiaSemana' => $arrDiaSemana,
            'form' => $form->createView()
        ]);
    }

}