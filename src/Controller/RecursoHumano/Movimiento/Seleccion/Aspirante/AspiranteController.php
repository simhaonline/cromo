<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Aspirante;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuAspirante;
use App\Form\Type\RecursoHumano\AspiranteType;
use App\General\General;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AspiranteController extends ControllerListenerGeneral
{

    protected $clase = RhuAspirante::class;
    protected $claseFormulario = AspiranteType::class;
    protected $claseNombre = "RhuAspirante";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Aspirante";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/aspirante/lista", name="recursohumano_movimiento_seleccion_aspirante_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Aspirantes");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuAspirante::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/aspirante/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/seleccion/aspirante/nuevo/{id}", name="recursohumano_movimiento_seleccion_aspirante_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAspirante = new RhuAspirante();
        if ($id != 0) {
            $arAspirante = $em->getRepository('App:RecursoHumano\RhuAspirante')->find($id);
            if (!$arAspirante) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
            }
        } else {
            $arAspirante->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(AspiranteType::class, $arAspirante);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAspirante->setNombreCorto($arAspirante->getNombre1().' '.$arAspirante->getNombre2().' '.$arAspirante->getApellido1().' '.$arAspirante->getApellido2());
                $em->persist($arAspirante);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/aspirante/nuevo.html.twig', [
            'form' => $form->createView(), 'arAspirante' => $arAspirante
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("recursohumano/movimiento/seleccion/aspirante/detalle/{id}", name="recursohumano_movimiento_seleccion_aspirante_detalle")
     */
    public function detalle(){
        return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_aspirante_lista'));
    }
}

