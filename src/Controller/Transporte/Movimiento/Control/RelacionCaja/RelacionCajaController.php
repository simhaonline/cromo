<?php

namespace App\Controller\Transporte\Movimiento\Control\RelacionCaja;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRelacionCaja;
use App\Form\Type\Transporte\RelacionCajaType;
use App\Formato\Transporte\RelacionCaja;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RelacionCajaController extends ControllerListenerGeneral
{
    protected $class= TteRelacionCaja::class;
    protected $claseNombre = "TteRelacionCaja";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Control";
    protected $nombre = "Relacion caja";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/control/relacioncaja/lista", name="transporte_movimiento_control_relacioncaja_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroTteRelacionCajaFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroTteRelacionCajaFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTteRelacionCajaFechaHasta'))])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTteRelacionCajaFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroTteRelacionCajaFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroTteRelacionCajaFiltroFecha', $form->get('filtrarFecha')->getData());
            }
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteRelacionCaja::class)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteRelacionCaja::class)->lista()->getQuery()->getResult(), "Relacion caja");
            }
        }
        $arRelacionesCaja = $paginator->paginate($em->getRepository(TteRelacionCaja::class)->lista(), $request->query->getInt('page', 1), 30);

        return $this->render('transporte/movimiento/control/relacioncaja/lista.html.twig', [
            'arRelacionesCaja' => $arRelacionesCaja,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/control/relacioncaja/nuevo/{codigoRelacionCaja}", name="transporte_movimiento_control_relacioncaja_nuevo")
     */
    public function nuevo(Request $request, $codigoRelacionCaja)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = new TteRelacionCaja();
        if($codigoRelacionCaja == 0) {
            $arRelacionCaja->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RelacionCajaType::class, $arRelacionCaja);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRelacionCaja = $form->getData();
            $em->persist($arRelacionCaja);
            $em->flush();
            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_nuevo', array('codigoRelacionCaja' => 0)));
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
     * @Route("/transporte/movimiento/control/relacioncaja/detalle/{codigoRelacionCaja}", name="transporte_movimiento_control_relacioncaja_detalle")
     */
    public function detalle(Request $request, $codigoRelacionCaja)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = $em->getRepository(TteRelacionCaja::class)->find($codigoRelacionCaja);
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
                $formato->Generar($em, $codigoRelacionCaja);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteRelacionCaja::class)->autorizar($arRelacionCaja);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', ['codigoRelacionCaja' => $codigoRelacionCaja]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteRelacionCaja::class)->desautorizar($arRelacionCaja);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', ['codigoRelacionCaja' => $codigoRelacionCaja]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteRelacionCaja::class)->aprobar($arRelacionCaja);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', ['codigoRelacionCaja' => $codigoRelacionCaja]));
            }
            if ($form->get('btnRetirarRecibo')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteRelacionCaja::class)->retirarRecibo($arr);
                if($respuesta) {
                    $em->flush();
                    $this->getDoctrine()->getRepository(TteRelacionCaja::class)->liquidar($codigoRelacionCaja);
                }
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', array('codigoRelacionCaja' => $codigoRelacionCaja)));
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteRecibo::class)->relacionCaja($codigoRelacionCaja), "Relacion caja detalle");
            }
        }

        $arRecibos = $this->getDoctrine()->getRepository(TteRecibo::class)->relacionCaja($codigoRelacionCaja);
        return $this->render('transporte/movimiento/control/relacioncaja/detalle.html.twig', [
            'arRelacionCaja' => $arRelacionCaja,
            'arRecibos' => $arRecibos,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/control/relacioncaja/detalle/adicionar/recibo/{codigoRelacionCaja}", name="transporte_movimiento_control_relacioncaja_detalle_adicionar_recibo")
     */
    public function detalleAdicionarGuia(Request $request, $codigoRelacionCaja)
    {
        $em = $this->getDoctrine()->getManager();
        $arRelacionCaja = $em->getRepository(TteRelacionCaja::class)->find($codigoRelacionCaja);
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
                $this->getDoctrine()->getRepository(TteRelacionCaja::class)->liquidar($codigoRelacionCaja);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arRecibos = $this->getDoctrine()->getRepository(TteRecibo::class)->relacionPendiente();
        return $this->render('transporte/movimiento/control/relacioncaja/detalleAdicionarRecibo.html.twig', ['arRecibos' => $arRecibos, 'form' => $form->createView()]);
    }

}

