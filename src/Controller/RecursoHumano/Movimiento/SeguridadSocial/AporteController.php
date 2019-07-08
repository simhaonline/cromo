<?php


namespace App\Controller\RecursoHumano\Movimiento\SeguridadSocial;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAportePlanilla;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\Transporte\TteGuia;
use App\Form\Type\RecursoHumano\AporteType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AporteController extends ControllerListenerGeneral
{
    protected $clase = RhuAporte::class;
    protected $claseNombre = "RhuAporte";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "SeguridadSocial";
    protected $nombre = "Aporte";

    /**
    * @Route("recursohumano/movimiento/seguridadsocial/aporte/lista", name="recursohumano_movimiento_seguridadsocial_aporte_lista")
    */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtAnio', TextType::class, ['required' => false])
            ->add('txtMes', ChoiceType::class, [
                'choices' => array(
                    'Enero' => '1', 'Febrero' => '2', 'Marzo' => '3', 'Abril' => '4', 'Mayo' => '5', 'Junio' => '6', 'Julio' => '7',
                    'Agosto' => '8', 'Septiembre' => '9', 'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12',
                ),
                'required'    => false,
                'placeholder' => '',
            ])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnEliminar', SubmitType::class, ['label'=>'Eliminar', 'attr'=>['class'=> 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuAporteAnio', $form->get('txtAnio')->getData());
                $session->set('filtroRhuAporteMes', $form->get('txtMes')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuAporte::class, $arrSeleccionados);
				return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_lista'));
			}
        }
        $arAportes = $paginator->paginate($em->getRepository(RhuAporte::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/lista.html.twig', [
            'arAportes' => $arAportes,
            'form' => $form->createView()
        ]);
	}

    /**
     * @Route("recursohumano/movimiento/seguridadsocial/aporte/nuevo/{id}", name="recursohumano_movimiento_seguridadsocial_aporte_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAporte = new RhuAporte();
        if ($id != 0) {
            $arAporte = $em->getRepository(RhuAporte::class)->find($id);
			if (!$arAporte) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_lista'));
            }
		}else{
            $arAporte->setAnio((new \DateTime('now'))->format('Y'));
            $arAporte->setMes((new \DateTime('now'))->format('m'));
        }
        $form = $this->createForm(AporteType::class, $arAporte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAporte = $form->getData();
                $fechas = FuncionesController::desdeHastaAnioMes($arAporte->getAnio(), $arAporte->getMes());
                $arAporte->setFechaDesde($fechas['fechaDesde']);
                $arAporte->setFechaHasta($fechas['fechaHasta']);
                $em->persist($arAporte);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', ['id' => $arAporte->getCodigoAportePk()]));
            }
        }
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/nuevo.html.twig', [
            'form' => $form->createView(),
            'arAporte' => $arAporte
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seguridadsocial/aporte/detalle/{id}", name="recursohumano_movimiento_seguridadsocial_aporte_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        if ($id != 0) {
            $arAporte = $em->getRepository(RhuAporte::class)->find($id);
            if (!$arAporte) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_lista'));
            }
        }
        $form = $this->createFormBuilder()
            ->add( 'btnExcelContrato', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnExcelDetalle', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnCargarContratos', SubmitType::class, ['label'=>'Cargar contratos', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnSoporte', SubmitType::class, ['label'=>'Soporte', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnDetalle', SubmitType::class, ['label'=>'Detalle', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcelContrato')->isClicked()) {
                General::get()->setExportar($em->getRepository(RhuAporteContrato::class)->lista(), "aporte contrato");
            }
            if ($form->get('btnCargarContratos')->isClicked()) {
                $em->getRepository(RhuAporteContrato::class)->cargar($arAporte);
                $em->getRepository(RhuAporteSoporte::class)->generar($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnSoporte')->isClicked()) {
                $em->getRepository(RhuAporteSoporte::class)->generar($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnDetalle')->isClicked()) {
                $em->getRepository(RhuAporteDetalle::class)->generar($arAporte);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
        }
        $arAporteDetalles = null;
        $arAporteContratos= $paginator->paginate($em->getRepository(RhuAporteContrato::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/detalle.html.twig', [
            'arAporte' => $arAporte,
            'arAporteContratos'=>$arAporteContratos,
            'arAporteDetalles' => $arAporteDetalles,
            'form'=>$form->createView()
        ]);
	}

    /**
     * @Route("recursohumano/movimiento/seguridadsocial/soporte/aporte/detalle/{id}", name="recursohumano_movimiento_seguridadsocial_soporte_aporte_detalle")
     */
    public function soporte(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAporteContrato = $em->getRepository(RhuAporteContrato::class)->find($id);

        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        $arAporteSoportes = $em->getRepository(RhuAporteSoporte::class)->listaSoporte($id);
        return $this->render('recursohumano/movimiento/seguridadsocial/aporte/soporte.html.twig', [
            'arAporteContrato' => $arAporteContrato,
            'arAporteSoportes' => $arAporteSoportes,
            'form' => $form->createView()
        ]);
    }

}