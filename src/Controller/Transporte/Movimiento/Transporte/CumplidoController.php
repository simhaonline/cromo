<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Form\Type\Transporte\CumplidoType;
use App\Formato\Transporte\Cumplido;
use App\Formato\Transporte\CumplidoEntrega;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CumplidoController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "TteCumplido";


    protected $clase= TteCumplido::class;
    protected $claseNombre = "TteCumplido";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Cumplido";

   /**
    * @Route("/transporte/movimiento/transporte/cumplido/lista", name="transporte_movimiento_transporte_cumplido_lista")
    */    
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
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
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltro($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltro($form);
                General::get()->setExportar($em->getRepository(TteCumplido::class)->lista($raw)->getQuery()->execute(), "Facturas");
            }
        }
        $arCumplidos = $paginator->paginate($em->getRepository(TteCumplido::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('transporte/movimiento/transporte/cumplido/lista.html.twig', [
            'arCumplidos' => $arCumplidos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/cumplido/detalle/{id}", name="transporte_movimiento_transporte_cumplido_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($id);
        $form = Estandares::botonera($arCumplido->getEstadoAutorizado(),$arCumplido->getEstadoAprobado(),$arCumplido->getEstadoAnulado());
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arCumplido->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                if($arCumplido->getCumplidoTipoRel()->getEntregaMercancia()) {
                    $formato = new CumplidoEntrega();
                    $formato->Generar($em, $id);
                } else {
                    $formato = new Cumplido();
                    $formato->Generar($em, $id);
                }

            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteCumplido::class)->autorizar($arCumplido);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteCumplido::class)->desAutorizar($arCumplido);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteCumplido::class)->Aprobar($arCumplido);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteCumplido::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(TteCumplido::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_detalle', ['id' => $id]));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->cumplido($id), "Cumplidos $id");
            }
        }

        $arGuias = $paginator->paginate($this->getDoctrine()->getRepository(TteGuia::class)->cumplido($id), $request->query->getInt('page', 1), 30 );
        return $this->render('transporte/movimiento/transporte/cumplido/detalle.html.twig', [
            'arCumplido' => $arCumplido,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/cumplido/detalle/adicionar/guia/{codigoCumplido}", name="transporte_movimiento_transporte_cumplido_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoCumplido)
    {
        $em = $this->getDoctrine()->getManager();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setCumplidoRel($arCumplido);
                    $arGuia->setFechaCumplido(new \DateTime('now'));
                    $arGuia->setEstadoCumplido(1);
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteCumplido::class)->liquidar($codigoCumplido);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->cumplidoPendiente($arCumplido->getCodigoClienteFk());
        return $this->render('transporte/movimiento/transporte/cumplido/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/cumplido/nuevo/{id}", name="transporte_movimiento_transporte_cumplido_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arCumplido = new TteCumplido();
        if($id != 0) {
            $arCumplido = $em->getRepository(TteCumplido::class)->find($id);
        }
        $form = $this->createForm(CumplidoType::class, $arCumplido);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arCumplido->setClienteRel($arCliente);
                    $fecha = new \DateTime('now');
                    $arCumplido->setFecha($fecha);
                    $em->persist($arCumplido);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_nuevo', array('codigoRecogida' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_cumplido_detalle', ['id' => $arCumplido->getCodigoCumplidoPk()]));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/transporte/cumplido/nuevo.html.twig', [
            'arCumplido' => $arCumplido,
            'form' => $form->createView()]);
    }

    public function getFiltro($form){

        return $filtro = [
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ?$form->get('fechaHasta')->getData()->format('Y-m-d'): null,
        ];

    }



}

