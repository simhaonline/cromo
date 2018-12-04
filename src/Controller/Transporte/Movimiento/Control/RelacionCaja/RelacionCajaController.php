<?php

namespace App\Controller\Transporte\Movimiento\Control\RelacionCaja;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRelacionCaja;
use App\Form\Type\Transporte\RelacionCajaType;
use App\Formato\Transporte\RelacionCaja;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RelacionCajaController extends ControllerListenerGeneral
{
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
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
            }
        }

        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Relacion caja");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteRelacionCaja::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_lista'));
            }
        }

        return $this->render('transporte/movimiento/control/relacioncaja/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
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
                return $this->redirect($this->generateUrl('transporte_movimiento_control_relacioncaja_detalle', array('codigoRelacionCaja' => $id)));
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

}

