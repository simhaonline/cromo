<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina\Incapacidad;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuIncapacidadDiagnostico;
use App\Entity\RecursoHumano\RhuIncapacidadTipo;
use App\Entity\RecursoHumano\RhuPago;
use App\Form\Type\RecursoHumano\incapacionType;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IncapacidadController extends ControllerListenerGeneral
{
    protected $clase = RhuIncapacidad::class;
    protected $claseNombre = "RhuIncapacidad";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Incapacidad";

    /**
     * @Route("recursohumano/moviento/nomina/Incapacidad/lista", name="recursohumano_movimiento_nomina_incapacidad_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoPk', TextType::class, array('required' => false))
            ->add('numeroEps', TextType::class, array('required' => false))
            ->add('tipoIncapacidad', ChoiceType::class, ['choices' => ['TODOS' => '', 'GENERAL' => '1', 'LABORAL' => '0'], 'required' => false])
            ->add('entidadSaludRel', EntityType::class, [
                'class' => RhuEntidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.eps = 1')
                        ->orderBy('r.nombre', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('grupoRel', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('tipoLegalizada', ChoiceType::class, ['choices' => ['TODOS' => '', 'LEGALIZADA' => '1', 'SIN LEGALIZADA' => '0'], 'required' => false])
            ->add('estadoTranscripcion', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaIncapacidadDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuIncapacidadFechaDesde') ? date_create($session->get('filtroTteGuiaFechaIngresoDesde')): null])
            ->add('fechaIncapacidadHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuIncapacidadFechaHasta') ? date_create($session->get('filtroRhuIncapacidadFechaHasta')): null])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add( 'btnEliminar', SubmitType::class, ['label'=>'Eliminar', 'attr'=>['class'=> 'btn btn-sm btn-danger']])
          ->getForm();

        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuIncapacidadFechaDesde',  $form->get('fechaIncapacidadDesde')->getData() ?$form->get('fechaIncapacidadDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuIncapacidadFechaHasta', $form->get('fechaIncapacidadHasta')->getData() ? $form->get('fechaIncapacidadHasta')->getData()->format('Y-m-d'): null);
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arClienterSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuIncapacidad::class, $arClienterSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_incapacidad_lista'));
            }
        }

        $arIncapacidades = $paginator->paginate($em->getRepository(RhuIncapacidad::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/incapacidad/lista.html.twig', [
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/Incapacidad/nuevo/{id}", name="recursohumano_movimiento_nomina_incapacidad_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIncapacidad = new RhuIncapacidad();
        if ($id != 0) {
            $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($id);
            if (!$arIncapacidad) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_incapacidad_lista'));
            }
        }
        $form = $this->createForm(incapacionType::class, $arIncapacidad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $codigoEmpleado = $request->request->get('form_txtNumeroIdentificacion');
                $codigoIncapacidadDiagnostico = $request->request->get('form_txtCodigoIncapacidadDiagnostico');
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->findOneBy(['codigoEmpleadoPk'=>$id]);
                $arContrato = $em->getRepository(RhuContrato::class)->findOneBy(['codigoEmpleadoFk'=>$arEmpleado->getCodigoEmpleadoPk()]);
                $arIncapacidadDiagnostico = $em->getRepository(RhuIncapacidadDiagnostico::class)->find($codigoIncapacidadDiagnostico);
                if ($arEmpleado){
                    if ($arContrato){
                        if ($arIncapacidadDiagnostico){
                            $arIncapacidad = $form->getData();
                            $arIncapacidad->setFecha(new \DateTime('now'));
                            $arIncapacidad->setEmpleadoRel($arEmpleado);
                            $arIncapacidad->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                            $arIncapacidad->setIncapacidadDiagnosticoRel($arIncapacidadDiagnostico);
                            $em->persist($arIncapacidad);
                            $em->flush();
                        }
                    }else{
                        Mensajes::error("El empleado no tiene contrato");
                    }
                }else{
                    Mensajes::error("El empleado no existe");
                }



                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_incapacidad_detalle', ['id' => $arIncapacidad->getCodigoIncapacidadPk()]));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/incapacidad/nuevo.html.twig', [
            'form' => $form->createView(),
            'arIncapacidad' => $arIncapacidad,
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/Incapacidad/detalle/{id}", name="recursohumano_movimiento_nomina_incapacidad_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($id);
            if (!$arIncapacidad) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_incapacidad_incapacidad_lista'));
            }
        }
        $arIncapacidad = $em->getRepository(RhuIncapacidad::class)->find($id);
        $form = Estandares::botonera($arIncapacidad->getEstadoAutorizado(), $arIncapacidad->getEstadoAprobado(), $arIncapacidad->getEstadoAnulado());

        return $this->render('recursohumano/movimiento/nomina/incapacidad/detalle.html.twig', [
            'arIncapacidad' => $arIncapacidad,
            'form'=>$form->createView()
        ]);

    }

}