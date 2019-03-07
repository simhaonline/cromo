<?php

namespace App\Controller\Crm\Movimiento\Control\Visita;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmVisita;
use App\Entity\Crm\CrmVisitaReporte;
use App\Form\Type\Crm\VisitaReporteType;
use App\Form\Type\Crm\VisitaType;
use App\Formato\Crm\Visita;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class VisitaController extends ControllerListenerGeneral
{
    protected $clase = CrmVisita::class;
    protected $claseNombre = "CrmVisita";
    protected $modulo = "Crm";
    protected $funcion = "Movimiento";
    protected $grupo = "Control";
    protected $nombre = "Visita";

    /**
     * @Route("/crm/movimiento/control/visita/lista", name="crm_movimiento_control_visita_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
//                $datos = $this->getDatosLista();
            }
        }

        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);

        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Visita");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('App:Crm\CrmVisita')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('crm_movimiento_control_visita_lista'));
            }
        }

        return $this->render('crm/movimiento/control/visita/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/control/visita/nuevo/{id}", name="crm_movimiento_control_visita_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $arVisita = new CrmVisita();
            $arVisita->setFecha(new \DateTime('now'));
        } else {
            $arVisita = $em->getRepository(CrmVisita::class)->find($id);
        }
        $form = $this->createForm(VisitaType::class, $arVisita);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arVisita->setComentarios($form->get('comentarios')->getData());
                $em->persist($arVisita);
                $em->flush();
                return $this->redirect($this->generateUrl('crm_movimiento_control_visita_detalle', ['id' => $arVisita->getCodigoVisitaPk()]));
            }
        }
        return $this->render('crm/movimiento/control/visita/nuevo.html.twig', [
            'arServicio' => $arVisita,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/control/visita/detalle/{id}", name="crm_movimiento_control_visita_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVisita = $em->getRepository(CrmVisita::class)->find($id);
        $form = Estandares::botonera($arVisita->getEstadoAutorizado(), $arVisita->getEstadoAprobado(), $arVisita->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Visita();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->autorizar($arVisita);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->desautorizar($arVisita);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->aprobar($arVisita);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository('App\Entity\Crm\CrmVisita')->anular($arVisita);
            }
            return $this->redirect($this->generateUrl('crm_movimiento_control_visita_detalle', ['id' => $arVisita->getCodigoVisitaPk()]));
        }
        $arReportes = $em->getRepository(CrmVisitaReporte::class)->reporte($id);
        return $this->render('crm/movimiento/control/visita/detalle.html.twig', [
            'clase' => array(
                'clase' => 'CrmVisita', 'codigo' => $id),
            'arVisita' => $arVisita,
            'arReportes' => $arReportes,
            'form' => $form->createView()]);

    }

    /**
     * @param Request $request
     * @param $codigoVisita
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/crm/movimiento/control/visita/detalle/reporte/{codigoVisita}/{id}", name="crm_movimiento_control_visita_detalle_reporte")
     */
    public function reporte(Request $request, $codigoVisita, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arReporte = new CrmVisitaReporte();
        $arVisita = $em->getRepository(CrmVisita::class)->find($codigoVisita);
        if ($id != 0) {
            $arReporte = $em->getRepository(CrmVisitaReporte::class)->find($id);
        }
        $form = $this->createForm(VisitaReporteType::class, $arReporte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arReporte->setVisitaRel($arVisita);
                $arReporte->setFecha(new \DateTime('now'));
                $em->persist($arReporte);
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }
        return $this->render('crm/movimiento/control/visita/reporte.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
