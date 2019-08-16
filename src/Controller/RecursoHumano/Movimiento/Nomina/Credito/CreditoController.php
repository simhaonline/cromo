<?php

namespace App\Controller\RecursoHumano\Movimiento\Nomina\Credito;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\CreditoPagoType;
use App\Form\Type\RecursoHumano\CreditoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CreditoController extends ControllerListenerGeneral
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/credito/lista", name="recursohumano_movimiento_nomina_credito_lista")
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
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Creditos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuCredito::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_credito_lista'));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/credito/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView(),
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
        $em = $this->getDoctrine()->getManager();
        $arCredito = new RhuCredito();
        if ($id != 0) {
            $arCredito = $em->getRepository($this->clase)->find($id);
        } else {
            $arCredito->setFechaCredito(new \DateTime('now'));
            $arCredito->setFechaInicio(new \DateTime('now'));
            $arCredito->setFechaFinalizacion(new \DateTime('now'));
        }
        $form = $this->createForm(CreditoType::class, $arCredito);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arCredito->getCodigoEmpleadoFk());
                if ($arEmpleado) {
                    $arContrato = null;
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
                    } elseif ($arEmpleado->getCodigoContratoUltimoFk()) {
                        $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoUltimoFk());
                    }
                    if ($arContrato != null) {
                        if ($id == 0) {
                            $arCredito->setFecha(new \DateTime('now'));
                        }
                        $arCredito->setGrupoRel($arContrato->getGrupoRel());
                        $arCredito->setEmpleadoRel($arEmpleado);
                        $arCredito->setContratoRel($arContrato);
                        $arCredito->setUsuario($this->getUser()->getUsername());
                        $arCredito->setVrSaldo($arCredito->getVrCredito() - $arCredito->getVrAbonos());
                        $em->persist($arCredito);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_credito_detalle', ['id' => $arCredito->getCodigoCreditoPk()]));
                    } else {
                        Mensajes::error('El empleado no tiene contratos en el sistema');
                    }
                } else {
                    Mensajes::error('No se ha encontrado un empleado con el codigo ingresado');
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/credito/nuevo.html.twig', [
            'form' => $form->createView(),
            'arCredito' => $arCredito
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/credito/detalle/{id}", name="recursohumano_movimiento_nomina_credito_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->clase)->find($id);
        $form = Estandares::botonera($arRegistro->getEstadoAutorizado(), $arRegistro->getEstadoAprobado(), $arRegistro->getEstadoAnulado());
        $form->handleRequest($request);
        $arCreditoPagos = $paginator->paginate($em->getRepository(RhuCreditoPago::class)->listaPorCredito($id), $request->query->getInt('PageCreditoPago', 1), 30,
            array(
                'pageParameterName' => 'PageCreditoPago',
                'sortFieldParameterName' => 'sortPageCreditoPago',
                'sortDirectionParameterName' => 'directionPageCreditoPago',
            ));
        return $this->render('recursohumano/movimiento/nomina/credito/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'arCreditoPagos' => $arCreditoPagos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/movimiento/nomina/credito/detalle/nuevo/{id}", name="recursohumano_movimiento_nomina_credito_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCredito = $em->getRepository(RhuCredito::class)->find($id);
        $arCreditoPago = New RhuCreditoPago();
        $form = $this->createForm(CreditoPagoType::class, $arCreditoPago);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arCredito->getEstadoPagado() == 0) {
                $saldoActual = $arCredito->getVrSaldo();
                $abono = $form->get('vrPago')->getData();
                if ($abono > $arCredito->getVrSaldo()) {
                    Mensajes::error("El valor del pago no puede ser superior al saldo");
                } else {
                    $arCredito->setVrSaldo($saldoActual - $abono);
                    if ($arCredito->getVrSaldo() == 0) {
                        $arCredito->setEstadoPagado(1);
                    }
                    $cuotasActuales = $arCredito->getNumeroCuotaActual();
                    $arCredito->setNumeroCuotaActual($cuotasActuales + 1);
                    $arCreditoPago->setCreditoRel($arCredito);
                    $arCreditoPago->setfechaPago(new \ DateTime("now"));
                    $em->persist($arCreditoPago);
                    $em->persist($arCredito);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            } else {
                Mensajes::error("El credito ya se encuentra pagado");
            }
        }
        return $this->render('recursohumano/movimiento/nomina/credito/detalleNuevo.html.twig', [
            'arCredito' => $arCredito,
            'arCreditoPago' => $arCreditoPago,
            'form' => $form->createView()
        ]);
    }
}

