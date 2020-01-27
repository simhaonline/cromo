<?php


namespace App\Controller\RecursoHumano\Movimiento\Recurso\Disciplinario;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuDesempeno;
use App\Entity\RecursoHumano\RhuDisciplinario;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\DesempenoType;
use App\Form\Type\RecursoHumano\DisciplinarioType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DisciplinarioController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "RhuDisciplinario";

    protected $clase = RhuDisciplinario::class;
    protected $claseNombre = "RhuDisciplinario";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Recurso";
    protected $nombre = "Disciplinario";


    /**
     * @Route("recursohumano/movimiento/recurso/disciplinario/lista", name="recursohumano_movimiento_recurso_disciplinario_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoDisciplinarioPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuDisciplinario::class)->lista($raw)->getQuery()->execute(), "desempeño");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuDisciplinario::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_disciplinario_lista'));
            }

        }
        $arDisciplinarios = $paginator->paginate($em->getRepository(RhuDisciplinario::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/recurso/disciplinario/lista.html.twig', [
            'arDisciplinarios' => $arDisciplinarios,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("recursohumano/movimiento/recurso/disciplinario/nuevo/{id}", name="recursohumano_movimiento_recurso_disciplinario_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDisciplinario = new RhuDisciplinario();
        if ($id != 0) {
            $arDisciplinario = $em->getRepository(RhuDisciplinario::class)->find($id);
        } else {
            $arDisciplinario->setFechaIncidente(new \DateTime('now'));
            $arDisciplinario->setFechaDesdeSancion(new \DateTime('now'));
            $arDisciplinario->setFechaHastaSancion(new \DateTime('now'));
        }
        $form = $this->createForm(DisciplinarioType::class, $arDisciplinario);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arDisciplinario->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    $arDisciplinario->setEmpleadoRel($arEmpleado);
                    $arDisciplinario = $form->getData();
                    $em->persist($arDisciplinario);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_recurso_disciplinario_detalle', array('id' => $arDisciplinario->getCodigoDisciplinarioPk())));
                }
            }
        }
        return $this->render('recursohumano/movimiento/recurso/disciplinario/nuevo.html.twig', [
            'arDisciplinario' => $arDisciplinario,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/recurso/disciplinario/detalle/{id}", name="recursohumano_movimiento_recurso_disciplinario_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository(RhuDisciplinario::class)->find($id);
        $form = Estandares::botonera($arRegistro->getEstadoAutorizado(), $arRegistro->getEstadoAprobado(), $arRegistro->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        return $this->render('recursohumano/movimiento/recurso/disciplinario/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }


    public function getFiltros($form)
    {
        $filtro = [
            'codigoDisciplinario' => $form->get('codigoDisciplinarioPk')->getData(),
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