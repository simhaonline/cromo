<?php


namespace App\Controller\RecursoHumano\Administracion\General;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuCargo;
use App\Form\Type\RecursoHumano\CargoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class CargoController  extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "RhuCargo";

    protected $clase = RhuCargo::class;
    protected $claseNombre = "RhuCargo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Cargo";

    /**
     * @param Request $request
     * @Route("recursohumano/adminsitracion/general/cargo/lista", name="recursohumano_administracion_general_cargo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('codigoCargoPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuCargo::class)->lista($raw), "Cargos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuCargo::class)->eliminar($arrSeleccionados);
            }
        }
        $arCargos = $paginator->paginate($em->getRepository(RhuCargo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render ('recursohumano/administracion/general/cargo/lista.html.twig', [
            'arCargos' => $arCargos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/general/cargo/nuevo/{id}", name="recursohumano_administracion_general_cargo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCargo = new RhuCargo();
        if ($id != 0) {
            $arCargo = $em->getRepository(RhuCargo::class)->find($id);
            if (!$arCargo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_general_cargo_lista'));
            }
        }
        $form = $this->createForm(CargoType::class, $arCargo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCargo = $form->getData();
                $em->persist($arCargo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_general_cargo_detalle', array('id' => $arCargo->getCodigoCargoPk())));
            }
        }
        return $this->render ('recursohumano/administracion/general/cargo/nuevo.html.twig', [
            'arEmbargoJusgado' => $arCargo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/adminsitracion/general/cargo/detalle/{id}", name="recursohumano_administracion_general_cargo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCargo = $em->getRepository(RhuCargo::class)->find($id);
        return $this->render ('recursohumano/administracion/general/cargo/detalle.html.twig', [
            'arCargo' => $arCargo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCargo' => $form->get('codigoCargoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }
}