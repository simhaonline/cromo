<?php


namespace App\Controller\Turno\Administracion\Operacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Turno\TurTurno;
use App\Form\Type\Turno\TurnoType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class TurnoController extends ControllerListenerGeneral
{
    protected $clase = TurTurno::class;
	protected $claseFormulario = TurnoType::class;
	protected $claseNombre = "TurTurno";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
	protected $grupo = "Operacion";
	protected $nombre = "Turno";

    /**
     * @Route("/turno/administracion/operacion/turno/lista", name="turno_administracion_operacion_turno_lista")
     */
	public function lista(Request $request)
    {
        $session = new Session();
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtTurno', TextType::class, ['required' => false, 'data' => $session->get('filtroTurTurnoCodigoTurno')])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroTurTurnoNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurTurnoCodigoTurno', $form->get('txtTurno')->getData());
                $session->set('filtroTurTurnoNombre', $form->get('txtNombre')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurTurno::class, $arrSeleccionados);
				return $this->redirect($this->generateUrl('turno_administracion_operacion_turno_lista'));
			}
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                General::get()->setExportar($em->getRepository(TurTurno::class)->lista(), "Turnos");
            }
        }
        $arTurnos = $paginator->paginate($em->getRepository(TurTurno::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('turno/administracion/operacion/turno/lista.html.twig', [
            'arTurnos' => $arTurnos,
            'form' => $form->createView()
        ]);
	}

	/**
     * @Route("/turno/administracion/operacion/turno/nuevo/{id}", name="turno_administracion_operacion_turno_nuevo")
     */
   public function nuevo(Request $request, $id)
   {
       $em = $this->getDoctrine()->getManager();
       $arTurno = new TurTurno();

       if ($id != "") {
           $arTurno = $em->getRepository(TurTurno::class)->find($id);
       }
       $form = $this->createForm(turnoType::class, $arTurno);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           if ($form->get('guardar')->isClicked()) {
               $arTurno = $form->getData();
               $em->persist($arTurno);
               $em->flush();
               return $this->redirect($this->generateUrl('turno_administracion_operacion_turno_detalle', ['id' => $arTurno->getCodigoTurnoPk()]));
           }
       }
       return $this->render('turno/administracion/operacion/turno/nuevo.html.twig', [
           'form' => $form->createView(),
           'arTurno' => $arTurno
       ]);
   }

  /**
   * @Route("/turno/administracion/operacion/turno/detalle/{id}", name="turno_administracion_operacion_turno_detalle")
   */
	public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != "") {
            $arTurno = $em->getRepository(TurTurno::class)->find($id);
            if (!$arTurno) {
                return $this->redirect($this->generateUrl('turno_administracion_operacion_turno_lista'));
            }
        }
        $arTurno = $em->getRepository(TurTurno::class)->find($id);
        return $this->render('turno/administracion/operacion/turno/detalle.html.twig', [
            'arTurno' => $arTurno
        ]);
    }
}