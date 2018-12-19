<?php

namespace App\Controller\General\Administracion\Tarea;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\General\GenTarea;
use App\Form\Type\General\TareaType;
use App\General\General;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TareaController extends ControllerListenerGeneral
{
    protected $clase = GenTarea::class;
    protected $claseNombre = "GenTarea";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "Tarea";
    protected $nombre = "Tarea";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/general/administracion/tarea/tarea/lista", name="general_administracion_tarea_tarea_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);

        $datos = $this->getDatosLista(false);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Usuarios");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                return $this->redirect($this->generateUrl('general_administracion_tarea_tarea_lista'));
            }
        }
        return $this->render('general/administracion/tarea/tarea/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/general/administracion/tarea/tarea/nuevo/{id}", name="general_administracion_tarea_tarea_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTarea = new GenTarea();
        if ($id != 0) {
            $arTarea = $em->find(GenTarea::class, $id);
        } else {
            $arTarea->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(TareaType::class, $arTarea);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arTarea->setUsuarioAsigna($this->getUser()->getUsername());
//            $arTarea->setUsuarioRecibe($arTarea->getUsuarioRecibeRel()->getUsername());
            $em->persist($arTarea);
            $em->flush();
            return $this->redirect($this->generateUrl('general_administracion_tarea_tarea_lista'));
        }
        return $this->render('general/administracion/tarea/tarea/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/general/administracion/tarea/tarea/nuevo/{id}", name="general_administracion_tarea_tarea_detalle")
     */
    public function detalle($id)
    {
        return $this->redirect($this->generateUrl('general_administracion_tarea_tarea_lista'));
    }
}
