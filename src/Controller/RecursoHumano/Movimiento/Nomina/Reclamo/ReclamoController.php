<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Reclamo;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuReclamo;
use App\Form\Type\RecursoHumano\ReclamoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReclamoController extends BaseController
{
    protected $clase = RhuReclamo::class;
    protected $claseFormulario = ReclamoType::class;
    protected $claseNombre = "RhuReclamo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Reclamo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/reclamo/lista", name="recursohumano_movimiento_nomina_reclamo_lista")
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
        return $this->render('recursoHumano/movimiento/nomina/reclamo/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/reclamo/nuevo/{id}", name="recursohumano_movimiento_nomina_reclamo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_reclamo_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/reclamo/detalle/{id}", name="recursohumano_movimiento_nomina_reclamo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        return $this->render('recursoHumano/movimiento/nomina/reclamo/detalle.html.twig',[
            'arRegistro' => $arRegistro
        ]);
    }
}

