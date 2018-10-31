<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Adicional;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\AdicionalType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdicionalController extends BaseController
{
    protected $clase = RhuAdicional::class;
    protected $claseFormulario = AdicionalType::class;
    protected $claseNombre = "RhuAdicional";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Adicional";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/adicional/lista", name="recursohumano_movimiento_nomina_adicional_lista")
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
        return $this->render('recursoHumano/movimiento/nomina/adicional/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/adicional/nuevo/{id}", name="recursohumano_movimiento_nomina_adicional_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAdicional = new RhuAdicional();
        if ($id != 0) {
            $arAdicional = $em->getRepository(RhuAdicional::class)->find($id);
        } else {
            $arAdicional->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(AdicionalType::class, $arAdicional);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arAdicional->getCodigoEmpleadoFk());
                if ($arEmpleado->getCodigoContratoFk()) {
                    $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                    $arAdicional->setEmpleadoRel($arEmpleado);
                    $arAdicional->setContratoRel($arContrato);
                    $em->persist($arAdicional);
                    $em->flush();
                } else {
                    Mensajes::error('El empleado no tiene un contrato activo en el sistema');
                }
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/adicional/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/adicional/detalle/{id}", name="recursohumano_movimiento_nomina_adicional_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        return $this->render('recursoHumano/movimiento/nomina/adicional/detalle.html.twig', [
            'arRegistro' => $arRegistro
        ]);
    }
}

