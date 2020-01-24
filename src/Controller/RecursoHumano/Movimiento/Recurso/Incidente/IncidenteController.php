<?php

namespace App\Controller\RecursoHumano\Movimiento\Recurso\Incidente;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuIncidente;
use App\Entity\RecursoHumano\RhuIncidenteTipo;
use App\Entity\RecursoHumano\RhuInduccion;
use App\Entity\RecursoHumano\RhuPermiso;
use App\Form\Type\RecursoHumano\IncidenteType;
use App\Form\Type\RecursoHumano\InduccionType;
use App\Form\Type\RecursoHumano\PermisoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IncidenteController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "RhuIncidenteController";


    protected $clase = RhuIncidente::class;
    protected $claseFormulario = IncidenteType::class;
    protected $claseNombre = "RhuIncidente";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Recurso";
    protected $nombre = "Incidente";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/recurso/incidente/lista", name="recursohumano_movimiento_recurso_incidente_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoIncidenteTipoFk', EntityType::class, [
                'class' => RhuIncidenteTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.codigoIncidenteTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoIncidentePk', TextType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuIncidente::class)->lista($raw), "Incidentes");
            }
        }
        $arIncidentes = $paginator->paginate($em->getRepository(RhuIncidente::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/recurso/incidente/lista.html.twig', [
            'arIncidentes' => $arIncidentes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/incidente/nuevo/{id}", name="recursohumano_movimiento_recurso_incidente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arIncidente = new RhuIncidente();
        if ($id != 0) {
            $arIncidente = $em->getRepository($this->clase)->find($id);
        } else {
            $arIncidente->setFecha(new \DateTime('now'));
            $arIncidente->setFechaNovedad(new \DateTime('now'));
            $arIncidente->setFechaHoraCitacionDescargo(new \DateTime('now'));
            $arIncidente->setFechaHoraNotificacion(new \DateTime('now'));
        }
        $form = $this->createForm(IncidenteType::class, $arIncidente);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arIncidente->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    $arContrato = null;
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                        if ($arContrato != null) {
                            if ($id == 0) {
                                $arIncidente->setFecha(new \DateTime('now'));
                            }
                            $arIncidente->setFecha(new \DateTime('now'));
                            $arIncidente->setEmpleadoRel($arEmpleado);
                            $em->persist($arIncidente);
                            $em->flush();
                            return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_incidente_detalle', ['id' => $arIncidente->getCodigoIncidentePk()]));
                        } else {
                            Mensajes::error('El empleado no tiene contratos en el sistema');
                        }
                    } else {
                        Mensajes::error('El emplado no tiene contrato');
                    }
                }
            }
        }
        return $this->render('recursohumano/movimiento/recurso/incidente/nuevo.html.twig', [
            'form' => $form->createView(),
            'arIncidente' => $arIncidente
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/incidente/detalle/{id}", name="recursohumano_movimiento_recurso_incidente_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arRegistro->getEstadoAutorizado(), $arRegistro->getEstadoAprobado(), $arRegistro->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        return $this->render('recursohumano/movimiento/recurso/incidente/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoIncidente' => $form->get('codigoIncidentePk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arIncidenteTipo = $form->get('codigoIncidenteTipoFk')->getData();

        if (is_object($arIncidenteTipo)) {
            $filtro['incidenteTipo'] = $arIncidenteTipo->getCodigoIncidenteTipoPk();
        } else {
            $filtro['incidenteTipo'] = $arIncidenteTipo;
        }

        return $filtro;

    }
}

