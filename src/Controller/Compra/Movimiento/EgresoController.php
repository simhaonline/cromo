<?php

namespace App\Controller\Compra\Movimiento;

use App\Controller\BaseController;
use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoDetalle;
use App\Entity\Compra\ComProveedor;
use App\Form\Type\Compra\EgresoType;
use App\Formato\Compra\Egreso;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class EgresoController extends BaseController
{
    protected $clase = ComEgreso::class;
    protected $claseNombre = "ComEgreso";
    protected $modulo = "Compra";
    protected $funcion = "Movimiento";
    protected $grupo = "Egreso";
    protected $nombre = "Egreso";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/compra/movimiento/egreso/egreso/lista", name="compra_movimiento_egreso_egreso_lista")
     */
    public function lista(Request $request)
    {
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = BaseController::botoneraLista();
        $formBotonera->handleRequest($request);
        if ($formBotonera->isSubmitted() && $formBotonera->isValid()) {
            if ($formBotonera->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository($this->clase)->parametrosExcel(), "Egreso");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(ComEgreso::class)->eliminar($arrSeleccionados);
            }
        }
        return $this->render('compra/movimiento/Egreso/lista.html.twig', [
            'arrDatosLista' => $this->getDatosLista(),
            'formBotonera' => $formBotonera->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/compra/movimiento/egreso/egreso/nuevo/{id}", name="compra_movimiento_egreso_egreso_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = new ComEgreso();
        if ($id != 0) {
            $arEgreso = $em->getRepository(ComEgreso::class)->find($id);
            if (!$arEgreso) {
                return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_lista'));
            }
        }
        $form = $this->createForm(EgresoType::class, $arEgreso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoProveedor = $request->request->get('txtCodigoProveedor');
                if ($txtCodigoProveedor != '') {
                    $arProveedor = $em->getRepository(ComProveedor::class)->find($txtCodigoProveedor);
                    if ($arProveedor) {
                        $arEgreso->setProveedorRel($arProveedor);
                        if ($id == 0) {
                            $arEgreso->setFecha(new \DateTime('now'));
                        }
                        $em->persist($arEgreso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $arEgreso->getCodigoEgresoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('compra/movimiento/Egreso/nuevo.html.twig', [
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/compra/movimiento/egreso/egreso/detalle/{id}", name="compra_movimiento_egreso_egreso_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = $em->getRepository(ComEgreso::class)->find($id);
        $paginator = $this->get('knp_paginator');
        $form = Estandares::botonera($arEgreso->getEstadoAutorizado(), $arEgreso->getEstadoAprobado(), $arEgreso->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arEgreso->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
        }
        $form
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(ComEgreso::class)->autorizar($arEgreso);
                return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arEgreso->getEstadoAutorizado() == 1 && $arEgreso->getEstadoImpreso() == 0) {
                    $em->getRepository(ComEgreso::class)->desAutorizar($arEgreso);
                    return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El egreso debe estar autorizado y no puede estar impreso");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(ComEgreso::class)->aprobar($arEgreso);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Egreso();
                $formato->Generar($em, $id);
                $arEgreso->setEstadoImpreso(1);
                $em->persist($arEgreso);
                $em->flush();
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(ComEgreso::class)->anular($arEgreso);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(ComEgresoDetalle::class)->actualizar($arrControles, $id);
                return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(ComEgresoDetalle::class)->eliminar($arEgreso, $arrDetallesSeleccionados);
            }
            return $this->redirect($this->generateUrl('compra_movimiento_egreso_egreso_detalle', ['id' => $id]));
        }
        $arEgresoDetalles = $paginator->paginate($em->getRepository(ComEgresoDetalle::class)->lista($arEgreso->getCodigoEgresoPk()), $request->query->getInt('page', 1), 30);
        return $this->render('compra/movimiento/Egreso/detalle.html.twig', [
            'arEgresoDetalles' => $arEgresoDetalles,
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/compra/movimiento/egreso/egreso/detalle/nuevo/{id}", name="compra_movimiento_egreso_egreso_detalle_nuevo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arEgreso = $em->getRepository(ComEgreso::class)->find($id);
        $codigoProveedor = $arEgreso->getProveedorRel()->getCodigoProveedorPk();
        $session->set('filtroComCodigoProveedor', $codigoProveedor);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoCuentaPagar', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => $session->get('')])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroComCuentaPagarCodigo', $form->get('txtCodigoItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrConceptos = $request->request->get('cuentaPagarValor');
                if ($arrConceptos) {
                    foreach ($arrConceptos as $codigoCuentaPagar => $valor) {
                        if ($valor != '' && $valor != 0) {
                            $arCuentaPagar = $em->getRepository(ComCuentaPagar::class)->find($codigoCuentaPagar);
                            $arEgresoDetalle = new ComEgresoDetalle();
                            $arEgresoDetalle->setEgresoRel($arEgreso);
                            $arEgresoDetalle->setNumeroCompra($arCuentaPagar->getNumeroDocumento());
                            $arEgresoDetalle->setNumeroDocumentoAplicacion($arCuentaPagar->getNumeroReferencia());
                            $arEgresoDetalle->setCuentaPagarRel($arCuentaPagar);
                            $arEgresoDetalle->setOperacion($arCuentaPagar->getOperacion());
                            $arEgresoDetalle->setVrPago($valor);
                            $em->persist($arEgresoDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arCuentasPagar = $paginator->paginate($em->getRepository(ComCuentaPagar::class)->lista(), $request->query->getInt('page', 1), 10);

        return $this->render('compra/movimiento/Egreso/detalleNuevo.html.twig', [
            'arCuentasPagar' => $arCuentasPagar,
            'form' => $form->createView()
        ]);
    }
}
