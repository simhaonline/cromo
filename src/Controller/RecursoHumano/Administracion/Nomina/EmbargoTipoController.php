<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuEmbargoTipo;
use App\Form\Type\RecursoHumano\EmbargoTipoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmbargoTipoController extends MaestroController
{


    public $tipo = "Administracion";
    public $modelo = "RhuEmbargoTipo";

    protected $clase = RhuEmb::class;
    protected $claseNombre = "RhuEmbargoTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "EmbargoTipo";


    /**
     * @Route("recursohumano/adminsitracion/nomina/embargotipo/lista", name="recursohumano_administracion_nomina_embargotipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEmbargoTipoPk', TextType::class, array('required' => false))
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
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuEmbargoTipo::class)->lista($raw), "Embargo tipo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuEmbargoTipo::class)->eliminar($arrSeleccionados);
            }
        }
        $arEmbargoTipos = $paginator->paginate($em->getRepository(RhuEmbargoTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/nomina/embargoTipo/lista.html.twig', [
            'arEmbargoTipos' => $arEmbargoTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/embargotipo/nuevo/{id}", name="recursohumano_administracion_nomina_embargotipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargoTipo = new RhuEmbargoTipo();
        if ($id != 0 ) {
            $arEmbargoTipo = $em->getRepository(RhuEmbargoTipo::class)->find($id);
            if (!$arEmbargoTipo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_embargojuzgado_lista'));
            }
        }
        $form = $this->createForm(EmbargoTipoType::class, $arEmbargoTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmbargoTipo = $form->getData();
                $em->persist($arEmbargoTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_embargojuzgado_detalle', array('id' => $arEmbargoTipo->setCodigoEmbargoTipoPk())));
            }
        }
        return $this->render('recursohumano/administracion/nomina/embargoTipo/nuevo.html.twig', [
            'arEmbargoTipo' => $arEmbargoTipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/embargotipo/detalle/{id}", name="recursohumano_administracion_nomina_embargotipo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargoTipo = $em->getRepository(RhuEmbargoTipo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/embargoTipo/detalle.html.twig', [
            'arEmbargoTipo' => $arEmbargoTipo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoEmbargoTipo' => $form->get('codigoEmbargoTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;
    }
}