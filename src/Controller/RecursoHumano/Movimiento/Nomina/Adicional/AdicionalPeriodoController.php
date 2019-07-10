<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina\Adicional;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAdicionalPeriodo;
use App\Form\Type\RecursoHumano\AdicionalPeriodoType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AdicionalPeriodoController extends ControllerListenerGeneral
{
    protected $clase = RhuAdicionalPeriodo::class;
    protected $claseFormulario = AdicionalPeriodoType::class;
    protected $claseNombre = "RhuAdicionalPeriodo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "AdicionalPeriodo";

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/lista", name="recursohumano_movimiento_nomina_adicionalperiodo_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('estado', ChoiceType::class,  [
                'label' => 'CERRADOS:',
                'choices' => ['TODOS' => '','sin cerrar' => true, 'cerrar' => false],
                'required' => false
            ])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add( 'btnExcel', SubmitType::class, ['label'=>'Excel', 'attr'=>['class'=> 'btn btn-sm btn-default']])
            ->add( 'btnEliminar', SubmitType::class, ['label'=>'Eliminar', 'attr'=>['class'=> 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroRhuAdicionalPeriodoEstado', $form->get('estado')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(RhuAdicionalPeriodo::class, $arrSeleccionados);
				return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_lista'));
			}
            
        }
        $arAdicionalPeriodos = $paginator->paginate($em->getRepository(RhuAdicionalPeriodo::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/lista.html.twig', [
            'arAdicionalPeriodos' => $arAdicionalPeriodos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/nuevo/{id}", name="recursohumano_movimiento_nomina_adicionalperiodo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAdicionalPeriodo = new RhuAdicionalPeriodo();
        if ($id != 0) {
            $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);
        } else {
            $arAdicionalPeriodo->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(AdicionalPeriodoType::class, $arAdicionalPeriodo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAdicionalPeriodo = $form->getData();
                $em->persist($arAdicionalPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_detalle', array('id' => $arAdicionalPeriodo->getCodigoAdicionalPeriodoPk())));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/nuevo.html.twig', [
            'arAdicionalPeriodo' => $arAdicionalPeriodo,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/detalle/{id}", name="recursohumano_movimiento_nomina_adicionalperiodo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);
            if (!$arAdicionalPeriodo) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_lista'));
            }
        }
        $arNegocio = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/detalle.html.twig', [
            'arAdicionalPeriodo' => $arAdicionalPeriodo
        ]);
    }
}