<?php


namespace App\Controller\Tesoreria\General;


use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracion;
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
            ->add('banco', ChoiceType::class, ['choices' => ['SELECCIONAR BANCO' => '',
                                                                        'BBVA' => 'bbva',
                                                                        'BANCOLOMBIA SAP' => 'BancolombiaSap',
                                                                        'BANCOLOMBIA PAB' => 'BancolombiaPab',
                                                                        'AD VILLAS'=>'AdVillasInterno',
                                                                        'AD VILLAS AGRUPADO'=>'AdVillasInternoAgrupado',
                                                                        'AD VILLAS OTROS'=>'AvvillasOtros']
            ])
            ->add('secuencia', TextType::class, array('required' => false))
            ->add('fechaTrasmision', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaAplicacion', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $banco = $form->get('banco')->getData();
                $rawDataForm = [
                    'secuencia' => $form->get('secuencia')->getData()??null,
                    'fechaTrasmision' => $form->get('fechaTrasmision')->getData() ? $form->get('fechaTrasmision')->getData() : null,
                    'fechaAplicacion' => $form->get('fechaAplicacion')->getData() ? $form->get('fechaAplicacion')->getData() : null,
                ];
                switch ($banco){
                    case 'bbva':
                        $this->generarArchivoBBVA($arMovimiento);
                        break;
                    case 'BancolombiaSap':
                        $this->generarArchivoBancolombiaSap($arMovimiento, $rawDataForm);
                        break;
                    case 'BancolombiaPab':
                        $this->generarArchivoBancolombiaPab($arMovimiento, $rawDataForm);
                        break;
                    case 'AdVillasInterno':
                        $this->generarArchivoAdVillasInterno($arMovimiento);
                        break;
                    case 'AdVillasInternoAgrupado':
                        $this->generarArchivoAdVillasInternoAgrupedo($arMovimiento);
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
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk'=>$arMovimiento->getCodigoMovimientoPk()]);
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
        $strFechaCreacion =  $rawDataForm['fechaTrasmision']->format('ymd') ;
        $strFechaAplicacion = $rawDataForm['fechaAplicacion']->format('ymd');
        $strNumeroRegistros = $this->RellenarNr(count($arMovimientoDetalles), "0", 6);
        $strValorTotal = $this->RellenarNr($strValorTotal, "0", 24);
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar,          (1) Tipo de cuenta a debitar
        fputs($ar, "1" . $strNitEmpresa .$strNombreEmpresa .$strTipoPagoSecuencia .$strFechaCreacion.$strSecuencia.$strFechaAplicacion .$strNumeroRegistros .$strValorTotal .$arMovimiento->getCuentaRel()->getCuenta() .$arMovimiento->getCuentaRel()->getTipo() . "\n");
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
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->findBy(['codigoMovimientoFk'=>$arMovimiento->getCodigoMovimientoPk()]);
        // Encabezado
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNit()), "0", 15);
        $strNombreEmpresa = $this->RellenarNr(utf8_decode(substr($arConfiguracionGeneral->getNombre(), 0, 16)), 0, 16);
        $strTipoPagoSecuencia = "225          ";
        $strSecuencia = $rawDataForm['secuencia']. "1";
        $strFechaCreacion =  $rawDataForm['fechaTrasmision']->format('Ymd') ;
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
     *
     * @param $arMovimiento TesMovimiento
     */
    private function generarArchivoAdVillas($arMovimiento){

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