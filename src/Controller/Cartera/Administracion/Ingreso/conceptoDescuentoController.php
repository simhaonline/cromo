<?php


namespace App\Controller\Cartera\Administracion\Ingreso;


use App\Controller\MaestroController;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Form\Type\Cartera\DescuentoConceptoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class conceptoDescuentoController extends  MaestroController
{

    public $tipo = "Administracion";
    public $modelo = "CarDescuentoConcepto";

    protected $clase = CarDescuentoConcepto::class;
    protected $claseNombre = "CarDescuentoConcepto";
    protected $modulo = "Cartera";
    protected $funcion = "Administracion";
    protected $grupo = "Ingreso";
    protected $nombre = "ConceptoDescuento";


    /**
     * @Route("/cartera/administracion/ingreso/concepto/descuento/lista", name="cartera_administracion_ingreso_concepto_descuento_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoDescuentoConceptoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(CarDescuentoConcepto::class)->lista($raw), "Concepto descuento");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(CarDescuentoConcepto::class)->eliminar($arrSeleccionados);
            }
        }
        $arDescuentoConceptos= $paginator->paginate($em->getRepository(CarDescuentoConcepto::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('cartera/administracion/ingreso/descuentoConcepto/lista.html.twig', [
            'arDescuentoConceptos' => $arDescuentoConceptos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cartera/administracion/ingreso/concepto/descuento/nuevo/{id}", name="cartera_administracion_ingreso_concepto_descuento_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arDescuentoConcepto = new CarDescuentoConcepto();
        if ($id != 0) {
            $arDescuentoConcepto = $em->getRepository(CarDescuentoConcepto::class)->find($id);
        }
        $form = $this->createForm(DescuentoConceptoType::class, $arDescuentoConcepto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arDescuentoConcepto = $form->getData();
                $em->persist($arDescuentoConcepto);
                $em->flush();
                return $this->redirect($this->generateUrl('cartera_administracion_ingreso_concepto_descuento_detalle', array('id' => $arDescuentoConcepto->getCodigoDescuentoConceptoPk())));
            }
        }
        return $this->render('cartera/administracion/ingreso/descuentoConcepto/nuevo.html.twig', [
            'arDescuentoConcepto' => $arDescuentoConcepto,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cartera/administracion/ingreso/concepto/descuento/detalle/{id}", name="cartera_administracion_ingreso_concepto_descuento_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConceptoDescuento = $em->getRepository(CarDescuentoConcepto::class)->find($id);

        return $this->render('cartera/administracion/ingreso/descuentoConcepto/detalle.html.twig', [
            'arConceptoDescuento' => $arConceptoDescuento,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoDescuentoConcepto' => $form->get('codigoDescuentoConceptoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }

}