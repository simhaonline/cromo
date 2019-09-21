<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuReclamo;
use App\Entity\RecursoHumano\RhuReclamoConcepto;
use App\Form\Type\RecursoHumano\ReclamoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReclamoController extends AbstractController
{
    protected $class = RhuReclamo::class;
    protected $claseNombre = "RhuReclamo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Reclamo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/reclamo/lista", name="recursohumano_movimiento_nomina_reclamo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoReclamoConceptoFk', EntityType::class, [
                'class' => RhuReclamoConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rc')
                        ->orderBy('rc.codigoReclamoConceptoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoReclamoPk', IntegerType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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
                General::get()->setExportar($em->getRepository(RhuReclamo::class)->lista($raw), "Reclamos");
            }
        }
        $arReclamos = $paginator->paginate($em->getRepository(RhuReclamo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/reclamo/lista.html.twig', [
            'arReclamos' => $arReclamos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/reclamo/nuevo/{id}", name="recursohumano_movimiento_nomina_reclamo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arReclamo = new RhuReclamo();
        if ($id != 0) {
            $arReclamo = $em->getRepository(RhuReclamo::class)->find($id);
            if (!$arReclamo) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_reclamo_lista'));
            }
        } else {
            $arReclamo->setFecha(new \DateTime('now'));
            $arReclamo->setUsuario($this->getUser()->getUsername());
        }

        $form = $this->createForm(ReclamoType::class, $arReclamo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($arReclamo->getCodigoEmpleadoFk() != '') {
                    $arEmpleado = $em->find(RhuEmpleado::class, $arReclamo->getCodigoEmpleadoFk());
                    if ($arEmpleado) {
                        $arReclamo->setEmpleadoRel($arEmpleado);
                        $em->persist($arReclamo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_reclamo_detalle', ['id' => $arReclamo->getCodigoReclamoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un empleado');
                }

            }
        }
        return $this->render('recursohumano/movimiento/nomina/reclamo/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/reclamo/detalle/{id}", name="recursohumano_movimiento_nomina_reclamo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arReclamo = $em->getRepository($this->class)->find($id);
        $form = Estandares::botonera($arReclamo->getEstadoAutorizado(), $arReclamo->getEstadoAprobado(), $arReclamo->getEstadoAnulado());
        $form->handleRequest($request);
        return $this->render('recursohumano/movimiento/nomina/reclamo/detalle.html.twig', [
            'arReclamo' => $arReclamo,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoReclamo' => $form->get('codigoReclamoPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arReclamoConcepto = $form->get('codigoReclamoConceptoFk')->getData();

        if (is_object($arReclamoConcepto)) {
            $filtro['reclamoConcepto'] = $arReclamoConcepto->getCodigoReclamoConceptoPk();
        } else {
            $filtro['reclamoConcepto'] = $arReclamoConcepto;
        }

        return $filtro;

    }
}

