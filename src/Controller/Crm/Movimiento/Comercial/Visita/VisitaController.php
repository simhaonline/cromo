<?php

namespace App\Controller\Crm\Movimiento\Comercial\Visita;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Crm\CrmContacto;
use App\Entity\Crm\CrmVisita;
use App\Entity\Crm\CrmVisitaReporte;
use App\Entity\Crm\CrmVisitaTipo;
use App\Form\Type\Crm\VisitaReporteType;
use App\Form\Type\Crm\VisitaType;
use App\Formato\Crm\Visita;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class VisitaController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "CrmVisita";


    protected $clase = CrmVisita::class;
    protected $claseNombre = "CrmVisita";
    protected $modulo = "Crm";
    protected $funcion = "Movimiento";
    protected $grupo = "Control";
    protected $nombre = "Visita";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/crm/movimiento/control/visita/lista", name="crm_movimiento_control_visita_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoVisitaTipoFk', EntityType::class, [
                'class' => CrmVisitaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('vt')
                        ->orderBy('vt.codigoVisitaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoContactoFk', EntityType::class, [
                'class' => CrmContacto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoContactoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombreCorto',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoVisitaPk', TextType::class, array('required' => false))
            ->add('txtCodigoCliente', TextType::class, ['required' => false])
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
                General::get()->setExportar($em->getRepository(CrmVisita::class)->lista($raw), "Visitas");
            }
        }
        $arVisitas = $paginator->paginate($em->getRepository(CrmVisita::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('crm/movimiento/control/visita/lista.html.twig', [
            'arVisitas' => $arVisitas,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/control/visita/nuevo/{id}", name="crm_movimiento_control_visita_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $arVisita = new CrmVisita();
            $arVisita->setFecha(new \DateTime('now'));
        } else {
            $arVisita = $em->getRepository(CrmVisita::class)->find($id);
        }
        $form = $this->createForm(VisitaType::class, $arVisita);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arVisita->setComentarios($form->get('comentarios')->getData());
                $em->persist($arVisita);
                $em->flush();
                return $this->redirect($this->generateUrl('crm_movimiento_control_visita_detalle', ['id' => $arVisita->getCodigoVisitaPk()]));
            }
        }
        return $this->render('crm/movimiento/control/visita/nuevo.html.twig', [
            'arServicio' => $arVisita,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/control/visita/detalle/{id}", name="crm_movimiento_control_visita_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVisita = $em->getRepository(CrmVisita::class)->find($id);
        $form = Estandares::botonera($arVisita->getEstadoAutorizado(), $arVisita->getEstadoAprobado(), $arVisita->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Visita();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->autorizar($arVisita);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->desautorizar($arVisita);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->aprobar($arVisita);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->anular($arVisita);
            }
            return $this->redirect($this->generateUrl('crm_movimiento_control_visita_detalle', ['id' => $arVisita->getCodigoVisitaPk()]));
        }
        $arReportes = $em->getRepository(CrmVisitaReporte::class)->reporte($id);
        return $this->render('crm/movimiento/control/visita/detalle.html.twig', [
            'clase' => array(
                'clase' => 'CrmVisita', 'codigo' => $id),
            'arVisita' => $arVisita,
            'arReportes' => $arReportes,
            'form' => $form->createView()]);

    }

    /**
     * @param Request $request
     * @param $codigoVisita
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/control/visita/detalle/reporte/{codigoVisita}/{id}", name="crm_movimiento_control_visita_detalle_reporte")
     */
    public function reporte(Request $request, $codigoVisita, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arReporte = new CrmVisitaReporte();
        $arVisita = $em->getRepository(CrmVisita::class)->find($codigoVisita);
        if ($id != 0) {
            $arReporte = $em->getRepository(CrmVisitaReporte::class)->find($id);
        }
        $form = $this->createForm(VisitaReporteType::class, $arReporte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arReporte->setVisitaRel($arVisita);
                $arReporte->setFecha(new \DateTime('now'));
                $em->persist($arReporte);
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }
        return $this->render('crm/movimiento/control/visita/reporte.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoVisita' => $form->get('codigoVisitaPk')->getData(),
            'codigoCliente' => $form->get('txtCodigoCliente')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arVisitaTipo = $form->get('codigoVisitaTipoFk')->getData();
        $arContacto = $form->get('codigoContactoFk')->getData();

        if (is_object($arVisitaTipo)) {
            $filtro['visitaTipo'] = $arVisitaTipo->getCodigoVisitaTipoPk();
        } else {
            $filtro['visitaTipo'] = $arVisitaTipo;
        }
        if (is_object($arContacto)) {
            $filtro['contacto'] = $arContacto->getCodigoContactoPk();
        } else {
            $filtro['contacto'] = $arContacto;
        }

        return $filtro;

    }
}
