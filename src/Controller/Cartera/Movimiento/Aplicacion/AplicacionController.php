<?php

namespace App\Controller\Cartera\Movimiento\Aplicacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Cartera\CarAnticipo;
use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Compra\CuentaPagarType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AplicacionController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "CarAplicacion";


    protected $clase = CarAplicacion::class;
    protected $claseNombre = "CarAplicacion";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Operacion";
    protected $nombre = "Aplicacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/operacion/aplicacion/lista", name="cartera_movimiento_operacion_aplicacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoAplicacionPk', TextType::class, array('required' => false))
            ->add('numeroDocumento', NumberType::class, array('required' => false))
            ->add('numeroDocumentoAplicacion', NumberType::class, array('required' => false))
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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
                General::get()->setExportar($em->getRepository(CarAplicacion::class)->lista($raw)->getQuery()->execute(), "Aplicaciones");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                Mensajes::info("Las aplicaciones no se pueden eliminar solo anular");
                return $this->redirect($this->generateUrl('cartera_movimiento_operacion_aplicacion_lista'));
            }
        }
        $arAplicaciones = $paginator->paginate($em->getRepository(CarAplicacion::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('cartera/movimiento/operacion/aplicacion/lista.html.twig', [
            'arAplicaciones' => $arAplicaciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/operacion/aplicacion/nuevo/{id}", name="cartera_movimiento_operacion_aplicacion_nuevo")
     */
    public function nuevo()
    {
        return $this->redirect($this->generateUrl('cartera_movimiento_operacion_aplicacion_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/operacion/aplicacion/detalle/{id}", name="cartera_movimiento_operacion_aplicacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAplicacion = $em->getRepository(CarAplicacion::class)->find($id);
        $form = Estandares::botonera($arAplicacion->getEstadoAutorizado(),$arAplicacion->getEstadoAprobado(),$arAplicacion->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(CarAplicacion::class)->anular($arAplicacion);
                return $this->redirect($this->generateUrl('cartera_movimiento_operacion_aplicacion_detalle', ['id' => $id]));
            }
        }
        return $this->render('cartera/movimiento/operacion/aplicacion/detalle.html.twig', [
            'arAplicacion' => $arAplicacion,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoAplicacion' => $form->get('codigoAplicacionPk')->getData(),
            'numeroDocumento' => $form->get('numeroDocumento')->getData(),
            'numeroDocumentoAplicacion' => $form->get('numeroDocumentoAplicacion')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }

}