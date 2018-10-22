<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Credito;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuCredito;
use App\Form\Type\RecursoHumano\CreditoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CreditoController extends BaseController
{
    protected $clase = RhuCredito::class;
    protected $claseFormulario = CreditoType::class;
    protected $claseNombre = "RhuCredito";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Credito";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/credito/lista", name="recursohumano_movimiento_nomina_credito_lista")
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
        return $this->render('recursoHumano/movimiento/nomina/credito/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/credito/nuevo/{id}", name="recursohumano_movimiento_nomina_credito_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_credito_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/credito/detalle/{id}", name="recursohumano_movimiento_nomina_credito_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        return $this->render('recursoHumano/movimiento/nomina/credito/detalle.html.twig',[
            'arRegistro' => $arRegistro
        ]);
    }
}

