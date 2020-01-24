<?php


namespace App\Controller\RecursoHumano\Administracion\Contratacion;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuRequisitoTipo;
use App\Form\Type\RecursoHumano\RequisitoTipoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RequisitoTipoController extends MaestroController
{


    public $tipo = "administracion";
    public $modelo = "RhuRequisitoTipo";


    protected $clase = RhuRequisitoTipo::class;
    protected $claseNombre = "RhuRequisitoTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Contratacion";
    protected $nombre = "RequisitoTipo";



    /**
     * @Route("/recursohumano/administracion/contratacion/requisitostipo/lista", name="recursohumano_administracion_contratacion_requisitostipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoRequisitoTipoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuRequisitoTipo::class)->lista($raw), "Requisito Tipo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuRequisitoTipo::class)->eliminar($arrSeleccionados);
            }
        }
        $arRequisitoTipos = $paginator->paginate($em->getRepository(RhuRequisitoTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/contratacion/requisitotipo/lista.html.twig', [
            'arRequisitoTipos' => $arRequisitoTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/contratacion/requisitostipo/nuevo/{id}", name="recursohumano_administracion_contratacion_requisitosTipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitoTipo = new RhuRequisitoTipo();
        if ($id != 0) {
            $arRequisitoTipo = $em->getRepository(RhuRequisitoTipo::class)->find($id);
            if (!$arRequisitoTipo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitostipo_lista'));
            }
        }
        $form = $this->createForm( RequisitoTipoType::class, $arRequisitoTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arRequisitoTipo = $form->getData();
                $em->persist($arRequisitoTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitosTipo_detalle', array('id' => $arRequisitoTipo->getCodigoRequisitoTipoPk())));
            }
        }
        return $this->render('recursohumano/administracion/contratacion/requisitotipo/nuevo.html.twig', [
            'arRequisitoTipo' => $arRequisitoTipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/contratacion/requisitostipo/detalle/{id}", name="recursohumano_administracion_contratacion_requisitosTipo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitoTipo = $em->getRepository(RhuRequisitoTipo::class)->find($id);
        return $this->render('recursohumano/administracion/contratacion/requisitotipo/detalle.html.twig', [
            'arRequisitoTipo' => $arRequisitoTipo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoRequisitoTipo' => $form->get('codigoRequisitoTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }

}