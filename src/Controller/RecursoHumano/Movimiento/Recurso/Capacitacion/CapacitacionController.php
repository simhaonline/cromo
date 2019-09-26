<?php


namespace App\Controller\RecursoHumano\Movimiento\Recurso\Capacitacion;


use App\Entity\RecursoHumano\RhuCapacitacion;
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


class CapacitacionController extends AbstractController
{
    /**
     * @Route("recursohumano/movimiento/recurso/capacitacion/lista", name="recursohumano_movimiento_recurso_capacitacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('codigoCapacitacionPk', TextType::class, array('required' => false))
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
        }
        $form = $this->createForm(CapacitacionType::class, $arCapacitacion);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arCapacitacion = $form->getData();
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
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository(RhuCapacitacion::class)->find($id);
        $form = Estandares::botonera($arRegistro->getEstadoAutorizado(), $arRegistro->getEstadoAprobado(), $arRegistro->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        return $this->render('recursohumano/movimiento/recurso/capacitacion/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
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