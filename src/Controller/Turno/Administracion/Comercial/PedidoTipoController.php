<?php


namespace App\Controller\Turno\Administracion\Comercial;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\turno\turnoCliente;
use App\Entity\Turno\TurPedidoTipo;
use App\Form\Type\Turno\PedidoTipoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class PedidoTipoController extends ControllerListenerGeneral
{
    protected $clase = TurPedidoTipo::class;
    protected $claseFormulario = PedidoTipoType::class;
    protected $claseNombre = "TurPedidoTipo";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "comercial";
    protected $nombre = "PedidoTipo";

    /**
     * @Route("/turno/administracion/comercial/pedidotipo/lista", name="turno_administracion_comercial_pedidotipo_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroTurnoPedidoTipoNombre')))
            ->add('btnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('btnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->add('btnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurnoPedidoTipoNombre', $form->get('txtNombre')->getData());
            }
            if ($form->get('btnEliminar')->isClicked()){
                $arClienterSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurPedidoTipo::class, $arClienterSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_comercial_pedidotipo_lista'));
            }
        }
        $arPedidoTipos = $paginator->paginate($em->getRepository(TurPedidoTipo::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('turno/administracion/comercial/pedidotipo/lista.html.twig', array(
            'arPedidoTipos' => $arPedidoTipos,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/turno/administracion/comercial/pedidotipo/nuevo/{id}", name="turno_administracion_comercial_pedidotipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arPedidoTipo = $em->getRepository(TurPedidoTipo::class)->find($id);
        if ($id != 0) {
            $arPedidoTipo = new TurPedidoTipo();
            if (!$arPedidoTipo) {
                return $this->redirect($this->generateUrl('turno_administracion_comercial_pedidotipo_lista'));
            }
        }
        $form = $this->createForm(PedidoTipoType::class, $arPedidoTipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arPedidoTipo = $form->getData();
                $em->persist($arPedidoTipo);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_comercial_pedidotipo_detalle', ['id' => $arPedidoTipo->getCodigoPedidoTipoPk()]));
            }
        }
        return $this->render('turno/administracion/comercial/pedidotipo/nuevo.html.twig', array(
            'form' => $form->createView(),
            'arCliente' => $arPedidoTipo));
    }

    /**
     * @Route("/turno/administracion/comercial/pedidotipo/detalle/{id}", name="turno_administracion_comercial_pedidotipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminar')->isClicked()){
                $arClienterSeleccionados = $request->request->get('ChkSeleccionar');
                $this->get("UtilidadesModelo")->eliminar(TurPedidoTipo::class, $arClienterSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_comercial_cliente_detalle', ['id' => $id]));
            }
        }
        if ($id != 0) {
            $arCliente = $em->getRepository(TurPedidoTipo::class)->find($id);
            if (!$arCliente) {
                return $this->redirect($this->generateUrl('turno_administracion_comercial_cliente_lista'));
            }
        }

        $arPedidoTipo = $em->getRepository(TurPedidoTipo::class)->find($id);
        return $this->render('turno/administracion/comercial/pedidotipo/detalle.html.twig', array(
            'arPedidoTipo' => $arPedidoTipo
        ));
    }
}