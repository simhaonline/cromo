<?php


namespace App\Controller\RecursoHumano\Administracion\Dotacion;


use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuDotacionElemento;
use App\Form\Type\RecursoHumano\DotacionElementoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DotacionElementoController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "RhuDotacionElemento";


    protected $clase = RhuDotacionElemento::class;
    protected $claseNombre = "RhuDotacionElemento";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Dotacion";
    protected $nombre = "DotacionElemento";



    /**
     * @Route("recursohumano/administracion/dotacion/dotacionelemento/lista", name="recursohumano_administracion_dotacion_dotacionelemento_lista")
     */
    public function lista (Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoDotacionElementoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuDotacionElemento::class)->lista($raw), "Dotacion");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuDotacionElemento::class)->eliminar($arrSeleccionados);
            }
        }
        $arDotacionElementos = $paginator->paginate($em->getRepository(RhuDotacionElemento::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/dotacion/dotacionelemento/lista.html.twig', [
            'arDotacionElementos' => $arDotacionElementos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/administracion/dotacion/dotacionelemento/nuevo/{id}", name="recursohumano_administracion_dotacion_dotacionelemento_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacionElemento =  new RhuDotacionElemento();
        if ($id != 0) {
            $arDotacionElemento = $em->getRepository(RhuDotacionElemento::class)->find($id);
        }
        $form = $this->createForm(DotacionElementoType::class, $arDotacionElemento);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arDotacionElemento);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_dotacion_dotacionelemento_detalle', ['id' => $arDotacionElemento->getCodigoDotacionElementoPk()]));
            }else{
                Mensajes::error('Debe se puede registrar el elemento');
            }

        }
        return $this->render('recursohumano/administracion/dotacion/dotacionelemento/nuevo.html.twig', [
            'form' => $form->createView(),
            'arDotacionElemento' => $arDotacionElemento
        ]);
    }

    /**
     * @Route("recursohumano/administracion/dotacion/dotacionelemento/detalle/{id}", name="recursohumano_administracion_dotacion_dotacionelemento_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacionElemento = $em->getRepository(RhuDotacionElemento::class)->find($id);
        return $this->render('recursohumano/administracion/dotacion/dotacionelemento/detalle.html.twig',[
            'arDotacionElemento'=>$arDotacionElemento
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoDotacionElemento' => $form->get('codigoDotacionElementoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }
}