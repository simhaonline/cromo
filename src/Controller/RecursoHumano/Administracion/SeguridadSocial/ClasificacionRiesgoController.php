<?php


namespace App\Controller\RecursoHumano\Administracion\SeguridadSocial;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use App\Form\Type\RecursoHumano\ClasificacionRiesgoType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClasificacionRiesgoController  extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "RhuClasificacionRiesgo";

    protected $clase = RhuClasificacionRiesgo::class;
    protected $claseNombre = "RhuClasificacionRiesgo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "SeguridadSocial";
    protected $nombre = "ClasificacionRiesgoController";



    /**
     * @Route("recursohumano/administracion/seguridadsocial/clasificacion/lista", name="recursohumano_administracion_seguridadsocial_clasificacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClasificacionRiesgoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuClasificacionRiesgo::class)->lista($raw), "Clasificacion Riesgo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuClasificacionRiesgo::class)->eliminar($arrSeleccionados);
            }
        }
        $arClasificacionRiegos = $paginator->paginate($em->getRepository(RhuClasificacionRiesgo ::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/seguridadsocial/clasificacion/lista.html.twig',[
            'arClasificacionRiegos' => $arClasificacionRiegos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seguridadsocial/clasificacion/nuevo/{id}", name="recursohumano_administracion_seguridadsocial_clasificacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arClasificacionRiesgo = new RhuClasificacionRiesgo();
        if ($id != 0) {
            $arClasificacionRiesgo = $em->getRepository(RhuClasificacionRiesgo::class)->find($id);
            if (!$arClasificacionRiesgo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_embargojuzgado_lista'));
            }
        }
        $form = $this->createForm(ClasificacionRiesgoType::class, $arClasificacionRiesgo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arClasificacionRiesgo = $form->getData();
                $em->persist($arClasificacionRiesgo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_seguridadsocial_clasificacion_detalle   ', array('id' => $arClasificacionRiesgo->getCodigoClasificacionRiesgoPk())));
            }
        }
        return $this->render('recursohumano/administracion/seguridadsocial/clasificacion/nuevo.html.twig',[
            'arClasificacionRiesgo' => $arClasificacionRiesgo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seguridadsocial/clasificacion/detalle/{id}", name="recursohumano_administracion_seguridadsocial_clasificacion_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arClasificacionRiego = $em->getRepository(RhuClasificacionRiesgo::class)->find($id);
        return $this->render('recursohumano/administracion/seguridadsocial/clasificacion/detalle.html.twig',[
            'arClasificacionRiego' => $arClasificacionRiego,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoClasificacionRiesgo' => $form->get('codigoClasificacionRiesgoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }

}