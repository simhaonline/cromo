<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Egreso;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use App\Form\Type\RecursoHumano\EgresoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EgresoController extends BaseController
{
    protected $clase = RhuEgreso::class;
    protected $claseFormulario = EgresoType::class;
    protected $claseNombre = "RhuEgreso";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Egreso";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/egreso/lista", name="recursohumano_movimiento_nomina_egreso_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Excel");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        return $this->render('recursoHumano/movimiento/nomina/egreso/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/egreso/nuevo/{id}", name="recursohumano_movimiento_nomina_egreso_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = new RhuEgreso();
        if ($id != 0) {
            $arEgreso = $em->find(RhuEgreso::class, $id);
            if(!$arEgreso){
                return $this->render($this->generateUrl('recursohumano_movimiento_nomina_egreso_lista'));
            }
        }
        $form = $this->createForm(EgresoType::class, $arEgreso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($arEgreso->getEgresoTipoRel()){
                if($arEgreso->getCuentaRel()){
                    $em->persist($arEgreso);
                    $em->flush();
                } else {
                    Mensajes::error('Debe seleccionar una cuenta');
                }
            } else {
                Mensajes::error('Debe seleccionar un tipo de egreso');
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/egreso/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/egreso/detalle/{id}", name="recursohumano_movimiento_nomina_egreso_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = $em->find(RhuEgreso::class,$id);
        $form = Estandares::botonera($arEgreso->getEstadoAutorizado(),$arEgreso->getEstadoAprobado(),$arEgreso->getEstadoAnulado());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }
        $arEgresoDetalles = $em->getRepository(RhuEgresoDetalle::class)->listaEgresosDetalle($id);
        return $this->render('recursoHumano/movimiento/nomina/egreso/detalle.html.twig', [
            'arEgreso' => $arEgreso,
            'arEgresoDetalles' => $arEgresoDetalles,
            'form' => $form->createView()
        ]);
    }
}

