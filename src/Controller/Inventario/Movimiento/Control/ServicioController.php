<?php

namespace App\Controller\Inventario\Movimiento\Control;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvServicio;
use App\Entity\Inventario\InvServicioTipo;
use App\Form\Type\Inventario\ServicioType;
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
use Symfony\Component\Routing\Annotation\Route;

class ServicioController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "InvServicio";

    protected $clase= InvServicio::class;
    protected $claseFormulario = ServicioType::class;
    protected $claseNombre = "InvServicio";
    protected $modulo   = "Inventario";
    protected $funcion  = "Movimiento";
    protected $grupo    = "Control";
    protected $nombre   = "Servicio";
    /**
     * @Route("/inventario/movimiento/control/servicio/lista", name="inventario_movimiento_control_servicio_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoServicioPk', TextType::class, array('required' => false))
            ->add('codigoServicioTipoFk', EntityType::class, [
                'class' => InvServicioTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.codigoServicioTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
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
                General::get()->setExportar($em->getRepository(InvServicio::class)->lista($raw)->getQuery()->execute(), "Servicio");

            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Inventario\InvServicio')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_control_servicio_lista'));
            }
        }
        $arServicios = $paginator->paginate($em->getRepository(InvServicio::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/movimiento/control/servicio/lista.html.twig', [
            'arServicios' => $arServicios,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inventario/movimiento/servicio/control/nuevo/{id}", name="inventario_movimiento_control_servicio_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $arServicio = new InvServicio();
            $arServicio->setFecha(new \DateTime('now'));
        } else {
            $arServicio = $em->getRepository(InvServicio::class)->find($id);
        }
        $form = $this->createForm(ServicioType::class, $arServicio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arServicio->setComentario($form->get('comentario')->getData());
                $em->persist($arServicio);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_movimiento_control_servicio_lista'));
            }
        }
        return $this->render('inventario/movimiento/control/servicio/nuevo.html.twig', [
            'arServicio' => $arServicio,
            'form' => $form->createView()]);
    }


    /**
     * @param Request $request
     * @param $id
     * @Route("/inventario/movimiento/control/servicio/detalle/{id}", name="inventario_movimiento_control_servicio_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arServicio = $em->getRepository(InvServicio::class)->find($id);
        $form = Estandares::botonera($arServicio->getEstadoAutorizado(),$arServicio->getEstadoAprobado(),$arServicio->getEstadoAnulado());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new InvServicio();
//                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->autorizar($arServicio);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->desautorizar($arServicio);
            }
            if ($form->get('btnAprobar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->aprobar($arServicio);
            }
            if ($form->get('btnAnular')->isClicked()) {
//                $em->getRepository(InvServicio::class)->anular($arServicio);
            }
            return $this->redirect($this->generateUrl('inventario_movimiento_control_servicio_detalle',['id' => $arServicio->getCodigoServicioPk()]));
        }

        return $this->render('inventario/movimiento/control/servicio/detalle.html.twig', [
            'arServicio' => $arServicio,
            'form' => $form->createView()]);

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoServicio' => $form->get('codigoServicioPk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arServicioTipo = $form->get('codigoServicioTipoFk')->getData();

        if (is_object($arServicioTipo)) {
            $filtro['servicioTipo'] = $arServicioTipo->getCodigoServicioTipoPk();
        } else {
            $filtro['servicioTipo'] = $arServicioTipo;
        }

        return $filtro;

    }
}
