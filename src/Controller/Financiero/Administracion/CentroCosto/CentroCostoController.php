<?php


namespace App\Controller\Financiero\Administracion\CentroCosto;


use App\Controller\MaestroController;
use App\Entity\Financiero\FinCentroCosto;
use App\Form\Type\Financiero\CentroCostoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CentroCostoController extends MaestroController
{



    public $tipo = "administracion";
    public $modelo = "FinCentroCosto";


    protected $clase= FinCentroCosto::class;
    protected $claseNombre = "FinCentroCosto";
    protected $modulo   = "Financiero";
    protected $funcion  = "Administracion";
    protected $grupo    = "CentroCosto";
    protected $nombre   = "Centro";




    /**
     * @Route("/financiero/administracion/centrocosto/lista", name="financiero_administracion_centrocosto_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoCentroCostoPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('estadoInactivo', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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
                General::get()->setExportar($em->getRepository(FinCentroCosto::class)->lista($raw), "Centros de costo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(FinCentroCosto::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('financiero_administracion_centrocosto_lista'));
            }
        }
        $arCentroCostos = $paginator->paginate($em->getRepository(FinCentroCosto::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('financiero/administracion/centrocosto/lista.html.twig', [
            'arCentroCostos' => $arCentroCostos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/financiero/administracion/centrocosto/nuevo/{id}", name="financiero_administracion_centrocosto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = new FinCentroCosto();
        if ($id != 0 || $id != '') {
            $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($id);
        }
        $form = $this->createForm(CentroCostoType::class, $arCentroCosto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCentroCosto = $form->getData();
                $em->persist($arCentroCosto);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_administracion_centrocosto_detalle', array('id' => $arCentroCosto->getCodigoCentroCostoPk())));
            }
        }
        return $this->render('financiero/administracion/centrocosto/nuevo.html.twig', [
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()
        ]); 
    }

    /**
     * @Route("/financiero/administracion/centrocosto/detalle/{id}", name="financiero_administracion_centrocosto_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($id);
        return $this->render('financiero/administracion/centrocosto/detalle.html.twig', [
            'arCentroCosto' => $arCentroCosto,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCentroCosto' => $form->get('codigoCentroCostoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'estadoInactivo' => $form->get('estadoInactivo')->getData(),
        ];

        return $filtro;

    }
}