<?php

namespace App\Controller\Turno\Movimiento\Comercial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Form\Type\Turno\ContratoType;
use App\Form\Type\Turno\ContratoDetalleType;
use App\General\General;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContratoController extends ControllerListenerGeneral
{
    protected $clase = TurContrato::class;
    protected $claseNombre = "TurContrato";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "Contrato";


    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/comercial/contrato/lista", name="turno_movimiento_comercial_contrato_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Contratos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurContrato::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_comercial_contrato_lista'));
            }
        }
        return $this->render('turno/movimiento/comercial/contrato/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/turno/movimiento/contrato/nuevo/{id}", name="turno_movimiento_comercial_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = new TurContrato();
        if ($id != '0') {
            $arContrato = $em->getRepository(TurContrato::class)->find($id);
            if (!$arContrato) {
                return $this->redirect($this->generateUrl('turno_movimiento_comercial_contrato_lista'));
            }
        } else {
            $arContrato->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(ContratoType::class, $arContrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TurCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arContrato->setClienteRel($arCliente);
                        $arContrato->setFechaGeneracion(new \DateTime('now'));
                        if ($id == 0) {
                            $arContrato->setFechaGeneracion(new \DateTime('now'));
                            $arContrato->setUsuario($this->getUser()->getUserName());
                        }
                        $em->persist($arContrato);
                        $em->flush();
                        return $this->redirect($this->generateUrl('turno_movimiento_comercial_contrato_detalle', ['id' => $arContrato->getCodigoContratoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }

            }
        }
        return $this->render('turno/movimiento/comercial/contrato/nuevo.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/turno/movimiento/contrato/detalle/{id}", name="turno_movimiento_comercial_contrato_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arContratos = $em->getRepository(TurContrato::class)->find($id);
        $form = Estandares::botonera($arContratos->getEstadoAutorizado(), $arContratos->getEstadoAprobado(), $arContratos->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arContratos->getEstadoAutorizado()) {
            $arrBtnAutorizar['disable'] = true;
            $arrBtnEliminar['disable'] = true;
            $arrBtnActualizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
            $arrBtnDesautorizar['disable'] = false;
        }
        if ($arContratos->getEstadoAprobado()) {
            $arrBtnDesautorizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
        }

        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurContratoDetalle::class)->eliminar($arrDetallesSeleccionados, $id);
                $em->getRepository(TurContrato::class)->liquidar($id);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurContratoDetalle::class)->actualizarDetalles($arrControles, $form, $arContratos);
            }
            return $this->redirect($this->generateUrl('turno_movimiento_comercial_contrato_detalle', ['id' => $id]));
        }
        $arContratoDetalles = $paginator->paginate($em->getRepository(TurContratoDetalle::class)->lista($id), $request->query->getInt('page', 1), 10);
        return $this->render('turno/movimiento/comercial/contrato/detalle.html.twig', array(
            'form' => $form->createView(),
            'arContratoDetalles' => $arContratoDetalles,
            'arContratos' => $arContratos
        ));
    }

    /**
     * @param Request $request
     * @param $codigoContrato
     * @param int $codigoContratoDetalle
     * @return Response
     * @Route("/turno/movimiento/comercial/contrato/detalle/nuevo/{codigoContrato}/{id}", name="turno_movimiento_comercial_contrato_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoContrato, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arContratoDetalle = new TurContratoDetalle();
        $arContratos = $em->getRepository(TurContrato::class)->find($codigoContrato);
        if ($id != '0') {
            $arContratoDetalle = $em->getRepository(TurContratoDetalle::class)->find($id);
        }
        $form = $this->createForm(ContratoDetalleType::class, $arContratoDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arContratoDetalle->setContratoRel($arContratos);
                $em->persist($arContratoDetalle);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/movimiento/comercial/contrato/detalleNuevo.html.twig', [
            'arContratos' => $arContratos,
            'form' => $form->createView()
        ]);
    }
}

