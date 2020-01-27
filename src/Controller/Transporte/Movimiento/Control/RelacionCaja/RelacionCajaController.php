<?php

namespace App\Controller\Transporte\Movimiento\Control\RelacionCaja;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRelacionCaja;
use App\Form\Type\Transporte\RelacionCajaType;
use App\Formato\Transporte\RelacionCaja;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RelacionCajaController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "TteRelacionCaja";

    protected $clase= TteRelacionCaja::class;
    protected $claseNombre = "TteRelacionCaja";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Control";
    protected $nombre = "RelacionCaja";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/control/relacioncaja/lista", name="transporte_movimiento_control_relacioncaja_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TteRelacionCaja::class)->lista($raw)->getQuery()->execute(), "Relacion caja");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteRelacionCaja::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_lista'));
            }
        }
        $arRelacionCajas = $paginator->paginate($em->getRepository(TteRelacionCaja::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('transporte/movimiento/control/relacioncaja/lista.html.twig', [
            'arRelacionCajas'=>$arRelacionCajas,
            'form' => $form->createView(),
            ]);


    }

    /**
     * @Route("/transporte/movimiento/control/relacioncaja/nuevo/{id}", name="transporte_movimiento_control_relacioncaja_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = new TteRelacionCaja();
        if($id == 0) {
            $arRelacionCaja->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RelacionCajaType::class, $arRelacionCaja);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRelacionCaja = $form->getData();
            $arRelacionCaja->setUsuario($this->getUser()->getUsername());
            $em->persist($arRelacionCaja);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_nuevo', array('id' => 0)));
            } else {
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_lista'));
            }
        }
        return $this->render('transporte/movimiento/control/relacioncaja/nuevo.html.twig', ['arRelacionCaja' => $arRelacionCaja,'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $codigoRelacionCaja
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/control/relacioncaja/detalle/{id}", name="transporte_movimiento_control_relacioncaja_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = $em->getRepository(TteRelacionCaja::class)->find($id);
        $form = Estandares::botonera($arRelacionCaja->getEstadoAutorizado(), $arRelacionCaja->getEstadoAprobado(), $arRelacionCaja->getEstadoAnulado());

        //Controles para el formulario
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arRelacionCaja->getEstadoAutorizado()) {
            $arrBtnRetirar['disabled'] = true;
        }
        $form
            ->add('btnRetirarRecibo', SubmitType::class, $arrBtnRetirar)
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new RelacionCaja();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteRelacionCaja::class)->autorizar($arRelacionCaja);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteRelacionCaja::class)->desautorizar($arRelacionCaja);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteRelacionCaja::class)->aprobar($arRelacionCaja);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarRecibo')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteRelacionCaja::class)->retirarRecibo($arr);
                if($respuesta) {
                    $em->flush();
                    $this->getDoctrine()->getRepository(TteRelacionCaja::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', array('id' => $id)));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteRecibo::class)->relacionCaja($id), "Relacion caja detalle");
            }
        }

        $arRecibos = $this->getDoctrine()->getRepository(TteRecibo::class)->relacionCaja($id);
        return $this->render('transporte/movimiento/control/relacioncaja/detalle.html.twig', [
            'arRelacionCaja' => $arRelacionCaja,
            'arRecibos' => $arRecibos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/control/relacioncaja/detalle/adicionar/recibo/{id}", name="transporte_movimiento_control_relacioncaja_detalle_adicionar_recibo")
     */
    public function detalleAdicionarGuia(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = $em->getRepository(TteRelacionCaja::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $ar = $em->getRepository(TteRecibo::class)->find($codigo);
                    $ar->setRelacionCajaRel($arRelacionCaja);
                    $ar->setEstadoRelacion(1);
                    $em->persist($ar);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteRelacionCaja::class)->liquidar($id);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arRecibos = $this->getDoctrine()->getRepository(TteRecibo::class)->relacionPendiente();
        return $this->render('transporte/movimiento/control/relacioncaja/detalleAdicionarRecibo.html.twig', ['arRecibos' => $arRecibos, 'form' => $form->createView()]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'fechaDesde' => $form->get('fechaDesde')->getData() ?$form->get('fechaDesde')->getData()->format('Y-m-d'): null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ?$form->get('fechaHasta')->getData()->format('Y-m-d'): null,
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData()
        ];
        return $filtro;
    }

}

