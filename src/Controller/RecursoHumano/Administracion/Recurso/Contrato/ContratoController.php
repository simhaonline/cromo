<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;


use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Form\Type\RecursoHumano\ContratoType;
use App\General\General;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContratoController extends BaseController
{
    protected $clase = RhuContrato::class;
    protected $claseFormulario = ContratoType::class;
    protected $claseNombre = "RhuContrato";
    protected $modulo = "RecursoHumano";
    protected $funcion = "administracion";
    protected $grupo = "Recurso";
    protected $nombre = "Contrato";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/administracion/recurso/contrato/lista", name="recursohumano_administracion_recurso_contrato_lista")
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
        return $this->render('recursoHumano/administracion/recurso/contrato/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/{id}", name="recursohumano_administracion_recurso_contrato_detalle")
     */
    public function detalle(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/nuevo/{id}", name="recursohumano_administracion_recurso_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
    }
}

