<?php

namespace App\Controller\RecursoHumano\Administracion\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RequisitoCargo;
use App\Form\Type\RecursoHumano\ExamenType;
use App\Form\Type\RecursoHumano\RequisitoCargoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RequisitoCargoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "RhuRequisitoCargo";


    protected $clase = RhuRequisitoCargo::class;
    protected $claseNombre = "RhuRequisitoCargo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Contratacion";
    protected $nombre = "RequisitoCargo";



    /**
     * @Route("/recursohumano/administracion/contratacion/requisitocargo/lista", name="recursohumano_administracion_contratacion_requisitocargo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoRequisitoCargoPk', TextType::class, array('required' => false))
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
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuRequisitoCargo::class)->lista($raw), "Requisito cargo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuRequisitoCargo::class)->eliminar($arrSeleccionados);
            }
        }
        $arRequisitosCargos = $paginator->paginate($em->getRepository(RhuRequisitoCargo::class)->lista($raw), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/administracion/contratacion/requisitocargo/lista.html.twig', [
            'arRequisitosCargos' => $arRequisitosCargos,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/recursohumano/administracion/contratacion/requisitocargo/nuevo/{id}", name="recursohumano_administracion_contratacion_requisitocargo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitoCargo = new RhuRequisitoCargo();
        if ($id != 0) {
            $arRequisitoCargo = $em->getRepository(RhuRequisitoCargo::class)->find($id);
            if (!$arRequisitoCargo) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitocargo_lista'));
            }
        }
        $form = $this->createForm( RequisitoCargoType::class, $arRequisitoCargo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arRequisitoCargo = $form->getData();
                $em->persist($arRequisitoCargo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_requisitocargo_detalle', array('id' => $arRequisitoCargo->getCodigoRequisitoCargoPk())));
            }
        }
        return $this->render('recursohumano/administracion/contratacion/requisitocargo/nuevo.html.twig', array(
            'form' => $form->createView(),
            'arRequisitosConcepto' => $arRequisitoCargo,
        ));

    }

    /**
     * @Route("/recursohumano/administracion/contratacion/requisitocargo/detalle/{id}", name="recursohumano_administracion_contratacion_requisitocargo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisitoCargo = $em->getRepository(RhuRequisitoCargo::class)->find($id);
        return $this->render('recursohumano/administracion/contratacion/requisitocargo/detalle.html.twig', [
            'arRequisitoCargo' => $arRequisitoCargo,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoRequisitoCargo' => $form->get('codigoRequisitoCargoPk')->getData(),
        ];

        return $filtro;

    }
}

