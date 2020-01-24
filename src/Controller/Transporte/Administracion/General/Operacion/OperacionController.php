<?php


namespace App\Controller\Transporte\Administracion\General\Operacion;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteOperacion;
use App\Form\Type\Transporte\OperacionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class OperacionController extends MaestroController
{
    public $tipo = "Administracion";
    public $modelo = "TteOperacion";

    protected $clase = TteOperacion::class;
    protected $claseNombre = "TteOperacion";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Operacion";


    /**
     * @Route("/transporte/administracion/general/operacion/lista", name="transporte_administracion_general_operacion_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this-> createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroTteOperacionCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroTteOperacionNombre'), 'attr' => ['class' => 'form-control']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnEliminar')->isClicked()){
                $arOperacion = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TteOperacion::class, $arOperacion);
                return $this->redirect($this->generateUrl('transporte_administracion_general_operacion_lista'));
            }
            if ($form->get('btnFiltrar')->isClicked()){
                $session->set('filtroTteOperacionCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroTteOperacionNombre', $form->get('txtNombre')->getData());
            }
        }
        $arOperaciones = $paginator->paginate($em->getRepository(TteOperacion::class)->lista(), $request->query->get('page', 1), 20);
        return $this->render('transporte/administracion/general/operacion/lista.html.twig', [
            'arOperaciones' => $arOperaciones,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/transporte/administracion/general/operacion/nuevo/{id}", name="transporte_administracion_general_operacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arOperacion = $em->getRepository(TteOperacion::class)->find($id);
        if (is_null($arOperacion)) {
            $arOperacion = new TteOperacion();
        }
        $form = $this->createForm(OperacionType::class, $arOperacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arOperacion = $form->getData();
                $em->persist($arOperacion);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_general_ciudad_detalle', array('id' => $arOperacion->getCodigoOperacionPk())));
            }
        }
        return $this->render('transporte/administracion/general/operacion/nuevo.html.twig', [
            'arOperacion' => $arOperacion,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/transporte/administracion/general/operacion/detalle/{id}", name="transporte_administracion_general_operacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arOperacion = $em->getRepository(TteOperacion::class)->find($id);
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('pais_detalle', ['id' => $id]));
        }
        return $this->render('transporte/administracion/general/operacion/detalle.html.twig', [
            'form' => $form->createView(),
            'arOperacion' => $arOperacion,
        ]);
    }
}