<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Form\Type\Transporte\RecaudoType;
use App\Formato\Transporte\RecaudoDevolucion;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecaudoDevolucionController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "TteRecaudoDevolucion";

    protected $clase= TteRecaudoDevolucion::class;
    protected $claseNombre = "TteRecaudoDevolucion";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "RecaudoDevolucion";
   /**
    * @Route("/transporte/movimiento/transporte/recaudodevolucion/lista", name="transporte_movimiento_transporte_recaudodevolucion_lista")
    */    
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = MaestroController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
            }
        }
        $datos = $this->getDatosLista(true, true, $paginator);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteRecaudoDevolucion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudodevolucion_lista'));
            }
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteRecaudoDevolucion::class)->lista()->getQuery()->execute(), "Recaudo devolucion");
            }
        }

        return $this->render('transporte/movimiento/transporte/recaudodevolucion/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);

    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudodevolucion/detalle/{id}", name="transporte_movimiento_transporte_recaudodevolucion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecaudo = $em->getRepository(TteRecaudoDevolucion::class)->find($id);
        $paginator  = $this->get('knp_paginator');
        $form = Estandares::botonera($arRecaudo->getEstadoAutorizado(),$arRecaudo->getEstadoAprobado(),$arRecaudo->getEstadoAnulado());
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $arrBtnRetirar = ['label' => 'Retirar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if($arRecaudo->getEstadoAutorizado()){
            $arrBtnRetirar['disabled'] = true;
        }
        $form->add('btnRetirarGuia', SubmitType::class, $arrBtnRetirar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {

            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteRecaudoDevolucion::class)->autorizar($arRecaudo);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudodevolucion_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteRecaudoDevolucion::class)->desAutorizar($arRecaudo);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudodevolucion_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteRecaudoDevolucion::class)->Aprobar($arRecaudo);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudodevolucion_detalle', ['id' => $id]));
            }
            if ($form->get('btnRetirarGuia')->isClicked()) {
                $arrGuias = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteRecaudoDevolucion::class)->retirarGuia($arrGuias);
                if($respuesta) {
                    $em->flush();
                    $em->getRepository(TteRecaudoDevolucion::class)->liquidar($id);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudodevolucion_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new RecaudoDevolucion();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->recaudo($id), "Recaudos $id");
            }
        }

        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->recaudo($id);
        return $this->render('transporte/movimiento/transporte/recaudodevolucion/detalle.html.twig', [
            'arRecaudo' => $arRecaudo,
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudo/detalle/adicionar/guia/{codigoRecaudo}", name="transporte_movimiento_transporte_recaudo_detalle_adicionar_guia")
     */
    public function detalleAdicionarGuia(Request $request, $codigoRecaudo)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecaudo = $em->getRepository(TteRecaudoDevolucion::class)->find($codigoRecaudo);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if (count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigo) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigo);
                    $arGuia->setRecaudoDevolucionRel($arRecaudo);
                    $arGuia->setEstadoRecaudoDevolucion(1);
                    $em->persist($arGuia);
                }
                $em->flush();
                $this->getDoctrine()->getRepository(TteRecaudoDevolucion::class)->liquidar($codigoRecaudo);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->recaudoPendiente($arRecaudo->getCodigoClienteFk());
        return $this->render('transporte/movimiento/transporte/recaudodevolucion/detalleAdicionarGuia.html.twig', ['arGuias' => $arGuias, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/recaudodevolucion/nuevo/{id}", name="transporte_movimiento_transporte_recaudodevolucion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arRecaudoDevolucion = new TteRecaudoDevolucion();
        if($id != 0) {
            $arRecaudoDevolucion = $em->getRepository(TteRecaudoDevolucion::class)->find($id);
        }
        $arRecaudoDevolucion->setUsuario($this->getUser()->getUserName());
        $form = $this->createForm(RecaudoType::class, $arRecaudoDevolucion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if($arCliente) {
                    $arRecaudoDevolucion->setClienteRel($arCliente);
                    $fecha = new \DateTime('now');
                    $arRecaudoDevolucion->setFecha($fecha);
                    $em->persist($arRecaudoDevolucion);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudodevolucion_nuevo', array('codigoRecaudo' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_recaudodevolucion_detalle', ['id' => $arRecaudoDevolucion->getCodigoRecaudoDevolucionPk()]));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/transporte/recaudodevolucion/nuevo.html.twig', [
            'arRecaudo' => $arRecaudoDevolucion,
            'form' => $form->createView()]);
    }


}

