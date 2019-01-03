<?php

namespace App\Controller\Crm\Movimiento\Control\Vista;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Crm\CrmVista;
use App\Form\Type\Crm\VistaType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class VistaController extends ControllerListenerGeneral
{
    protected $clase= CrmVista::class;
    protected $claseNombre = "CrmVista";
    protected $modulo = "Crm";
    protected $funcion = "Movimiento";
    protected $grupo = "Control";
    protected $nombre = "Vista";

    /**
     * @Route("/crm/movimiento/control/vista/lista", name="crm_movimiento_control_vista_lista")
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
                FuncionesController::generarSession($this->modulo,$this->nombre,$this->claseNombre,$formFiltro);
//                $datos = $this->getDatosLista();
            }
        }

        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);

        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Vista");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }

        return $this->render('crm/movimiento/control/vista/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/crm/movimiento/control/vista/nuevo/{id}", name="crm_movimiento_control_vista_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id == 0) {
            $arVista = new CrmVista();
            $arVista->setFecha(new \DateTime('now'));
        } else {
            $arVista= $em->getRepository(CrmVista::class)->find($id);
        }
        $form = $this->createForm(VistaType::class, $arVista);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arVista->setComentarios($form->get('comentarios')->getData());
                $em->persist($arVista);
                $em->flush();
                return $this->redirect($this->generateUrl('crm_movimiento_control_vista_lista'));
            }
        }
        return $this->render('crm/movimiento/control/vista/nuevo.html.twig', [
            'arServicio' => $arVista,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/crm/movimiento/control/vista/detalle/{id}", name="crm_movimiento_control_vista_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVista = $em->getRepository(CrmVista::class)->find($id);
        $form = Estandares::botonera($arVista->getEstadoAutorizado(),$arVista->getEstadoAprobado(),$arVista->getEstadoAnulado());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new CrmVista();
//                $formato->Generar($em, $id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->autorizar($arServicio);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->desautorizar($arServicio);
            }
            if ($form->get('btnAprobar')->isClicked()) {
//                $em->getRepository(InvServicio::class)->aprobar($arServicio);
            }
            if ($form->get('btnAnular')->isClicked()) {
//                $em->getRepository(InvServicio::class)->anular($arServicio);
            }
            return $this->redirect($this->generateUrl('crm_movimiento_control_vista_detalle',['id' => $arVista->getCodigoServicioPk()]));
        }

        return $this->render('crm/movimiento/control/vista/detalle.html.twig', [
            'arVista' => $arVista,
            'form' => $form->createView()]);

    }
}
