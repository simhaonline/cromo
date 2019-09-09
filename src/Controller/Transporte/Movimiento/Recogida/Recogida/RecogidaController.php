<?php

namespace App\Controller\Transporte\Movimiento\Recogida\Recogida;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Entity\Transporte\TteRecogida;
use App\Form\Type\Transporte\RecogidaType;
use App\Formato\Transporte\Recogida;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecogidaController extends ControllerListenerGeneral
{
    protected $clase = TteRecogida::class;
    protected $claseNombre = "TteRecogida";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Recogida";
    protected $nombre = "Recogida";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/recogida/recogida/lista", name="transporte_movimiento_recogida_recogida_lista")
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
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                $fechaActual = new \DateTime('now');
                General::get()->setExportar($em->getRepository(TteRecogida::class)->lista()->getQuery()->execute(), "recogida {$fechaActual->format('ymd')}");

            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteRecogida::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_lista'));
            }

        }
        return $this->render('transporte/movimiento/recogida/recogida/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/recogida/detalle/{id}", name="transporte_movimiento_recogida_recogida_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = $em->getRepository(TteRecogida::class)->find($id);
        $form = Estandares::botonera($arRecogida->getEstadoAutorizado(), $arRecogida->getEstadoAprobado(), $arRecogida->getEstadoAnulado());
        $form->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Recogida();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteRecogida::class)->autorizar($arRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteRecaudoDevolucion::class)->desAutorizar($arRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_detalle', ['id' => $id]));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteRecaudoDevolucion::class)->Aprobar($arRecogida);
                return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_detalle', ['id' => $id]));
            }
        }
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->recogidas($id);
        return $this->render('transporte/movimiento/recogida/recogida/detalle.html.twig', [
            'arRecogida' => $arRecogida,
            'arDespachoRecogida' => $arDespachoRecogida,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/recogida/recogida/nuevo/{id}", name="transporte_movimiento_recogida_recogida_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = new TteRecogida();
        if ($id != 0) {
            $arRecogida = $em->getRepository(TteRecogida::class)->find($id);
        } else {
            $arRecogida->setFechaRegistro(new \DateTime('now'));
            $arRecogida->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RecogidaType::class, $arRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRecogida = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if ($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if ($arCliente) {
                    $arRecogida->setClienteRel($arCliente);
                    $arRecogida->setOperacionRel($this->getUser()->getOperacionRel());
                    $arRecogida->setAnunciante($arCliente->getNombreCorto());
                    $arRecogida->setDireccion($arCliente->getDireccion());
                    $arRecogida->setTelefono($arCliente->getTelefono());
                    $em->persist($arRecogida);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_nuevo', array('id' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('transporte_movimiento_recogida_recogida_detalle', ['id' => $arRecogida->getCodigoRecogidaPk()]));
                    }
                }
            }
        }
        return $this->render('transporte/movimiento/recogida/recogida/nuevo.html.twig', ['arRecogida' => $arRecogida, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/transporte/movimiento/recogida/recogida/reprogramar/{id}", name="transporte_movimiento_recogida_reprogramar_nuevo")
     */
    public function reprogramarRecodiga(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = $em->getRepository(TteRecogida::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('fecha', DateType::class, array('widget' => 'single_text', 'data' => $arRecogida->getFecha(), 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arRecogida->setFecha($form->get('fecha')->getData());
                $em->persist($arRecogida);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('transporte/movimiento/recogida/recogida/reprogramar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

