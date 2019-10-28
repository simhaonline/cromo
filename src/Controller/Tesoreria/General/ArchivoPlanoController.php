<?php


namespace App\Controller\Tesoreria\General;


use App\Entity\General\GenConfiguracion;
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
                                                                        'BANCOLOMBIA PAB' => 'BancolombiaPab']
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
                    'fechaTrasmision' => $form->get('fechaTrasmision')->getData() ? $form->get('fechaTrasmision')->getData()->format('ymd') : null,
                    'fechaAplicacion' => $form->get('fechaAplicacion')->getData() ? $form->get('fechaAplicacion')->getData()->format('ymd') : null,
                ];
                switch ($banco){
                    case 'BBVA':
                        break;
                    case 'BancolombiaSap':
                        $this->generarArchivoBancolombiaSap($arMovimiento, $rawDataForm);
                        break;
                    case 'BancolombiaPab':
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
        $strFechaCreacion =  $rawDataForm['fechaTrasmision'] ;
        $strFechaAplicacion = $rawDataForm['fechaAplicacion'];
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
            fputs($ar, $arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoInterface() != "" ? $this->RellenarNr($arMovimientoDetalle->getTerceroRel()->getBancoRel()->getCodigoInterface(), "0", 9) : "005600078"); // (9) Banco cuenta del beneficiario
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

    private function generarArchivoBancolombiaPab($arPagoBanco)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $strNombreArchivo = "pagoPab" . date('YmdHis') . ".txt";
        $auxZero = "000000";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die("Problemas en la creacion del archivo plano");
        $strValorTotal = 0;
        $arPagosBancoDetalle = $em->getRepository( TesMovimientoDetalle::class)->findBy(array('codigoBancoFk' => $arPagoBanco->getCodigoPagoBancoPk()));
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            $strValorTotal += round($arPagoBancoDetalle->getVrPago());
        }
        // Encabezado
        $strNitEmpresa = $this->RellenarNr(utf8_decode($arConfiguracionGeneral->getNitEmpresa()), "0", 15);
        $strNombreEmpresa = $this->RellenarNr(utf8_decode(substr($arConfiguracionGeneral->getNombreEmpresa(), 0, 16)), 0, 16);
        $strTipoPagoSecuencia = "225          ";
        $strSecuencia = $arPagoBanco->getSecuencia() . "1";
        $strFechaCreacion = $arPagoBanco->getFechaTrasmision()->format('Ymd');
        $strFechaAplicacion = $arPagoBanco->getFechaAplicacion()->format('Ymd');
        $strNumeroRegistros = $this->RellenarNr($arPagoBanco->getNumeroRegistros(), "0", 6);
        $strValorTotal = "00000000000000000" . $this->RellenarNr($strValorTotal, "0", 15) . "00";
        //Fin encabezado
        //(1) Tipo de registro, (10) Nit empresa, (225PAGO NOMI) descripcion transacion, (yymmdd) fecha creacion, (yymmdd) fecha aplicacion, (6) Numero de registros, (17) sumatoria de creditos, (11) Cuenta cliente a debitar, (1) Tipo de cuenta a debitar
        fputs($ar, "1" . $strNitEmpresa . "I" . "               " . $strTipoPagoSecuencia . $strFechaCreacion . $strSecuencia . $strFechaAplicacion . $strNumeroRegistros . $strValorTotal . $this->RellenarNr($arPagoBanco->getCuentaRel()->getCuenta(), 0, 11) . $this->RellenarNr2($arPagoBanco->getCuentaRel()->getTipo(), ' ', 150, 'D') . "\n");
        //Inicio cuerpo
        foreach ($arPagosBancoDetalle AS $arPagoBancoDetalle) {
            fputs($ar, "6"); //(1)Tipo registro
            fputs($ar, $this->RellenarNr2($arPagoBancoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), ' ', 15, 'D')); //(15) Nit del beneficiario
            fputs($ar, $this->RellenarNr2(substr($arPagoBancoDetalle->getEmpleadoRel()->getNombre1() . ' ' . $arPagoBancoDetalle->getEmpleadoRel()->getNombre2(), 0, 30), " ", 30, 'D')); // (30) Nombre del beneficiario
            fputs($ar, $arPagoBancoDetalle->getEmpleadoRel()->getBancoRel()->getCodigoGeneralBancolombia() != "" ? $this->RellenarNr($arPagoBancoDetalle->getEmpleadoRel()->getBancoRel()->getCodigoGeneralBancolombia(), 0, 9) : "005600078"); // (9) Banco cuenta del beneficiario
            fputs($ar, $arPagoBancoDetalle->getCuenta()); // (17) Nro cuenta beneficiario
            fputs($ar, "      "); // (17) Nro cuenta beneficiario
            fputs($ar, "337"); // (1) Indicador de lugar de pago (2) y tipo de transacción (37)
            $duoValorNetoPagar = round($arPagoBancoDetalle->getVrPago()); // (17) Valor transacción
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