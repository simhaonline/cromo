<?php

namespace App\Controller\General\Administracion\Tarea;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\General\GenTarea;
use App\Entity\General\GenTareaPrioridad;
use App\Entity\Seguridad\Usuario;
use App\Form\Type\General\TareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class TareaController extends ControllerListenerGeneral
{
    protected $clase = GenTarea::class;
    protected $claseNombre = "GenTarea";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "Tarea";
    protected $nombre = "Tarea";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/general/administracion/tarea/tarea/recibida", name="general_administracion_tarea_tarea_recibida")
     */
    public function misTareas(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroGenTareaRecibidaFechaDesde') ? date_create($session->get('filtroGenTareaRecibidaFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroGenTareaRecibidaFechaHasta') ? date_create($session->get('filtroGenTareaRecibidaFechaHasta')): null])
            ->add('cboTareaPrioridadRel', EntityType::class, $em->getRepository(GenTareaPrioridad::class)->llenarCombo())
            ->add('chkEstadoTerminado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroGenTareaRecibida'), 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroGenTareaRecibidaFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
            $session->set('filtroGenTareaRecibidaFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            $session->set('filtroGenTareaRecibida', $form->get('chkEstadoTerminado')->getData());
            if ($form->get('cboTareaPrioridadRel')->getData() != '') {
                $session->set('filtroGenTareaPrioridad', $form->get('cboTareaPrioridadRel')->getData()->getCodigoTareaPrioridadPk());
            } else {
                $session->set('filtroGenTareaPrioridad', null);
            }
        }
        $arTareasRecibidas = $paginator->paginate($em->getRepository(GenTarea::class)->tareasRecibidas($this->getUser()->getUsername()), $request->query->getInt('page', 1), 50);
        return $this->render('general/administracion/tarea/tarea/misTareas.html.twig',
            ['arTareasRecibidas' => $arTareasRecibidas,
                'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/general/administracion/tarea/tarea/nuevo/{id}", name="general_administracion_tarea_tarea_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTarea = new GenTarea();
        if ($id != 0) {
            $arTarea = $em->find(GenTarea::class, $id);
        } else {
            $arTarea->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(TareaType::class, $arTarea);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arTarea->setUsuarioAsigna($this->getUser()->getUsername());
//            $arTarea->setUsuarioRecibe($arTarea->getUsuarioRecibeRel()->getUsername());
            $em->persist($arTarea);
            $em->flush();
            return $this->redirect($this->generateUrl('general_administracion_tarea_tarea_recibida'));
        }
        return $this->render('general/administracion/tarea/tarea/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/general/administracion/tarea/tarea/nuevo/{id}", name="general_administracion_tarea_tarea_detalle")
     */
    public function detalle($id)
    {
        return $this->redirect($this->generateUrl('general_administracion_tarea_tarea_recibida'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/general/administracion/tarea/tarea/asignada", name="general_administracion_tarea_tarea_asignada")
     */
    public function tareasAsignadas(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroGenTareaAsiganadaFechaDesde') ? date_create($session->get('filtroGenTareaAsiganadaFechaDesde')): null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroGenTareaAsiganadaFechaHasta') ? date_create($session->get('filtroGenTareaAsiganadaFechaHasta')): null])
            ->add('cboTareaPrioridadRel', EntityType::class, $em->getRepository(GenTareaPrioridad::class)->llenarCombo())
            ->add('cboUsuario', EntityType::class, $em->getRepository(Usuario::class)->llenarCombo())
            ->add('chkEstadoTerminado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroGenTareaAsignada'), 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroGenTareaAsiganadaFechaDesde',  $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null);
            $session->set('filtroGenTareaAsiganadaFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d'): null);
            $session->set('filtroGenTareaAsignada', $form->get('chkEstadoTerminado')->getData());
            if ($form->get('cboTareaPrioridadRel')->getData() != '') {
                $session->set('filtroGenTareaPrioridad', $form->get('cboTareaPrioridadRel')->getData()->getCodigoTareaPrioridadPk());
            } else {
                $session->set('filtroGenTareaPrioridad', null);
            }
            if ($form->get('cboUsuario')->getData() != '') {
                $session->set('filtroUsuario', $form->get('cboUsuario')->getData()->getUsername());
            } else {
                $session->set('filtroUsuario', null);
            }
        }
        $arTareasAsignadas = $paginator->paginate($em->getRepository(GenTarea::class)->tareasAsignadas($this->getUser()->getUsername()), $request->query->getInt('page', 1), 50);
        return $this->render('general/administracion/tarea/tarea/asignadas.html.twig',
            ['arTareasAsignadas' => $arTareasAsignadas,
                'form' => $form->createView()]);
    }
}
