<?php


namespace App\Controller\Tesoreria\General;


use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArchivoPlanoController extends AbstractController
{
    /**
     * @Route("/tesoreria/general/ArchivoPlano/{id}", name="tesoreria_general_archivoPlano")
     */
    public function archivoPlano(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimiento = $em->getRepository(TesMovimiento::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('banco', ChoiceType::class, ['choices' => [
                'SELECCIONAR BANCO' => '',
                'BBVA' => 'bbva',
                'BANCOLOMBIA SAP' => 'BancolombiaSap',
                'BANCOLOMBIA PAB' => 'BancolombiaPab',
                'AV VILLAS INTERNO' => 'AvVillasInterno',
                'AV VILLAS INTERNO OTROS' => 'AvVillasInternoOtros',
                'AV VILLAS INTERNO AGRUPADO' => 'AvVillasInternoAgrupado',
                'AV VILLAS OTROS' => 'AvvillasOtrosAgrupado',
                'BANCO AGRARIO' => 'BancoAgrario',
                'BANCO DE BOGOTA' => 'BancoBogota',
                'BANCO DE BOGOTA 2' => 'BancoBogota2']
            ])
            ->add('secuencia', TextType::class, array('required' => false))
            ->add('fechaTrasmision', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaAplicacion', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $banco = $form->get('banco')->getData();
                $rawDataForm = [
                    'secuencia' => $form->get('secuencia')->getData() ?? null,
                    'fechaTrasmision' => $form->get('fechaTrasmision')->getData() ? $form->get('fechaTrasmision')->getData() : null,
                    'fechaAplicacion' => $form->get('fechaAplicacion')->getData() ? $form->get('fechaAplicacion')->getData() : null,
                ];
                switch ($banco) {
                    case 'bbva':
                        $this->generarArchivoBBVA($arMovimiento);
                        break;
                    case 'BancolombiaSap':
                        $this->generarArchivoBancolombiaSap($arMovimiento, $rawDataForm);
                        break;
                    case 'BancolombiaPab':
                        $this->generarArchivoBancolombiaPab($arMovimiento, $rawDataForm);
                        break;
                    case 'AvVillasInterno':
                        $this->generarArchivoAvvillasInterno($arMovimiento, $rawDataForm);
                        break;
                    case 'AvVillasInternoOtros':
                        $this->generarArchivoAvVillasInternoOtros($arMovimiento, $rawDataForm);
                        break;
                    case 'AvVillasInternoAgrupado':
                        $this->generarArchivoAvvillasInternoAgrupado($arMovimiento, $rawDataForm);
                        break;
                    case 'AvvillasOtrosAgrupado':
                        $this->generarArchivoAvvillasOtrosAgrupado($arMovimiento, $rawDataForm);
                        break;
                    case 'BancoAgrario':
                        $this->generarArchivoBancoAgrario($arMovimiento, $rawDataForm);
                        break;
                    case 'BancoBogota':
                        $this->generarArchivoBogota($arMovimiento, $rawDataForm);
                        break;
                    case 'BancoBogota2':
                        $this->generarArchivoBogota2($arMovimiento, $rawDataForm);
                        break;
                    default:
                        Mensajes::error("seleccionar banco");
                }
            }
        }
        return $this->render('tesoreria/archivoPlano/archivoPlano.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $arMovimiento TesMovimiento
     * @throws \Exception
     */
    private function generarArchivoBBVA($arMovimiento)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $arRhuConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $dateNow = new \DateTime('now');
        $dateNow = $dateNow->format('YmdHis');
        $strNombreArchivo = "pagoBBVA{$arMovimiento->getNumero()}_{$dateNow}.txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $archivo = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $strValorTotal += round($arMovimientoDetalle->getVrPago());
        }
        //Inicio cuerpo
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getVrPago() > 0) {
                $varTipoDocumento = $arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getCodigoIdentificacionFk();
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
                fputs($archivo, $this->RellenarNr($arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getNumeroIdentificacion(), "0", 15));

                fputs($archivo, '01');

                //Codigo general del banco o codigo interface
                fputs($archivo, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoInterface(), "0", 4));

                //Numero de cuenta del empleado y se valida si al cuenta es de BBVA o pertenece a un banco diferente
                if ($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoInterface() != 13) {
                    fputs($archivo, '0000000000000000');
                    switch ($arMovimiento->getTerceroRel()->getCodigoCuentaTipoFk()) {
                        case 'S':
                            $tipoCuenta = '02';
                            break;
                        case 'D':
                            $tipoCuenta = '01';
                            break;
                    }
//                    fputs($archivo, $this->RellenarNr2($tipoCuenta . $arEgresoDetalle->getCuentaPagarRel()->getTerceroRel()->getCuenta(), ' ', 19, 'D'));
                    fputs($archivo, $this->RellenarNr2($tipoCuenta . $arMovimientoDetalle->getCuentaPagarRel()->getCuenta(), ' ', 19, 'D'));
                } else {
                    if ($arRhuConfiguracion->getConcatenarOfinaCuentaBbva()) {
                        $oficina = substr($arMovimientoDetalle->getCuentaPagarRel()->getCuenta(), 0, 3);
                        $cuenta = substr($arMovimientoDetalle->getCuentaPagarRel()->getCuenta(), 3);
                        $strRellenar = "";
                        if ($arMovimiento->getTerceroRel()->getCodigoCuentaTipoFk() == "S") {
                            $strRellenar = '000200';
                        } elseif ($arMovimiento->getTerceroRel()->getCodigoCuentaTipoFk() == "D") {
                            $strRellenar = '000100';
                        }
                        fputs($archivo, $this->RellenarNr2('0' . $oficina . '' . $strRellenar . '' . $cuenta, ' ', 16, 'D'));
                    } else {
                        fputs($archivo, $this->RellenarNr2($arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getCuenta(), ' ', 16, 'D'));
                    }
                    fputs($archivo, '0000000000000000000');
                }

                //Valor entero del pago
                fputs($archivo, $this->RellenarNr($arMovimientoDetalle->getVrPago(), '0', 13));

                //Valor decimal del pago
                fputs($archivo, $this->RellenarNr('0', '0', 2));

                //Fecha limite de pago, no aplica
                fputs($archivo, '000000000000');

                //Nombre del empleado
                fputs($archivo, $this->RellenarNr2(substr(utf8_decode($arMovimientoDetalle->getCuentaPagarRel()->getTerceroRel()->getNombreCorto()), 0, 36), " ", 36, 'D'));

                //Direccion del empleado
                fputs($archivo, $this->RellenarNr2('MEDELLIN', " ", 36, 'D'));

                //2da direccion del empleado
                fputs($archivo, $this->RellenarNr2(" ", " ", 36, 'D'));

                //Email del empleado
                fputs($archivo, $this->RellenarNr2("", " ", 48, 'D'));

                //Concepto del pago
                fputs($archivo, $this->RellenarNr2("NOMINA", " ", 40, 'D'));
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

    /**
     * @param $arMovimiento TesMovimiento
     */
    private function generarArchivoBancolombiaSap($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        $strNombreArchivo = "pagoSap" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = $arMovimiento->getVrTotalNeto();

        // Encabezado
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNit()), "0", 10);
        $strNombreEmpresa = $this->RellenarNr(utf8_decode(substr($arConfiguracionGeneral->getNombre(), 0, 16)), 0, 16);
        $strTipoPagoSecuencia = "225PAGONOMINA";
        $strSecuencia = $rawDataForm['secuencia'];
        $strFechaCreacion = $rawDataForm['fechaTrasmision']->format('ymd');
        $strFechaAplicacion = $rawDataForm['fechaAplicacion']->format('ymd');
        $strNumeroRegistros = $this->RellenarNr(count($arMovimientoDetalles), "0", 6);
        $strValorTotal = $this->RellenarNr($strValorTotal, "0", 24);
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar,          (1) Tipo de cuenta a debitar
        fputs($ar, "1" . $strNitEmpresa . $strNombreEmpresa . $strTipoPagoSecuencia . $strFechaCreacion . $strSecuencia . $strFechaAplicacion . $strNumeroRegistros . $strValorTotal . $arMovimiento->getCuentaRel()->getCuenta() . $arMovimiento->getCuentaRel()->getTipo() . "\n");
//        //Inicio cuerpo
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            fputs($ar, "6"); //(1)Tipo registro
            fputs($ar, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getNumeroIdentificacion(), "0", 15)); //(15) Nit del beneficiario
            fputs($ar, $this->RellenarNr(utf8_decode(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 18)), "0", 18)); // (18) Nombre del beneficiario
            fputs($ar, $arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoBancolombia() != "" ? $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoBancolombia(), "0", 9) : "005600078"); // (9) Banco cuenta del beneficiario
            fputs($ar, $this->RellenarNr($arMovimientoDetalle->getCuenta(), "0", 17)); // (17) Nro cuenta beneficiario
            fputs($ar, "S37"); // (3) Indicador de lugar de pago (S) y tipo de transacción (37)
            $duoValorNetoPagar = round($arMovimientoDetalle->getVrPago()); // (17) Valor transacción
            fputs($ar, ($this->RellenarNr($duoValorNetoPagar, "0", 10)));
            fputs($ar, "                      ");
            fputs($ar, "\n");
        }
        fclose($ar);
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

    /**
     * @param $arMovimiento TesMovimiento
     */
    private function generarArchivoBancolombiaPab($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $strNombreArchivo = "pagoPab" . date('YmdHis') . ".txt";
        $auxZero = "000000";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = $arMovimiento->getVrTotalNeto();
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        // Encabezado
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNit()), "0", 15);
        $strNombreEmpresa = $this->RellenarNr(utf8_decode(substr($arConfiguracionGeneral->getNombre(), 0, 16)), 0, 16);
        $strTipoPagoSecuencia = "225          ";
        $strSecuencia = $rawDataForm['secuencia'] . "1";
        $strFechaCreacion = $rawDataForm['fechaTrasmision']->format('Ymd');
        $strFechaAplicacion = $rawDataForm['fechaAplicacion']->format('Ymd');
        $strNumeroRegistros = $this->RellenarNr(count($arMovimientoDetalles), "0", 6);
        $strValorTotal = "00000000000000000" . $this->RellenarNr($strValorTotal, "0", 15) . "00";
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar, (1) Tipo de cuenta a debitar
        fputs($ar, "1" . $strNitEmpresa . "I" . "               " . $strTipoPagoSecuencia . $strFechaCreacion . $strSecuencia . $strFechaAplicacion . $strNumeroRegistros . $strValorTotal . $arMovimiento->getCuentaRel()->getCuenta() . $arMovimiento->getCuentaRel()->getTipo() . "\n");
        //Inicio cuerpo
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            fputs($ar, "6"); //(1)Tipo registro
            fputs($ar, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getNumeroIdentificacion(), "0", 15)); //(15) Nit del beneficiario
            fputs($ar, $this->RellenarNr(utf8_decode(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 30)), "0", 30)); // (30) Nombre del beneficiario
            fputs($ar, $arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoBancolombia() != "" ? $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoBancolombia(), "0", 9) : "005600078"); // (9) Banco cuenta del beneficiario
            fputs($ar, $this->RellenarNr($arMovimientoDetalle->getCuenta(), "0", 17)); // (17) Nro cuenta beneficiario
            fputs($ar, "337"); // (1) Indicador de lugar de pago (2) y tipo de transacción (37)
            $duoValorNetoPagar = round($arMovimientoDetalle->getVrPago()); // (17) Valor transacción
            fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 15) . "00");
            fputs($ar, $this->RellenarNr2($strFechaAplicacion, ' ', 28, 'D'));
//            fputs($ar, "\n");
            fputs($ar, $this->RellenarNr2($auxZero, ' ', 143, 'D'));
            fputs($ar, "\n");
        }
        fclose($ar);
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

    /**
     * @param $arMovimiento TesMovimiento
     * @var $arMovimiento TesMovimiento
     */
    private function generarArchivoAvvillasInterno($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $strNombreArchivo = "pagoAvvillasInterno" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $strValorTotal += round($arMovimientoDetalle->getVrPago());
        }
        // Encabezado
//        $strNumeroRegistros = $this->RellenarNr($arMovimientoDetalles->getNumeroRegistros(), "0", 6);
        $strNumeroRegistros = $this->RellenarNr(count($arMovimientoDetalles), "0", 6);

        $strValorTotal = ($this->RellenarNr($strValorTotal, "0", 18) . "00");
        $strTipoRegistro = "01";
        $strFechaCreacion = $rawDataForm['fechaTrasmision']->format('Ymd');
        $strHoraCreacion = date('His');
        $oficina = "088";
        $adquiriente = "02";
        $nombreArchivo = "NominaVillas                                      ";
        $relleno = "                                                                                                                        ";
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar, (1) Tipo de cuenta a debitar
        fputs($ar, $strTipoRegistro . $strFechaCreacion . $strHoraCreacion . $oficina . $adquiriente . $nombreArchivo . $relleno . "\n");
        //Inicio cuerpo
        $strSecuencia = 1;
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getVrPago() > 0) {
                fputs($ar, "02"); //(1)Tipo registro
                fputs($ar, "000023"); // codigo transaccion
                fputs($ar, "06"); // tipo producto origen
                fputs($ar, $this->RellenarNr($arMovimientoDetalle->getCuentaRel()->getCuenta(), "0", 16)); // Nro cuenta origen
                fputs($ar, "052"); // entidad destino av villas 052
                fputs($ar, "01"); // tipo producto destino
                fputs($ar, $this->RellenarNr($arMovimientoDetalle->getCuenta(), "0", 16)); // Nro cuenta destino
                fputs($ar, ($this->RellenarNr($strSecuencia, "0", 9))); //secuencia
                $duoValorNetoPagar = round($arMovimientoDetalle->getVrPago()); // (17) Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "0000000000000000"); // numero factura duda
                fputs($ar, "0000000000000000"); // referencia 1
                fputs($ar, "0000000000000000"); // referencia 2
                fputs($ar, $this->RellenarNr2(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 30), " ", 30, "D")); // (30) Nombre del beneficiario
                fputs($ar, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getNumeroIdentificacion(), "0", 11)); // (30) Numero identificacion
                fputs($ar, "000000"); // numero de autorizacion
                fputs($ar, "00"); // codigo respuesta
                fputs($ar, "000000000000000000"); // retencion contigente
                fputs($ar, "00"); // relleno
                fputs($ar, "\n");
                $strSecuencia++;
            }
        }
        fputs($ar, "03" . $this->RellenarNr(($strSecuencia - 1), "0", 9) . $strValorTotal . $this->RellenarNr(" ", " ", 15) . $this->RellenarNr(" ", " ", 145) . "\n");
        fclose($ar);
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

    /**
     * @param $arMovimiento TesMovimiento
     * @var $arMovimiento TesMovimiento
     */
    private function generarArchivoAvVillasInternoOtros($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $strNombreArchivo = "pagoAvvillasOtros" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        ob_clean();
        $strValorTotal = 0;
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $strValorTotal += round($arMovimientoDetalle->getVrPago());
        }
        // Encabezado
        $strTipoRegistro = "1";
        $cuentaOrigen = $this->RellenarNr2($arMovimiento->getCuentaRel()->getCuenta(), " ", 17, "D");
        $tipoCuentaOrigen = "0"; //duda
        $codigoProducto = "PP"; //duda
        $strFechaCreacion = $rawDataForm['fechaTrasmision']->format('Ymd');
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNit()), "0", 15);
        $tipoId = "03"; //duda
        $strNombreEmpresa = $this->RellenarNr2(utf8_decode(substr($arConfiguracionGeneral->getNombre(), 0, 16)), " ", 16, "D");
        $codPlazaOrigen = "0002"; //duda
        $tipoRegistros = "PPD"; //duda
        $strSecuencia = "000000";
        $canal = "4"; //duda
        //$strValorTotal = $this->RellenarNr($strValorTotal, "0", 18);
        $strValorTotal = ($this->RellenarNr($strValorTotal, "0", 16) . "00");
        //Fin encabezado
        fputs($ar, $strTipoRegistro . $cuentaOrigen . $tipoCuentaOrigen . $codigoProducto . $strFechaCreacion . $strNitEmpresa . $tipoId . $strNombreEmpresa . $codPlazaOrigen . $tipoRegistros . $strSecuencia . $canal . "\n");
        //Inicio cuerpo
        $cont = 0;
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $codigoBanco = str_pad($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoInterface(), 4, '0', STR_PAD_LEFT);
            if ($arMovimientoDetalle->getVrPago() > 0) {
                $cont++;
                fputs($ar, "2"); //(1)Tipo registro
                fputs($ar, "32"); // codigo transaccion DUDA
                fputs($ar, $codigoBanco); // codigo banco des
                fputs($ar, "0002"); // codigo plaza des
                fputs($ar, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getNumeroIdentificacion(), " ", 15)); //(15) Nit del beneficiario
                fputs($ar, "01"); //(15) tipo identificacion
                fputs($ar, $this->RellenarNr2($arMovimientoDetalle->getCuenta(), " ", 17, "D")); // Nro cuenta destino
                fputs($ar, "1"); // tipo cuenta destino
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 22)), " ", 22, "D")); // (22) Nombre del beneficiario
                fputs($ar, "0"); // duda addendas
                $duoValorNetoPagar = round($arMovimientoDetalle->getVrPago()); // (17) Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "1"); // valida identificacion
                fputs($ar, "\n");
            }
        }
        //Fin cuerpo
        //Pie de pagina
        fputs($ar, "4" . $this->RellenarNr($cont, "0", 8) . $strValorTotal . "\n");
        fclose($ar);
        $em->flush();

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

    /**
     * @param $arMovimiento TesMovimiento
     * @var $arMovimiento TesMovimiento
     */
    private function generarArchivoAvvillasInternoAgrupado($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $strNombreArchivo = "pagoAvvillasInterno" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->listaImprimirAgrupado($arMovimiento->getCodigoMovimientoPk());
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $strValorTotal += round($arMovimientoDetalle['vrPago']);
        }
        // Encabezado;
//        $strNumeroRegistros = $this->RellenarNr($arMovimiento->getNumeroRegistros(), "0", 6);
        $strNumeroRegistros = $this->RellenarNr(count($arMovimientoDetalles), "0", 6);
        $strValorTotal = ($this->RellenarNr($strValorTotal, "0", 18) . "00");
        $strTipoRegistro = "01";
        $strFechaCreacion = $rawDataForm['fechaTrasmision']->format('Ymd');
        $strHoraCreacion = date('His');
        $oficina = "088";
        $adquiriente = "02";
        $nombreArchivo = "NominaVillas                                      ";
        $relleno = "                                                                                                                        ";
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar, (1) Tipo de cuenta a debitar
        fputs($ar, $strTipoRegistro . $strFechaCreacion . $strHoraCreacion . $oficina . $adquiriente . $nombreArchivo . $relleno . "\n");
        //Inicio cuerpo
        $strSecuencia = 1;
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if ($arMovimientoDetalle['vrPago'] > 0) {
                fputs($ar, "02"); //(1)Tipo registro
                fputs($ar, "000023"); // codigo transaccion
                fputs($ar, "06"); // tipo producto origen
                fputs($ar, $this->RellenarNr($arMovimiento->getCuentaRel()->getCuenta(), "0", 16)); // Nro cuenta origen
                fputs($ar, "052"); // entidad destino av villas 052
                fputs($ar, "01"); // tipo producto destino
                fputs($ar, $this->RellenarNr($arMovimientoDetalle['cuenta'], "0", 16)); // Nro cuenta destino
                fputs($ar, ($this->RellenarNr($strSecuencia, "0", 9))); //secuencia
                $duoValorNetoPagar = round($arMovimientoDetalle['vrPago']); // (17) Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "0000000000000000"); // numero factura duda
                fputs($ar, "0000000000000000"); // referencia 1
                fputs($ar, "0000000000000000"); // referencia 2
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arMovimientoDetalle['nombreCorto'], 0, 30)), " ", 30, "D")); // (30) Nombre del beneficiario
                fputs($ar, $this->RellenarNr($arMovimientoDetalle['numeroIdentificacion'], "0", 11)); // (30) Numero identificacion
                fputs($ar, "000000"); // numero de autorizacion
                fputs($ar, "00"); // codigo respuesta
                fputs($ar, "000000000000000000"); // retencion contigente
                fputs($ar, "00"); // relleno
                fputs($ar, "\n");
                $strSecuencia++;
            }
        }
        fputs($ar, "03" . $this->RellenarNr(($strSecuencia - 1), "0", 9) . $strValorTotal . "\n");
        fclose($ar);
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

    /**
     * @param $arPagoBanco RhuPagoBanco
     */
    private function generarArchivoAvvillasOtrosAgrupado($arMovimiento, $rawDataForm)
    {

        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $strNombreArchivo = "pagoAvvillasOtros" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        ob_clean();
        $strValorTotal = 0;
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->listaImprimirAgrupado($arMovimiento->getCodigoMovimientoPk());
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $strValorTotal += round($arMovimientoDetalle['vrPago']);
        }
        // Encabezado
        $strTipoRegistro = "1";
        $cuentaOrigen = $this->RellenarNr2($arMovimiento->getCuentaRel()->getCuenta(), " ", 17, "D");
        $tipoCuentaOrigen = "0"; //duda
        $codigoProducto = "PP"; //duda
        $strFechaCreacion = $rawDataForm['fechaTrasmision']->format('Ymd');
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNit()), "0", 15);
        $tipoId = "03"; //duda
        $strNombreEmpresa = $this->RellenarNr2(utf8_decode(substr($arConfiguracionGeneral->getNombre(), 0, 16)), " ", 16, "D");
        $codPlazaOrigen = "0002"; //duda
        $tipoRegistros = "PPD"; //duda
        $strSecuencia = "000000";
        $canal = "4"; //duda
        //$strValorTotal = $this->RellenarNr($strValorTotal, "0", 18);
        $strValorTotal = ($this->RellenarNr($strValorTotal, "0", 16) . "00");
        //Fin encabezado
        fputs($ar, $strTipoRegistro . $cuentaOrigen . $tipoCuentaOrigen . $codigoProducto . $strFechaCreacion . $strNitEmpresa . $tipoId . $strNombreEmpresa . $codPlazaOrigen . $tipoRegistros . $strSecuencia . $canal . "\n");
        //Inicio cuerpo
        $cont = 0;
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $codigoBanco = str_pad($arMovimientoDetalle['codigoInterface'], 4, '0', STR_PAD_LEFT);
            if ($arMovimientoDetalle['vrPago'] > 0) {
                $cont++;
                fputs($ar, "2"); //(1)Tipo registro
                fputs($ar, "32"); // codigo transaccion DUDA
                fputs($ar, $codigoBanco); // codigo banco des
                fputs($ar, "0002"); // codigo plaza des
                fputs($ar, $this->RellenarNr($arMovimientoDetalle['numeroIdentificacion'], " ", 15)); //(15) Nit del beneficiario
                fputs($ar, "01"); //(15) tipo identificacion
                fputs($ar, $this->RellenarNr2($arMovimientoDetalle['cuenta'], " ", 17, "D")); // Nro cuenta destino
                fputs($ar, "1"); // tipo cuenta destino
                fputs($ar, $this->RellenarNr(utf8_decode(substr($arMovimientoDetalle['nombreCorto'], 0, 22)), " ", 22)); // (22) Nombre del beneficiario
                fputs($ar, "0"); // duda addendas
                $duoValorNetoPagar = round($arMovimientoDetalle['vrPago']); // (17) Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "1"); // valida identificacion
                fputs($ar, "\n");
            }
        }
        //Fin cuerpo
        //Pie de pagina
        fputs($ar, "4" . $this->RellenarNr($cont, "0", 8) . $strValorTotal . "\n");
        fclose($ar);
        $em->flush();

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

    /**
     * Funcion que imprime bancolombia sap agrupado por empleado
     * @var $arMovimiento TesMovimiento
     * @var $arPagoBancoDetalle RhuPagoBancoDetalle
     */
    private function generarArchivoBancoAgrario($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $strNombreArchivo = "pagoBancoAgrario" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        //Inicio cuerpo
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()]);
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            //Codigo del banco de destino (4)
            fputs($ar, $this->RellenarNr('40', 0, 4)); //Agrario
//            fputs($ar,'23'); //Occidente
            fputs($ar, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getNumeroIdentificacion(), 0, 15));
            fputs($ar, '1');
            fputs($ar, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getCuenta(), 0, 17));
            fputs($ar, $arMovimientoDetalle->getTerceroRel()->codigoCuentaTipoFk() == 'S' ? '4' : '3');
            fputs($ar, $this->RellenarNr2($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), ' ', 30, 'R'));
            fputs($ar, $this->RellenarNr($arMovimientoDetalle->getVrPago() . '.00', 0, 15));
            fputs($ar, $this->RellenarNr2('TRANSACCION BANCO AGRARIO', 0, 42, 'R') . "\n");
        }
        fclose($ar);
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

    /**
     * @param $arMovimiento TesMovimiento
     * @throws \Exception
     */
    private function generarArchivoBogota($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $dateNow = new \DateTime('now');
        $dateNow = $dateNow->format('YmdHis');
        $strNombreArchivo = "pagoBancoBogota{$arMovimiento->getNumero()}_{$dateNow}.txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        ob_clean();
        $strValorTotal = 0;
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            $strValorTotal += round($arMovimientoDetalle->getVrPago());
        }
        // Encabezado
        $strTipoRegistro = "1";
        $strFechaAplicacion = $rawDataForm['fechaAplicacion']->format('ymd');
        $espacios24 = "000000000000000000000000";
        $srtTipoCuenta = $arMovimiento->getCuentaRel()->getTipo(); // tipo cuenta
        if ($srtTipoCuenta == 'D') {
            $srtTipoCuenta = 1;
        } else {
            $srtTipoCuenta = 2;
        }
        $espacios6 = "000000";
        $cuentaOrigen = $this->RellenarNr($arMovimiento->getCuentaRel()->getCuenta(), "0", 11); // cuenta
        $strNombreEmpresa = $this->RellenarNr2(utf8_decode(substr($arConfiguracionGeneral->getNombre(), 0, 40)), " ", 40, "D");
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNit() . $arConfiguracionGeneral->getDigitoVerificacion()), "0", 11); //nit
        $strTipoMovimiento = "001";
        $srtCodigoCiudadCuenta = "0001";
        $strFechaProceso = $rawDataForm['fechaTrasmision']->format('Ymd');
        $strCodigoOficina = "137";
        $strTipoIdentificacion = "N";
        $strEspacios129 = "                                                                                                                                 ";
        //Fin encabezado
        fputs($ar, $strTipoRegistro . $strFechaAplicacion . $espacios24 . $srtTipoCuenta . $espacios6 . $cuentaOrigen . $strNombreEmpresa . $strNitEmpresa . $strTipoMovimiento . $srtCodigoCiudadCuenta . $strFechaProceso . $strCodigoOficina . $strTipoIdentificacion . $strEspacios129 . "\n");
        //Inicio cuerpo
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getVrPago() > 0) {
                fputs($ar, "2"); //(1)Tipo registro
                $tipoIdentificacion = $arMovimientoDetalle->getTerceroRel()->getIdentificacionRel()->getCodigoInterface();
                if ($tipoIdentificacion == 13) {
                    $tipo = "C";
                }
                if ($tipoIdentificacion == 31) {
                    $tipo = "N";
                }
                if ($tipoIdentificacion == 22) {
                    $tipo = "E";
                }
                fputs($ar, $tipo); //(1)Tipo identificacion
                fputs($ar, $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getNumeroIdentificacion(), "0", 11)); //(15) identificacion del beneficiario
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 40)), " ", 40, "D")); // nombre beneficiario
                fputs($ar, "0");
                $tipocuenta = $arMovimientoDetalle->getTerceroRel()->getCodigoCuentaTipoFk();
                if ($tipocuenta == "S") {
                    $tipocuenta = 2;
                }
                if ($tipocuenta == "D") {
                    $tipocuenta = 1;
                }
                fputs($ar, $tipocuenta);
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arMovimientoDetalle->getCuenta(), 0, 17)), " ", 17, "D")); // Nro cuenta destino
                $duoValorNetoPagar = round($arMovimientoDetalle->getVrPago()); // Valor transacción
                fputs($ar, $this->RellenarNr($duoValorNetoPagar, "0", 16) . "00");
                fputs($ar, "A"); // forma de pago abono
                fputs($ar, "000");
                fputs($ar, "001"); // codigo compensacion del banco
                fputs($ar, "0001"); // codigo ciudad del banco
                fputs($ar, "ATEMPI   "); // informacion addenda
                fputs($ar, " ");
//                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arPagoBancoDetalle->getPagoBancoRel()->getDescripcion(), 0, 70)), " ", 70, "D")); // nombre beneficiario
                fputs($ar, $this->RellenarNr2(utf8_decode(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 70)), " ", 70, "D")); // nombre beneficiario
                fputs($ar, "0");
                fputs($ar, "0000000000"); //numero de factura o comprobante
                fputs($ar, "N");  // envio de la informacion
                fputs($ar, "        "); // espacios en blanco
                fputs($ar, "000000000000000000"); //valor libranza
                fputs($ar, "           "); // numero libranza
                fputs($ar, "           "); // espacios
                fputs($ar, "N");  // indicador envio de mensaje
                fputs($ar, "        "); // espacios
                fputs($ar, "\n");
            }
        }
        //Fin cuerpo
        //Pie de pagina
        fclose($ar);
        $em->flush();

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

    private function generarArchivoBogota2($arMovimiento, $rawDataForm)
    {
        $em = $this->getDoctrine()->getManager();
        $objPHPExcel = new Spreadsheet();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $dateNow = new \DateTime('now');
        $dateNow = $dateNow->format('YmdHis');
        $strNombreArchivo = "pagoBancoBogota{$arMovimiento->getNumero()}_{$dateNow}.txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        ob_clean();
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(array('codigoMovimientoFk' => $arMovimiento->getCodigoMovimientoPk()));
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'V'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'TIPO IDENTIFICACION BENEFICIARIO')
            ->setCellValue('B1', 'NOMBRE DEL BENEFICIARION')
            ->setCellValue('C1', 'NUMERO DE IDENTIFICACION BENEFICIARION')
            ->setCellValue('D1', 'TIPO DE CUENTA DESTINO')
            ->setCellValue('E1', 'NUMERO CUENTA DESTINO')
            ->setCellValue('F1', 'VALOR A PAGAR')
            ->setCellValue('G1', 'CODIGO ENTIDAD FINANCIERA DESTINO')
            ->setCellValue('H1', 'REFERENCIA /FACTURA')
            ->setCellValue('I1', 'CORREO ELECTRONICO O E-MAIL')
            ->setCellValue('J1', 'MENSAJE A ENVIAR');
        $i = 2;
        foreach ($arMovimientoDetalles AS $arMovimientoDetalle) {
            if ($arMovimientoDetalle->getVrPago() > 0) {
                $tipoIdentificacion = $arMovimientoDetalle->getTerceroRel()->getIdentificacionRel()->getCodigoInterface();
                if ($tipoIdentificacion == 13) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, "C");
                }
                if ($tipoIdentificacion == 31) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, "N");
                }
                if ($tipoIdentificacion == 22) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, "E");
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B' . $i, utf8_decode(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 40)))
                    ->setCellValue('C' . $i, $arMovimientoDetalle->getTerceroRel()->getNumeroIdentificacion());
                $tipocuenta = $arMovimientoDetalle->getTerceroRel()->getCodigoCuentaTipoFk();
                if ($tipocuenta == "S") {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, "2");
                }
                if ($tipocuenta == "D") {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, "1");
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, utf8_decode(substr($arMovimientoDetalle->getCuenta(), 0, 17)))
                    ->setCellValue('F' . $i, round($arMovimientoDetalle->getVrPago()))
                    ->setCellValue('G' . $i, "1")
                    ->setCellValue('H' . $i, "")
                    ->setCellValue('I' . $i, $arMovimientoDetalle->getTerceroRel()->getEmail())
//                    ->setCellValue('J' . $i, utf8_decode(substr($arMovimientoDetalle->getPagoBancoRel()->getDescripcion(), 0, 70)));
                    ->setCellValue('J' . $i, utf8_decode(substr($arMovimientoDetalle->getTerceroRel()->getNombreCorto(), 0, 70)));
                $i++;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle("Pagos BcoBta");
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pagoBancoBogota' . $arMovimiento->getNumero() . '_' . $dateNow . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
        $writer->save('php://output');
        exit;
    }

    //Rellenar numeros
    public static function RellenarNr($Nro, $Str, $NroCr): string
    {
        $Longitud = strlen($Nro);

        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++)
            $Nro = $Str . $Nro;

        return (string)$Nro;
    }

    public static function RellenarNr2($Nro, $Str, $NroCr, $strPosicion): string
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