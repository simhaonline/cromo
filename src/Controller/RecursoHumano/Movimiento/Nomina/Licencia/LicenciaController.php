<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina\Licencia;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuLicenciaTipo;
use App\Form\Type\RecursoHumano\LicenciaType;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class LicenciaController extends ControllerListenerGeneral
{
    protected $clase = RhuLicencia::class;
    protected $claseNombre = "RhuLicencia";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Licencia";
    protected $nombre = "Licencia";

    /**
     * @Route("recursohumano/moviento/nomina/licencia/lista", name="recursohumano_movimiento_nomina_licencia_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoPk', TextType::class, array('required' => false))
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
            ->add('licenciaTipo', EntityType::class, [
                'class' => RhuLicenciaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.codigoLicenciaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('licenciaFechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuLicenciaFechaDesde') ? date_create($session->get('filtroRhuLicenciaFechaDesde')): null])
            ->add('licenciafechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroRhuLicenciaFechaHasta') ? date_create($session->get('filtroRhuLicenciaFechaHasta')): null])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add( 'btnEliminar', SubmitType::class, ['label'=>'Eliminar', 'attr'=>['class'=> 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $codigoEmpleado =$form->get('codigoEmpleadoPk')->getData();
                $arGrupo =$form->get('grupoRel')->getData();
                $arLicenciaTipo =$form->get('licenciaTipo')->getData();
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado??0);
                if ($arEmpleado){
                    $session->set('filtroRhuLicenciaCodigoEmpleado',  $arEmpleado->getCodigoEmpleadoPk());
                }else{
                    $session->set('filtroRhuLicenciaCodigoEmpleado',  null);
                }
                if ($arLicenciaTipo){
                    $session->set('filtroRhuLicenciaLiencenciaTipo',  $arLicenciaTipo->getCodigoLicenciaTipoPk());
                }else{
                    $session->set('filtroRhuLicenciaLiencenciaTipo',  null);
                }
                if ($arGrupo){
                    $session->set('filtroRhuLicenciaCodigoGrupo',  $arGrupo->getCodigoGrupoPk());
                }else{
                    $session->set('filtroRhuLicenciaCodigoGrupo',  null);
                }
                $session->set('filtroRhuLicenciaFechaDesde',  $form->get('licenciaFechaDesde')->getData() ?$form->get('licenciaFechaDesde')->getData()->format('Y-m-d'): null);
                $session->set('filtroRhuLicenciaFechaHasta', $form->get('licenciafechaHasta')->getData() ? $form->get('licenciafechaHasta')->getData()->format('Y-m-d'): null);
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arLicenicasSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuLicencia::class, $arLicenicasSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        }

        $arLicencias = $paginator->paginate($em->getRepository(RhuLicencia::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/licencia/lista.html.twig', [
            'arLicencias' => $arLicencias,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("recursohumano/moviento/nomina/licencia/nuevo/{id}", name="recursohumano_movimiento_nomina_licencia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arLicencia = new RhuLicencia();
        if ($id != 0) {
            $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);

            if (!$arLicencia) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        }
        $form = $this->createForm(LicenciaType::class, $arLicencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $codigoEmpleado = $request->request->get('form_txtNumeroIdentificacion');
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->findOneBy(['codigoEmpleadoPk'=>$codigoEmpleado]);
                $arContrato = $em->getRepository(RhuContrato::class)->findOneBy(['codigoEmpleadoFk'=>$arEmpleado->getCodigoEmpleadoPk()]);
                if ($arEmpleado){
                    $arLicencia = $form->getData();
                    $arLicencia->setFecha(new \DateTime('now'));
                    $arLicencia->setEmpleadoRel($arEmpleado);
                    $arLicencia->setGrupoRel($arContrato->getGrupoRel());
                    $arLicencia->setEntidadSaludRel($arContrato->getEntidadSaludRel());
                    $arLicencia->setCodigoUsuario($this->getUser()->getUserName());
                    $em->persist($arLicencia);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista', ['id' => $arLicencia->getCodigoLicenciaPk()]));
                }else{
                    Mensajes::error("El empleado no existe");
                }

            }
        }
        return $this->render('recursohumano/movimiento/nomina/licencia/nuevo.html.twig', [
            'form' => $form->createView(),
            'arIncapacidad' => $arLicencia,
        ]);
    }

    /**
     * @Route("recursohumano/moviento/nomina/licencia/detalle/{id}", name="recursohumano_movimiento_nomina_licencia_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);
            if (!$arLicencia) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_licencia_lista'));
            }
        }
        $arLicencia = $em->getRepository(RhuLicencia::class)->find($id);
        return $this->render('recursohumano/movimiento/nomina/licencia/detalle.html.twig', [
            'arLicencia' => $arLicencia
        ]);

    }

}