<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Vacaciones;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Form\Type\RecursoHumano\VacacionType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VacacionesController extends BaseController
{
    protected $clase = RhuVacacion::class;
    protected $claseFormulario = VacacionType::class;
    protected $claseNombre = "RhuVacacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Vacacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/vacacion/lista", name="recursohumano_movimiento_nomina_vacacion_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                $this->getDatosExportar($formBotonera->getClickedButton()->getName(),$this->nombre);
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {

            }
        }
        return $this->render('recursoHumano/movimiento/nomina/vacacion/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/vacacion/nuevo/{id}", name="recursohumano_movimiento_nomina_vacacion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVacacion = new RhuVacacion();
        if ($id != 0) {
            $arVacacion = $em->getRepository($this->clase)->find($id);
        }
        $form = $this->createForm(VacacionType::class, $arVacacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arVacacion->getCodigoEmpleadoFk());
                if ($arEmpleado->getCodigoContratoFk()) {
                    $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                    if($id == 0){
                        $arVacacion->setFecha(new \DateTime('now'));
                    }
                    $arVacacion->setContratoRel($arContrato);
                    $arVacacion->setGrupoRel($arContrato->getGrupoRel());
                    $arVacacion->setEmpleadoRel($arEmpleado);
                    $em->persist($arVacacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_vacacion_detalle',['id' => $arVacacion->getCodigoVacacionPk()]));
                } else {
                    Mensajes::error('El empleado no tiene contratos activos en el sistema');
                }
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/vacacion/nuevo.html.twig', [
            'form' => $form->createView(),
            'arVacacion' => $arVacacion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/vacacion/detalle/{id}", name="recursohumano_movimiento_nomina_vacacion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        return $this->render('recursoHumano/movimiento/nomina/vacacion/detalle.html.twig', [
            'arRegistro' => $arRegistro
        ]);
    }
}

