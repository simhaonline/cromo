<?php

namespace App\Controller\Transporte\Movimiento\Monitoreo\Monitoreo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteMonitoreoDetalle;
use App\Entity\Transporte\TteMonitoreoRegistro;
use App\Form\Type\Transporte\MonitoreoDetalleType;
use App\Formato\Transporte\Monitoreo;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class MonitoreoController extends ControllerListenerGeneral
{
    protected $clase= TteMonitoreo::class;
    protected $claseNombre = "TteMonitoreo";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Monitoreo";
    protected $nombre = "Monitoreo";

   /**
    * @Route("/transporte/movimiento/monitoreo/monitoreo/lista", name="transporte_movimiento_monitoreo_monitoreo_lista")
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
                General::get()->setExportar($em->getRepository(TteMonitoreo::class)->lista()->getQuery()->execute(), "Monitoreo");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(TteFactura::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_movimiento_monitoreo_monitoreo_lista'));
            }
        }

        return $this->render('transporte/movimiento/monitoreo/monitoreo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);

    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/nuevo/{id}", name="transporte_movimiento_monitoreo_monitoreo_nuevo")
     */
    public function nuevo(){
        return $this->redirect($this->generateUrl('transporte_movimiento_monitoreo_monitoreo_lista'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/transporte/movimiento/monitoreo/monitoreo/detalle/{id}", name="transporte_movimiento_monitoreo_monitoreo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMonitoreo = $em->getRepository(TteMonitoreo::class)->find($id);
        $form = Estandares::botonera($arMonitoreo->getEstadoAutorizado(), $arMonitoreo->getEstadoAprobado(), $arMonitoreo->getEstadoAnulado());
        $form
            ->add('btnRetirarDetalle', SubmitType::class, array('label' => 'Retirar'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnRetirarDetalle')->isClicked()) {
                $arrMonitoreoDetalle = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteMonitoreoDetalle::class)->eliminar($arrMonitoreoDetalle);
                return $this->redirect($this->generateUrl('transporte_movimiento_monitoreo_monitoreo_detalle', ['id' => $id]));
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TteMonitoreo::class)->autorizar($arMonitoreo);
                return $this->redirect($this->generateUrl('transporte_movimiento_monitoreo_monitoreo_detalle', array('id' => $id)));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TteMonitoreo::class)->desautorizar($arMonitoreo);
                return $this->redirect($this->generateUrl('transporte_movimiento_monitoreo_monitoreo_detalle', array('id' => $id)));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TteMonitoreo::class)->aprobar($arMonitoreo);
                return $this->redirect($this->generateUrl('transporte_movimiento_monitoreo_monitoreo_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new  Monitoreo();
                $formato->Generar($em, $id);
            }
        }
        $arMonitoreoDetalles = $this->getDoctrine()->getRepository(TteMonitoreoDetalle::class)->monitoreo($id);
        $arMonitoreoRegistros = $this->getDoctrine()->getRepository(TteMonitoreoRegistro::class)->monitoreo($id);
        return $this->render('transporte/movimiento/monitoreo/monitoreo/detalle.html.twig', [
            'arMonitoreo' => $arMonitoreo,
            'arMonitoreoDetalles' => $arMonitoreoDetalles,
            'arMonitoreoRegistros' => $arMonitoreoRegistros,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/detalle/adicionar/reporte/{codigoMonitoreo}/{codigoMonitoreoDetalle}", name="transporte_movimiento_monitoreo_monitoreo_detalle_adicionar_reporte")
     */
    public function detalleAdicionar(Request $request, $codigoMonitoreo, $codigoMonitoreoDetalle)
    {
        $em = $this->getDoctrine()->getManager();
        $arMonitoreo = $em->getRepository(TteMonitoreo::class)->find($codigoMonitoreo);
        $arMonitoreoDetalle = new TteMonitoreoDetalle();
        $form = $this->createForm(MonitoreoDetalleType::class, $arMonitoreoDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($codigoMonitoreoDetalle == 0) {
                $arMonitoreoDetalle->setFechaRegistro(new \DateTime('now'));
                $arMonitoreoDetalle->setFechaReporte(new \DateTime('now'));
                $arMonitoreoDetalle->setMonitoreoRel($arMonitoreo);
            }
            $em->persist($arMonitoreoDetalle);
            $em->flush();

            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }

        return $this->render('transporte/movimiento/monitoreo/monitoreo/detalleAdicionar.html.twig', [
            'arMonitoreoDetalle' => $arMonitoreoDetalle,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/monitoreo/monitoreo/mapa/{codigoMonitoreo}", name="transporte_movimiento_monitoreo_monitoreo_mapa")
     */
    public function verMapa(Request $request, $codigoMonitoreo)
    {
        $em = $this->getDoctrine()->getManager();
        //$googleMapsApiKey = $arConfiguracion->getGoogleMapsApiKey();
        //$googleMapsApiKey = "AIzaSyBXwGxeTtvba8Uset2XFjuwAxdRmJlkdcY";
        //Esta es la key de alejandro
        $googleMapsApiKey = "AIzaSyBEONds48sofQeiVLeOewxouvqo203DfZU";
        $arrDatos = $em->getRepository(TteMonitoreoRegistro::class)->datosMapa($codigoMonitoreo);

        return $this->render('transporte/movimiento/monitoreo/monitoreo/mapaRegistro.html.twig', [
            'datos' => $arrDatos ?? [],
            'apikey' => $googleMapsApiKey]);
    }

}

