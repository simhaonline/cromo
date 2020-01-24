<?php

namespace App\Controller\RecursoHumano\Movimiento\Recurso\Visita;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\RecursoHumano\RhuVisitaTipo;
use App\Form\Type\RecursoHumano\CreditoPagoType;
use App\Form\Type\RecursoHumano\CreditoType;
use App\Form\Type\RecursoHumano\VisitaType;
use App\Formato\RecursoHumano\Credito;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VisitaController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "RhuVisita";


    protected $clase = RhuVisita::class;
    protected $claseFormulario = VisitaType::class;
    protected $claseNombre = "RhuVisita";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Recurso";
    protected $nombre = "Visita";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/recurso/visita/lista", name="recursohumano_movimiento_recurso_visita_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoVisitaTipoFk', EntityType::class, [
                'class' => RhuVisitaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('vt')
                        ->orderBy('vt.codigoVisitaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoVisitaPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuVisita::class)->lista($raw), "Visitas");
            }
        }
        $arVisitas = $paginator->paginate($em->getRepository(RhuVisita::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/recurso/visita/lista.html.twig', [
            'arVisitas' => $arVisitas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/visita/nuevo/{id}", name="recursohumano_movimiento_recurso_visita_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVisita = new RhuVisita();
        if ($id != 0) {
            $arVisita = $em->getRepository($this->clase)->find($id);
        } else {
            $arVisita->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(VisitaType::class, $arVisita);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arVisita->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    $arContrato = null;
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                        if ($arContrato != null) {
                            if ($id == 0) {
                                $arVisita->setFecha(new \DateTime('now'));
                            }
                            $arVisita->setFechaCreacion(new \DateTime('now'));
                            $arVisita->setEmpleadoRel($arEmpleado);
                            $arVisita->setNombreCorto($arEmpleado->getNombreCorto());
                            $arVisita->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                            $em->persist($arVisita);
                            $em->flush();
                            return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_visita_detalle', ['id' => $arVisita->getCodigoVisitaPk()]));
                        } else {
                            Mensajes::error('El empleado no tiene contratos en el sistema');
                        }
                    } else {
                        Mensajes::error('No se ha encontrado un empleado con el codigo ingresado');
                    }
                }
            }
        }
        return $this->render('recursohumano/movimiento/recurso/visita/nuevo.html.twig', [
            'form' => $form->createView(),
            'arVisita' => $arVisita
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/recurso/visita/detalle/{id}", name="recursohumano_movimiento_recurso_visita_detalle")
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
        return $this->render('recursohumano/movimiento/recurso/visita/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoVisita' => $form->get('codigoVisitaPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arVisitaTipo = $form->get('codigoVisitaTipoFk')->getData();

        if (is_object($arVisitaTipo)) {
            $filtro['visitaTipo'] = $arVisitaTipo->getCodigoVisitaTipoPk();
        } else {
            $filtro['visitaTipo'] = $arVisitaTipo;
        }

        return $filtro;

    }
}

