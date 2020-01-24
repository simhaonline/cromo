<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Form\Type\RecursoHumano\PagoType;
use App\Formato\RecursoHumano\Pago;
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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PagoController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "RhuPago";

    protected $clase = RhuPago::class;
    protected $claseFormulario = PagoType::class;
    protected $claseNombre = "RhuPago";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Pago";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/pago/lista", name="recursohumano_movimiento_nomina_pago_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoPagoTipoFk', EntityType::class, [
                'class' => RhuPagoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.codigoPagoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoGrupoFk', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nombre', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoPagoPk', TextType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('numero', TextType::class, ['required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoContabilizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnContabilizar', SubmitType::class, ['label' => 'Contabilizar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->setMethod('GET')
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
                General::get()->setExportar($em->getRepository(RhuPago::class)->lista($raw), "Pagos");
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuPago::class)->contabilizar($arrSeleccionados);
            }
        }
        $arPagos = $paginator->paginate($em->getRepository(RhuPago::class)->lista($raw), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/movimiento/nomina/pago/lista.html.twig', [
            'arPagos' => $arPagos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/pago/nuevo/{id}", name="recursohumano_movimiento_nomina_pago_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        Mensajes::error('Esta funcion aun no esta disponible');
        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_pago_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/pago/detalle/{id}", name="recursohumano_movimiento_nomina_pago_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPago = $em->find(RhuPago::class, $id);
        $form = Estandares::botonera($arPago->getEstadoAutorizado(), $arPago->getEstadoAprobado(), $arPago->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormato = new Pago();
                $objFormato->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                //$em->getRepository(RhuPago::class)->liquidarProvision(['codigoPagoPk' => $arPago->getCodigoPagoPk()]);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_pago_detalle', ['id' => $id]));
            }
        }
        $arPagoDetalles = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->lista($id), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/pago/detalle.html.twig', [
            'arPago' => $arPago,
            'arPagoDetalles' => $arPagoDetalles,
            'clase' => array('clase' => 'RhuPago', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/pago/programacion/{id}", name="recursohumano_movimiento_nomina_pago_programacion")
     */
    public function verProgramacion(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPago = $em->getRepository(RhuPago::class)->find($id);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(RhuProgramacionDetalle::class)->actualizar($arProgramacionDetalle, $this->getUser()->getUsername());
            }*/
        }

        $arProgramaciones = $em->getRepository(TurProgramacion::class)->programacionEmpleado($arPago->getCodigoEmpleadoFk(), $arPago->getFechaDesde()->format('Y'), $arPago->getFechaHasta()->format('n'));
        return $this->render('recursohumano/movimiento/nomina/pago/verTurnos.html.twig', [
            'arPago' => $arPago,
            'arProgramaciones' => $arProgramaciones,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoPago' => $form->get('codigoPagoPk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoContabilizado' => $form->get('estadoContabilizado')->getData(),
        ];

        $arPagoTipo = $form->get('codigoPagoTipoFk')->getData();
        $arGrupo = $form->get('codigoGrupoFk')->getData();

        if (is_object($arPagoTipo)) {
            $filtro['pagoTipo'] = $arPagoTipo->getCodigoPagoTipoPk();
        } else {
            $filtro['pagoTipo'] = $arPagoTipo;
        }

        if (is_object($arGrupo)) {
            $filtro['codigoGrupo'] = $arGrupo->getCodigoGrupoPk();
        } else {
            $filtro['codigoGrupo'] = $arGrupo;
        }

        return $filtro;

    }

}