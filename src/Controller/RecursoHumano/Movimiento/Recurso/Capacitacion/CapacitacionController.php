<?php


namespace App\Controller\RecursoHumano\Movimiento\Recurso\Capacitacion;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuCapacitacion;
use App\Entity\RecursoHumano\RhuCapacitacionDetalle;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\CapacitacionType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CapacitacionController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "RhuCapacitacion";

    protected $clase = RhuCapacitacion::class;
    protected $claseNombre = "RhuCapacitacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Recurso";
    protected $nombre = "Capacitacion";

    /**
     * @Route("recursohumano/movimiento/recurso/capacitacion/lista", name="recursohumano_movimiento_recurso_capacitacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('codigoCapacitacionPk', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
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
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuCapacitacion::class)->lista($raw)->getQuery()->execute(), "");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuCapacitacion::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_seleccion_lista'));
            }
        }
        $arCapacitaciones = $paginator->paginate($em->getRepository(RhuCapacitacion::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/movimiento/recurso/capacitacion/lista.html.twig', [
            'arCapacitaciones' => $arCapacitaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/capacitacion/nuevo/{id}", name="recursohumano_movimiento_recurso_capacitacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = new RhuCapacitacion();
        if ($id != 0) {
            $arCapacitacion = $em->getRepository(RhuCapacitacion::class)->find($id);
        } else {
            $arCapacitacion->setFechaCapacitacion(new \DateTime('now'));
        }
        $form = $this->createForm(CapacitacionType::class, $arCapacitacion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arCapacitacion = $form->getData();
                if ($arCapacitacion->getObjetivo() == '') {
                    $arCapacitacion->getCapacitacionTemaRel()->getObjetivo();
                }
                if ($arCapacitacion->getContenido() == '') {
                    $arCapacitacion->getCapacitacionTemaRel()->getContenido();
                }
                $em->persist($arCapacitacion);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_capacitacion_detalle', array('id' => $arCapacitacion->getCodigoCapacitacionPk())));
            }
        }
        return $this->render('recursohumano/movimiento/recurso/capacitacion/nuevo.html.twig', [
            'arCapacitacion' => $arCapacitacion,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("recursohumano/movimiento/recurso/capacitacion/detalle/{id}", name="recursohumano_movimiento_recurso_capacitacion_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = $em->getRepository(RhuCapacitacion::class)->find($id);
        $arrActualizarDetalle = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Actualizar', 'disabled' => false];
        $arrAsiste = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Asiste', 'disabled' => false];
        $arrNoAsiste = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'No asiste', 'disabled' => false];
        $form = Estandares::botonera($arCapacitacion->getEstadoAutorizado(), $arCapacitacion->getEstadoAprobado(), $arCapacitacion->getEstadoAnulado());
        $form->add('btnActualizarDetalle', SubmitType::class, $arrActualizarDetalle);
        $form->add('btnAsiste', SubmitType::class, $arrAsiste);
        $form->add('btnNoAsiste', SubmitType::class, $arrNoAsiste);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnActualizarDetalle')->isClicked()) {
                $em->getRepository(RhuCapacitacionDetalle::class)->actualizarDetalles($arrControles, $form, $arCapacitacion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_capacitacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnAsiste')->isClicked()) {
                $arSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuCapacitacionDetalle::class)->asiste($arSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_capacitacion_detalle', ['id' => $id]));
            }
            if ($form->get('btnNoAsiste')->isClicked()) {
                $arSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuCapacitacionDetalle::class)->noAsiste($arSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_capacitacion_detalle', ['id' => $id]));
            }
        }
        $arCapacitacionDetalles = $paginator->paginate($em->getRepository(RhuCapacitacionDetalle::class)->lista($id), $request->query->getInt('page', 1), 500);
        return $this->render('recursohumano/movimiento/recurso/capacitacion/detalle.html.twig', [
            'arCapacitacion' => $arCapacitacion,
            'arCapacionDetalles' => $arCapacitacionDetalles,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("recursohumano/movimiento/recurso/capacitacion/detalle/nuevoempleado/{id}", name="recursohumano_movimiento_recurso_capacitacion_detalle_nuevoempleado")
     */
    public function detalleNuevoEmpleado(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arCapacitacion = $em->getRepository(RhuCapacitacion::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigo);
                        $arCapacitacionDetalle = new RhuCapacitacionDetalle();
                        $arCapacitacionDetalle->setCapacitacionRel($arCapacitacion);
                        $arCapacitacionDetalle->setEmpleadoRel($arEmpleado);
                        $em->persist($arCapacitacionDetalle);
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arEmpleados = $paginator->paginate($em->getRepository(RhuEmpleado::class)->listaBuscarCapacitacion(), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/movimiento/recurso/capacitacion/nuevoEmpleado.html.twig', array(
            'arCapacitacion' => $arCapacitacion,
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
        ));
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCapacitacionPk' => $form->get('codigoCapacitacionPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }
}