<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use App\Entity\RecursoHumano\RhuEmbargoTipo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\EmbargoType;
use App\General\General;
use App\Utilidades\Estandares;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmbargoController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "RhuEmbargo";

    protected $clase = RhuEmbargo::class;
    protected $claseFormulario = EmbargoType::class;
    protected $claseNombre = "RhuEmbargo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Embargo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/embargo/lista", name="recursohumano_movimiento_nomina_embargo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmbargoTipoFk', EntityType::class, [
                'class' => RhuEmbargoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.codigoEmbargoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoEmbargoPk', TextType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('estadoActivo', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
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
                General::get()->setExportar($em->getRepository(RhuEmbargo::class)->lista($raw), "Embargos");
            }
        }
        $arEmbargos = $paginator->paginate($em->getRepository(RhuEmbargo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/embargo/lista.html.twig', [
            'arEmbargos' => $arEmbargos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/embargo/nuevo/{id}", name="recursohumano_movimiento_nomina_embargo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargo = new RhuEmbargo();
        if ($id != 0) {
            $arEmbargo = $em->getRepository(RhuEmbargo::class)->find($id);
        }
        $form = $this->createForm(EmbargoType::class, $arEmbargo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arEmbargo->getCodigoEmpleadoFk());
                $arJuzgado = $em->getRepository(RhuEmbargoJuzgado::class)->find($arEmbargo->getCodigoEmbargoJuzgadoFk());
                if ($id == 0) {
                    $arEmbargo->setEstadoActivo(1);
                    $arEmbargo->setFecha(new \DateTime('now'));
                }
                $arEmbargo->setEmpleadoRel($arEmpleado);
                $arEmbargo->setEmbargoJuzgadoRel($arJuzgado);
                $em->persist($arEmbargo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_embargo_detalle', ['id' => $arEmbargo->getCodigoEmbargoPK()]));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/embargo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arEmbargo' => $arEmbargo
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/embargo/detalle/{id}", name="recursohumano_movimiento_nomina_embargo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargo = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arEmbargo->getEstadoAutorizado(), $arEmbargo->getEstadoAprobado(), $arEmbargo->getEstadoAnulado());
        $form->handleRequest($request);
        return $this->render('recursohumano/movimiento/nomina/embargo/detalle.html.twig', [
            'arEmbargo' => $arEmbargo
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoEmbargo' => $form->get('codigoEmbargoPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoActivo' => $form->get('estadoActivo')->getData(),
        ];

        $arEmbargoTipo = $form->get('codigoEmbargoTipoFk')->getData();

        if (is_object($arEmbargoTipo)) {
            $filtro['embargoTipo'] = $arEmbargoTipo->getCodigoEmbargoTipoPk();
        } else {
            $filtro['embargoTipo'] = $arEmbargoTipo;
        }

        return $filtro;

    }
}

