<?php


namespace App\Controller\RecursoHumano\Administracion\Costo;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuCostoClase;
use App\Form\Type\RecursoHumano\CostoClaseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;


class CostoClaseController extends  MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "RhuCostoClase";

    protected $clase = RhuCostoClase::class;
    protected $claseNombre = "RhuCostoClase";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Costo";
    protected $nombre = "CostoClase";


    /**
     * @Route("recursohumano/adminsitracion/costo/clase/lista", name="recursohumano_administracion_costo_clase_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoCostoClasePk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuCostoClase::class)->lista($raw), "costos clase");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuCostoClase::class)->eliminar($arrSeleccionados);
            }
        }
        $arCostrosClases = $paginator->paginate($em->getRepository(RhuCostoClase::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/costo/clase/lista.html.twig', [
            'arCostrosClases' => $arCostrosClases,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/costo/clase/nuevo/{id}", name="recursohumano_administracion_costo_clase_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCostroClase = new RhuCostoClase();
        if ($id != 0 || $id != "") {
            $arCostroClase = $em->getRepository(RhuCostoClase::class)->find($id);
        }
        $form = $this->createForm(CostoClaseType::class, $arCostroClase);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCostroClase = $form->getData();
                $em->persist($arCostroClase);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_costo_clase_detalle', array('id' => $arCostroClase->getCodigoCostoClasePk())));
            }
        }
        return $this->render('recursohumano/administracion/costo/clase/nuevo.html.twig', [
            'arCostroClase' => $arCostroClase,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/costo/clase/detalle/{id}", name="recursohumano_administracion_costo_clase_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCostroClase = $em->getRepository(RhuCostoClase::class)->find($id);
        return $this->render('recursohumano/administracion/costo/clase/detalle.html.twig', [
            'arCostroClase' => $arCostroClase,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCostoClase' => $form->get('codigoCostoClasePk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        return $filtro;
    }
}