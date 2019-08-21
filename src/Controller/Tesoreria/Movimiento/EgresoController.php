<?php

namespace App\Controller\Tesoreria\Movimiento;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenBanco;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesEgreso;
use App\Entity\Tesoreria\TesEgresoDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Tesoreria\EgresoType;
use App\Formato\Tesoreria\Egreso;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class EgresoController extends BaseController
{
    protected $clase = TesEgreso::class;
    protected $claseNombre = "TesEgreso";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "Egreso";
    protected $nombre = "Egreso";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/movimiento/egreso/egreso/lista", name="tesoreria_movimiento_egreso_egreso_lista")
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
                General::get()->setExportar($em->createQuery($datos['queryBuilder'])->execute(), "Egresos");
            }
            if ($formBotonera->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TesEgreso::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_lista'));
            }
        }
        return $this->render('tesoreria/movimiento/egreso/egreso/lista.html.twig', [
            'arrDatosLista' => $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/tesoreria/movimiento/egreso/egreso/nuevo/{id}", name="tesoreria_movimiento_egreso_egreso_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = new TesEgreso();
        if ($id != 0) {
            $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
            if (!$arEgreso) {
                return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_lista'));
            }
        }
        $form = $this->createForm(EgresoType::class, $arEgreso);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(TesTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arEgreso->setTerceroRel($arTercero);
                        if ($id == 0) {
                            $arEgreso->setFecha(new \DateTime('now'));
                        }
                        $em->persist($arEgreso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_detalle', ['id' => $arEgreso->getCodigoEgresoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('tesoreria/movimiento/egreso/egreso/nuevo.html.twig', [
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/tesoreria/movimiento/egreso/egreso/detalle/{id}", name="tesoreria_movimiento_egreso_egreso_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
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
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnArchivoPlanoBbva', SubmitType::class, ['label' => 'Generar archivo bbva']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TesEgresoDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(TesEgreso::class)->autorizar($arEgreso);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arEgreso->getEstadoAutorizado() == 1 && $arEgreso->getEstadoAprobado() == 0) {
                    $em->getRepository(TesEgreso::class)->desAutorizar($arEgreso);
                    return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El egreso debe estar autorizado y no puede estar aprobado");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TesEgreso::class)->aprobar($arEgreso);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Egreso();
                $formato->Generar($em, $id);
                $arEgreso->setEstadoImpreso(1);
                $em->persist($arEgreso);
                $em->flush();
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(TesEgreso::class)->anular($arEgreso);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TesEgresoDetalle::class)->actualizar($arrControles, $id);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TesEgresoDetalle::class)->eliminar($arEgreso, $arrDetallesSeleccionados);
            }
            if ($form->get('btnArchivoPlanoBbva')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $numero = $arEgreso->getNumero();
                $this->generarArchivoBBVA($arEgreso, $numero,$arrDetallesSeleccionados);
            }
            return $this->redirect($this->generateUrl('tesoreria_movimiento_egreso_egreso_detalle', ['id' => $id]));
        }
        $arEgresoDetalles = $paginator->paginate($em->getRepository(TesEgresoDetalle::class)->lista($arEgreso->getCodigoEgresoPk()), $request->query->getInt('page', 1), 500);
        return $this->render('tesoreria/movimiento/egreso/egreso/detalle.html.twig', [
            'arEgresoDetalles' => $arEgresoDetalles,
            'arEgreso' => $arEgreso,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/tesoreria/movimiento/egreso/egreso/detalle/nuevo/{id}", name="tesoreria_movimiento_egreso_egreso_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('cboCuentaPagarTipo', EntityType::class, $em->getRepository(TesCuentaPagarTipo::class)->llenarCombo())
            ->add('cboBanco', EntityType::class, $em->getRepository(GenBanco::class)->llenarCombo())
            ->add('txtCodigoCuentaPagar', TextType::class, ['label' => 'Codigo: ', 'required' => false, 'data' => $session->get('')])
            ->add('txtNumeroReferencia', TextType::class, ['label' => 'Numero referecia: ', 'required' => false, 'data' => $session->get('')])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesFechaDesde') ? date_create($session->get('filtroTesFechaDesde')) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $session->get('filtroTesFechaHasta') ? date_create($session->get('filtroTesFechaHasta')) : null])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arCuentaPagarTipo = $form->get('cboCuentaPagarTipo')->getData();
                if ($arCuentaPagarTipo) {
                    $session->set('filtroTesCuentaPagarTipo', $arCuentaPagarTipo->getCodigoCuentaPagarTipoPk());
                } else {
                    $session->set('filtroTesCuentaPagarTipo', null);
                }
                $arBanco = $form->get('cboBanco')->getData();
                if ($arBanco) {
                    $session->set('filtroGenBanco', $arBanco->getCodigoBancoPk());
                } else {
                    $session->set('filtroGenBanco', null);
                }
                $session->set('filtroTesCuentaPagarCodigoPendiente', $form->get('txtCodigoCuentaPagar')->getData());
                $session->set('filtroTesCuentaPagaNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
                $session->set('filtroTesFechaDesde', $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null);
                $session->set('filtroTesFechaHasta', $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null);
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrCuentasPagar = $request->request->get('ChkSeleccionar');
                if ($arrCuentasPagar) {
                    foreach ($arrCuentasPagar as $codigoCuentaPagar) {
                        /** @var $arCuentaPagar  TesCuentaPagar */
                        $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($codigoCuentaPagar);
                        $arEgreso = $em->getRepository(TesEgreso::class)->find($id);
                        $arEgresoDetalle = new TesEgresoDetalle();
                        $arEgresoDetalle->setEgresoRel($arEgreso);
                        $arEgresoDetalle->setNumero($arCuentaPagar->getNumeroDocumento());
                        $arEgresoDetalle->setCuentaPagarRel($arCuentaPagar);
                        $arEgresoDetalle->setVrPagoAfectar($arCuentaPagar->getVrTotal());
                        $arEgresoDetalle->setUsuario($this->getUser()->getUserName());
                        $em->persist($arEgresoDetalle);

                    }
                    $em->flush();
                    $em->getRepository(TesEgresoDetalle::class)->liquidar($id);
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arCuentasPagar = $paginator->paginate($em->getRepository(TesCuentaPagar::class)->cuentasPagar(), $request->query->getInt('page', 1), 500);
        return $this->render('tesoreria/movimiento/egreso/egreso/detalleNuevo.html.twig', [
            'arCuentasPagar' => $arCuentasPagar,
            'form' => $form->createView()
        ]);
    }


    private function generarArchivoBBVA($arEgreso, $numero, $arrSeleccionados)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $arRhuConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $dateNow = new \DateTime('now');
        $dateNow = $dateNow->format('YmdHis');
        $strNombreArchivo = "pagoBBVA{$numero}_{$dateNow}.txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $archivo = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arEgresoDetalles = $em->getRepository(TesEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
        foreach ($arEgresoDetalles AS $arEgresoDetalle) {
            $strValorTotal += round($arEgresoDetalle->getVrPago());
        }
        if ($arrSeleccionados != null) {
            $strValorTotal = 0;
            $arEgresoDetalles = [];
            foreach ($arrSeleccionados as $codigo) {
                $arEgresoDetalle = $em->getRepository(TesEgresoDetalle::class)->find($codigo);
                $arEgresoDetalles[] = $arEgresoDetalle;
                $strValorTotal += round($arEgresoDetalle->getVrPagoAfectar());
            }
        }
        //Inicio cuerpo
        foreach ($arEgresoDetalles AS $arEgresoDetalle) {
            if ($arEgresoDetalle->getVrPagoAfectar() > 0) {
                $varTipoDocumento = $arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getCodigoIdentificacionFk();
                switch ($varTipoDocumento) {
                    //'01' - Cédula de ciudadanía
                    case 'CC':
                        $strTipoDocumento = '01';
                        break;
                    //'02' - Cédula de extranjería
                    case 'CE':
                        $strTipoDocumento = '02';
                        break;
                    //'03' - N.I.T.
                    case 'NI':
                        $strTipoDocumento = '03';
                        break;
                    //'04' - Tarjeta de Identidad
                    case 'TI':
                        $strTipoDocumento = '04';
                        break;
                    //'05' - Pasaporte
                    case 'PA':
                        $strTipoDocumento = '05';
                        break;
                    // '06' - Sociedad extranjera sin N.I.T. En Colombia
                    case 'TDE':
                        $strTipoDocumento = '06';
                        break;
                }

                //Tipo de identificacion del empleado
                fputs($archivo, $strTipoDocumento);

                //Numero de identificacion del empleado
                fputs($archivo, $this->RellenarNr($arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getNumeroIdentificacion(), "0", 15));

                fputs($archivo, '01');

                //Codigo general del banco o codigo interface
                fputs($archivo, $this->RellenarNr($arEgreso->getTerceroRel()->getBancoRel()->getCodigoInterface(), "0", 4));

                //Numero de cuenta del empleado y se valida si al cuenta es de BBVA o pertenece a un banco diferente
                if ($arEgreso->getTerceroRel()->getBancoRel()->getCodigoInterface() != 13) {
                    fputs($archivo, '0000000000000000');
                    switch ($arEgreso->getTerceroRel()->getCodigoCuentaTipoFk()) {
                        case 'S':
                            $tipoCuenta = '02';
                            break;
                        case 'D':
                            $tipoCuenta = '01';
                            break;
                    }
//                    fputs($archivo, $this->RellenarNr2($tipoCuenta . $arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getCuenta(), ' ', 19, 'D'));
                    fputs($archivo, $this->RellenarNr2($tipoCuenta . $arEgresoDetalle->getCuentaPagarRel()->getCuenta(), ' ', 19, 'D'));
                } else {
                    if ($arRhuConfiguracion->getConcatenarOfinaCuentaBbva()) {
                        $oficina = substr($arEgresoDetalle->getCuentaPagarRel()->getCuenta(), 0, 3);
                        $cuenta = substr($arEgresoDetalle->getCuentaPagarRel()->getCuenta(), 3);
                        $strRellenar = "";
                        if ($$arEgreso->getTerceroRel()->getCodigoCuentaTipoFk() == "S") {
                            $strRellenar = '000200';
                        } elseif ($arEgreso->getTerceroRel()->getCodigoCuentaTipoFk() == "D") {
                            $strRellenar = '000100';
                        }
                        fputs($archivo, $this->RellenarNr2('0' . $oficina . '' . $strRellenar . '' . $cuenta, ' ', 16, 'D'));
                    } else {
                        fputs($archivo, $this->RellenarNr2($arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getCuenta(), ' ', 16, 'D'));
                    }
                    fputs($archivo, '0000000000000000000');
                }

                //Valor entero del pago
                fputs($archivo, $this->RellenarNr($arEgresoDetalle->getVrPagoAfectar(), '0', 13));

                //Valor decimal del pago
                fputs($archivo, $this->RellenarNr('0', '0', 2));

                //Fecha limite de pago, no aplica
                fputs($archivo, '000000000000');

                //Nombre del empleado
                fputs($archivo, $this->RellenarNr2(substr($arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getNombreCorto(), 0, 36), " ", 36, 'D'));

                //Direccion del empleado
                fputs($archivo, $this->RellenarNr2('MEDELLIN', " ", 36, 'D'));

                //2da direccion del empleado
                fputs($archivo, $this->RellenarNr2(" ", " ", 36, 'D'));

                //Email del empleado
                fputs($archivo, $this->RellenarNr2($arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getEmail()??"", " ", 48, 'D'));

                //Concepto del pago
                fputs($archivo, $this->RellenarNr2($arEgreso->getComentarios(), " ", 40, 'D'));
                fputs($archivo, "\n");
            }
        }
        fclose($archivo);
        $em->flush();
        //Fin cuerpo
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;
    }

    //Rellenar numeros
    public static function RellenarNr($Nro, $Str, $NroCr):string
    {
        $Longitud = strlen($Nro);

        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++)
            $Nro = $Str . $Nro;

        return (string)$Nro;
    }

    public static function RellenarNr2($Nro, $Str, $NroCr, $strPosicion):string
    {
        $Nro = utf8_decode($Nro);
        $Longitud = strlen($Nro);
        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++) {
            if ($strPosicion == "I") {
                $Nro = $Str . $Nro;
            } else {
                $Nro = $Nro . $Str;
            }
        }

        return (string)$Nro;
    }

}
