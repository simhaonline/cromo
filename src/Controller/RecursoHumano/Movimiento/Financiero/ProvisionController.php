<?php


namespace App\Controller\RecursoHumano\Movimiento\Financiero;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAportePlanilla;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuProvision;
use App\Entity\RecursoHumano\RhuProvisionDetalle;
use App\Entity\Transporte\TteGuia;
use App\Form\Type\RecursoHumano\AporteType;
use App\Form\Type\RecursoHumano\ProvisionPeriodoType;
use App\Form\Type\RecursoHumano\ProvisionType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProvisionController extends ControllerListenerGeneral
{
    protected $clase = RhuProvision::class;
    protected $claseNombre = "RhuProvision";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Financiero";
    protected $nombre = "Provision";

    /**
    * @Route("recursohumano/movimiento/financiero/provision/provision", name="recursohumano_movimiento_financiero_provision_provision")
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
				return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_provision_provision'));
			}
        }
        $arProvisiones = $paginator->paginate($em->getRepository(RhuProvision::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/financiero/provision/lista.html.twig', [
            'arProvisiones' => $arProvisiones,
            'form' => $form->createView()
        ]);
	}

    /**
     * @Route("recursohumano/movimiento/financiero/provision/nuevo/{id}", name="recursohumano_movimiento_financiero_provision_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arProvision = new RhuProvision();
        if ($id != 0) {
            $arProvision = $em->getRepository(RhuProvision::class)->find($id);
			if (!$arProvision) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_provision_provision'));
            }
		}else{
            $arProvision->setAnio((new \DateTime('now'))->format('Y'));
            $arProvision->setMes((new \DateTime('now'))->format('m'));
        }
        $form = $this->createForm(ProvisionType::class, $arProvision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arProvision->setFechaDesde($form->get('fechaDesde')->getData());
                $arProvision->setFechaHasta($form->get('fechaHasta')->getData());
                $arProvision->setAnio($arProvision->getFechaDesde()->format('Y'));
                $arProvision->setMes($arProvision->getFechaDesde()->format('m'));
                $em->persist($arProvision);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_provision_detalle', ['id' => $arProvision->getCodigoProvisionPk()]));
            }
        }
        return $this->render('recursohumano/movimiento/financiero/provision/nuevo.html.twig', [
            'form' => $form->createView(),
            'arProvision' => $arProvision
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/financiero/provision/detalle/{id}", name="recursohumano_movimiento_financiero_provision_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arProvision = $em->getRepository(RhuProvision::class)->find($id);
        if (!$arProvision) {
            return $this->redirect($this->generateUrl('recursohumano_movimiento_financiero_provision_provision'));
        }
        $form = Estandares::botonera($arProvision->getEstadoAutorizado(), $arProvision->getEstadoAprobado(), $arProvision->getEstadoAnulado());
        $form
            ->add( 'btnExcelContrato', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(RhuProvision::class)->autorizar($arProvision);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuProvision::class)->desAutorizar($arProvision);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(RhuProvision::class)->Aprobar($arProvision);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(RhuProvision::class)->Anular($arProvision);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seguridadsocial_aporte_detalle', array('id' => $id)));
            }
        }
        $arProvisionDetalles = $paginator->paginate($em->getRepository(RhuProvisionDetalle::class)->lista($id), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/financiero/provision/detalle.html.twig', [
            'arProvision' => $arProvision,
            'arProvisionDetalles'=>$arProvisionDetalles,
            'clase' => array('clase' => 'RhuProvision', 'codigo' => $id),
            'form'=>$form->createView()
        ]);
	}
}