<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use App\Form\Type\RecursoHumano\EmbargoJuzgadoType;
use App\General\General;
use FOS\RestBundle\Controller\Annotations as Rest;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmbargoJuzgadoController extends MaestroController
{


    public $tipo = "administracion";
    public $modelo = "RhuEmbargoJuzgado";

    protected $clase = RhuEmbargoJuzgado::class;
    protected $claseNombre = "RhuEmbargoJuzgado";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "EmbargoJuzgado";



    /**
     * @Rest\Route("recursohumano/adminsitracion/nomina/embargo/juzgado/lista", name="recursohumano_administracion_nomina_embargojuzgado_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmbargoJuzgado', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
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
            //estandar
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuEmbargoJuzgado::class)->lista($raw), "juzgados");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuEmbargoJuzgado::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl(''));
            }
        }
        $arJuzgados = $paginator->paginate($em->getRepository(RhuEmbargoJuzgado::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/juzgado/lista.html.twig', [
            'arJuzgados' => $arJuzgados,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/embargo/juzgado/nuevo/{id}", name="recursohumano_administracion_nomina_embargojuzgado_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargoJuzgado = new RhuEmbargoJuzgado();
        if ($id != 0) {
            $arEmbargoJuzgado = $em->getRepository(RhuEmbargoJuzgado::class)->find($id);
        }
        $form = $this->createForm(EmbargoJuzgadoType::class, $arEmbargoJuzgado);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmbargoJuzgado = $form->getData();
                $em->persist($arEmbargoJuzgado);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_embargojuzgado_detalle', array('id' => $arEmbargoJuzgado->getCodigoEmbargoJuzgadoPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/juzgado/nuevo.html.twig', [
            'arEmbargoJusgado' => $arEmbargoJuzgado,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("recursohumano/adminsitracion/nomina/embargo/juzgado/detalle/{id}", name="recursohumano_administracion_nomina_embargojuzgado_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargoJuzgado = $em->getRepository(RhuEmbargoJuzgado::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/juzgado/detalle.html.twig', [
            'arEmbargoJuzgado' => $arEmbargoJuzgado,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoEmbargoJuzgado' => $form->get('codigoEmbargoJuzgado')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }
}