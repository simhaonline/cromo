<?php


namespace App\Controller\Cartera\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\Cartera\CarCliente;
use App\Form\Type\Cartera\ClienteType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends MaestroController
{


    public $tipo = "administracion";
    public $modelo = "CarCliente";

    protected $clase = CarCliente::class;
    protected $claseNombre = "CarCliente";
    protected $modulo = "Cartera";
    protected $funcion = "Administracion";
    protected $grupo = "Cliente";
    protected $nombre = "Cliente";


    /**
     * @Route("/cartera/administracion/general/cliente/lista", name="cartera_administracion_general_cliente_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClientePk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('identificacion', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(CarCliente::class)->lista($raw), "clientes");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(CarCliente::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_administracion_general_cliente_lista'));
            }
        }
        $arClientes = $paginator->paginate($em->getRepository(CarCliente::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('cartera/administracion/cliente/lista.html.twig', [
            'arClientes' => $arClientes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cartera/administracion/general/cliente/nuevo/{id}", name="cartera_administracion_general_cliente_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new CarCliente();
        if ($id != 0) {
            $arCliente = $em->getRepository(CarCliente::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('cartera_administracion_general_cliente_lista'));
            }
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arCliente = $form->getData();
                $em->persist($arCliente);
                $em->flush();
                return $this->redirect($this->generateUrl('cartera_administracion_general_cliente_detalle', array('id' => $arCliente->getCodigoClientePK())));
            }
        }
        return $this->render('cartera/administracion/cliente/nuevo.html.twig', [
            'arCliente' => $arCliente,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cartera/administracion/general/cliente/detalle/{id}", name="cartera_administracion_general_cliente_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCliente = $em->getRepository(CarCliente::class)->find($id);
        return $this->render('cartera/administracion/cliente/detalle.html.twig', [
            'arCliente' => $arCliente,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoClientePk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'identificacion' => $form->get('identificacion')->getData(),
        ];
        return $filtro;
    }
}