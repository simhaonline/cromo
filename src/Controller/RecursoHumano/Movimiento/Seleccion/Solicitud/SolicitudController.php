<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Solicitud;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAspirante;
use App\Entity\RecursoHumano\RhuSolicitud;
use App\Form\Type\RecursoHumano\AspiranteType;
use App\Form\Type\RecursoHumano\SolicitudType;
use App\General\General;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SolicitudController extends ControllerListenerGeneral
{

    protected $clase = RhuSolicitud::class;
    protected $claseFormulario = SolicitudType::class;
    protected $claseNombre = "RhuSolicitud";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Solicitud";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/solicitud/lista", name="recursohumano_movimiento_seleccion_solicitud_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Solicitudes");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuSolicitud::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
            }
        }
        return $this->render('recursoHumano/movimiento/seleccion/solicitud/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/solicitud/nuevo/{id}", name="recursohumano_movimiento_seleccion_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new RhuSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository('App:RecursoHumano\RhuSolicitud')->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'solicitud']));
            }
        }
        $form = $this->createForm(SolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSolicitud->setFecha(new \DateTime('now'));
                $em->persist($arSolicitud);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano','entidad' => 'solicitud']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSolicitud);
                $em->flush($arSolicitud);
                return $this->redirect($this->generateUrl('recursoHumano_movimiento_seleccion_solicitud_nuevo', ['codigoSolicitud' => 0]));
            }
        }
        return $this->render('recursoHumano/movimiento/solicitud/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arSolicitud
        ]);
    }
}