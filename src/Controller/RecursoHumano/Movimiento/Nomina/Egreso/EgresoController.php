<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Egreso;

use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Form\Type\RecursoHumano\EgresoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            if (!$arEgreso) {
                return $this->render($this->generateUrl('recursohumano_movimiento_nomina_egreso_lista'));
            }
        }
        $form = $this->createForm(EgresoType::class, $arEgreso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arEgreso->getEgresoTipoRel()) {
                if ($arEgreso->getCuentaRel()) {
                    $em->persist($arEgreso);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_egreso_detalle', ['id' => $arEgreso->getCodigoEgresoPk()]));
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
        $arEgreso = $em->find(RhuEgreso::class, $id);
        $form = Estandares::botonera($arEgreso->getEstadoAutorizado(), $arEgreso->getEstadoAprobado(), $arEgreso->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
        $arEgresoDetalles = $em->getRepository(RhuEgresoDetalle::class)->listaEgresosDetalle($id);
        return $this->render('recursoHumano/movimiento/nomina/egreso/detalle.html.twig', [
            'arEgreso' => $arEgreso,
            'arEgresoDetalles' => $arEgresoDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $codigoPagoTipo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("recursohumano/movimiento/nomina/egreso/detalle/nuevo/{id}/{codigoPagoTipo}", name="recursohumano_movimiento_nomina_egreso_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id, $codigoPagoTipo)
    {
        $em = $this->getDoctrine()->getManager();
        $arPagos = $em->getRepository(RhuPago::class)->findBy(['estadoAprobado' => 1, 'estadoEgreso' => 0, 'codigoPagoTipoFk' => $codigoPagoTipo]);
        $arEgreso = $em->find(RhuEgreso::class, $id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados as $codigoPago) {
                        $arPago = $em->getRepository(RhuPago::class)->find($codigoPago);
                        if (!$arPago->getEstadoEgreso()) {
                            $arEgresoDetalle = new RhuEgresoDetalle();
                            $arEgresoDetalle->setEgresoRel($arEgreso);
                            $arEgresoDetalle->setPagoRel($arPago);
                            $valorPagar = round($arPago->getVrNeto());
                            $arEgresoDetalle->setVrPago($valorPagar);
                            $arEgresoDetalle->setBancoRel($arPago->getEmpleadoRel()->getBancoRel());
                            $arEgresoDetalle->setEmpleadoRel($arPago->getEmpleadoRel());
                            $arPago->setEstadoEgreso(1);
                            $em->persist($arEgresoDetalle);
                            $em->persist($arPago);
                        }
                    }
                    $em->flush();
                    $em->getRepository(RhuEgreso::class)->liquidar($id);
                    echo "<script languaje='javascript' type='text/javascript'>window.opener.location.reload();window.close();</script>";
                }
            }
        }
        return $this->render('recursoHumano/movimiento/nomina/egreso/detalleNuevo.html.twig', [
            'arPagos' => $arPagos,
            'form' => $form->createView()
        ]);
    }
}


