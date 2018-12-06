<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Embargo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\EmbargoType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmbargoController extends ControllerListenerGeneral
{
    protected $clase = RhuEmbargo::class;
    protected $claseFormulario = EmbargoType::class;
    protected $claseNombre = "RhuEmbargo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "Embargo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/embargo/lista", name="recursohumano_movimiento_nomina_embargo_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Embargos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuEmbargo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_embargo_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/embargo/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/embargo/nuevo/{id}", name="recursohumano_movimiento_nomina_embargo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargo = new RhuEmbargo();
        if($id != 0){
            $arEmbargo = $em->getRepository(RhuEmbargo::class)->find($id);
        }
        $form = $this->createForm(EmbargoType::class, $arEmbargo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('guardar')->isClicked()){
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arEmbargo->getCodigoEmpleadoFk());
                $arJuzgado = $em->getRepository(RhuEmbargoJuzgado::class)->find($arEmbargo->getCodigoEmbargoJuzgadoFk());
                if($id == 0){
                    $arEmbargo->setEstadoActivo(1);
                    $arEmbargo->setFecha(new \DateTime('now'));
                }
                $arEmbargo->setEmpleadoRel($arEmpleado);
                $arEmbargo->setEmbargoJuzgadoRel($arJuzgado);
                $em->persist($arEmbargo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_embargo_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/embargo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arEmbargo' => $arEmbargo
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/embargo/detalle/{id}", name="recursohumano_movimiento_nomina_embargo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEmbargo = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arEmbargo->getEstadoAutorizado(), $arEmbargo->getEstadoAprobado(), $arEmbargo->getEstadoAnulado());
        $form->handleRequest($request);
        return $this->render('recursohumano/movimiento/nomina/embargo/detalle.html.twig',[
            'arEmbargo' => $arEmbargo
        ]);
    }
}

