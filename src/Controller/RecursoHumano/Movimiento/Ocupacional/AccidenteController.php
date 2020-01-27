<?php


namespace App\Controller\RecursoHumano\Movimiento\Ocupacional;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuAccidente;
use App\Form\Type\RecursoHumano\AccidenteType;
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

class AccidenteController extends MaestroController
{
    public $tipo = "movimiento";
    public $modelo = "RhuAccidente";

    protected $class = RhuAccidente::class;
    protected $claseNombre = "RhuAccidente";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Ocupacional";
    protected $nombre = "Accidente";

    /**
     * @Route("recursohumano/movimiento/ocupacional/accidente/lista", name="recursohumano_movimiento_ocupacional_accidente_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('codigoAccidentePk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuAccidente::class)->lista($raw)->getQuery()->execute(), "Accidentes");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuAccidente::class, $arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_ocupacional_accidente_lista'));
            }
        }
        $arAccidentes = $paginator->paginate($em->getRepository(RhuAccidente::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/movimiento/ocupacional/accidente/lista.html.twig', [
            'arAccidentes' => $arAccidentes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/ocupacional/accidente/nuevo/{id}", name="recursohumano_movimiento_ocupacional_accidente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAccidente = new RhuAccidente();
        if ($id != 0) {
            $arAccidente = $em->getRepository(RhuAccidente::class)->find($id);
        }
        $form = $this->createForm(AccidenteType::class, $arAccidente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAccidente = $form->getData();
                $em->persist($arAccidente);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_ocupacional_accidente_detalle', array('id' => $arAccidente->getCodigoAccidentePk())));
            }
        }
        return $this->render('recursohumano/movimiento/ocupacional/accidente/nuevo.html.twig', [
            'arAccidente' => $arAccidente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/ocupacional/accidente/detalle/{id}", name="recursohumano_movimiento_ocupacional_accidente_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository(RhuAccidente::class)->find($id);
        $form = Estandares::botonera($arRegistro->getEstadoAutorizado(), $arRegistro->getEstadoAprobado(), $arRegistro->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }
        return $this->render('recursohumano/movimiento/ocupacional/accidente/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoAccidente' => $form->get('codigoAccidentePk')->getData(),
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