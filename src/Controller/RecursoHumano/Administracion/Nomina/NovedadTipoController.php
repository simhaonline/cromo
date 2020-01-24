<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuNovedadTipo;
use App\Form\Type\RecursoHumano\NovedadTipoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NovedadTipoController extends MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "RhuNovedadTipo";


    protected $clase = RhuNovedadTipo::class;
    protected $claseNombre = "RhuNovedadTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Nomina";
    protected $nombre = "NovedadTipo";




    /**
     * @Route("recursohumano/adminsitracion/nomina/novedadTipo/lista", name="recursohumano_administracion_nomina_novedadTipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoNovedadTipoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuNovedadTipo::class)->lista($raw), "novedad tipos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuNovedadTipo::class)->eliminar($arrSeleccionados);
            }
        }
        $arNovedadTipos = $paginator->paginate($em->getRepository(RhuNovedadTipo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/administracion/nomina/novedadTipo/lista.html.twig', [
            'arNovedadTipos' => $arNovedadTipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomina/novedadTipo/nuevo/{id}", name="recursohumano_administracion_nomina_novedadTipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedadTipo = $em->getRepository(RhuNovedadTipo::class)->find($id);
        if (is_null($arNovedadTipo)){
            $arNovedadTipo = new RhuNovedadTipo();
        }
        $form = $this->createForm(NovedadTipoType::class, $arNovedadTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arNovedadTipo = $form->getData();
                $em->persist($arNovedadTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_nomina_novedadTipo_detalle', array('id' => $arNovedadTipo->getCodigoNovedadTipoPk())));
            }
        }

        return $this->render('recursohumano/administracion/nomina/novedadTipo/nuevo.html.twig', [
            'arnovedadTipo' => $arNovedadTipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/nomnina/novedadTipo/detalle/{id}", name="recursohumano_administracion_nomina_novedadTipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedadTipo = $em->getRepository(RhuNovedadTipo::class)->find($id);
        return $this->render('recursohumano/administracion/nomina/novedadTipo/detalle.html.twig', [
            'arNovedadTipo' => $arNovedadTipo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoNovedadTipo' => $form->get('codigoNovedadTipoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }
}