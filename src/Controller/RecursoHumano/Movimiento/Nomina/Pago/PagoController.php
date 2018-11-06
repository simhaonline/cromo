<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Pago;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Form\Type\RecursoHumano\PagoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PagoController extends BaseController
{
    protected $clase = RhuPago::class;
    protected $claseFormulario = PagoType::class;
    protected $claseNombre = "RhuPago";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Pago";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/pago/lista", name="recursohumano_movimiento_nomina_pago_lista")
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
        return $this->render('recursoHumano/movimiento/nomina/pago/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/pago/nuevo/{id}", name="recursohumano_movimiento_nomina_pago_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        Mensajes::error('Esta funcion aun no esta disponible');
        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_pago_lista'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/pago/detalle/{id}", name="recursohumano_movimiento_nomina_pago_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arPago = $em->getRepository(RhuPago::class)->find($id);
        $form = Estandares::botonera($arPago->getEstadoAutorizado(),$arPago->getEstadoAprobado(),$arPago->getEstadoAnulado());
        $form->handleRequest($request);

        $arPagoDetalles = $paginator->paginate($em->getRepository(RhuPagoDetalle::class)->lista($id), $request->query->getInt('page', 1), 30);
        return $this->render('recursoHumano/movimiento/nomina/pago/detalle.html.twig',[
            'arPago' => $arPago,
            'arPagoDetalles' => $arPagoDetalles,
            'form' => $form->createView()
        ]);
    }

}