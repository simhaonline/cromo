<?php

namespace App\Controller\RecursoHumano\Utilidad\embargo;


use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BancoAgrarioController extends MaestroController
{


    public $tipo = "proceso";
    public $proceso = "rhuu0003";

    /**
     * @param Request $request
     * @Route("/recursohumano/utilidad/embargo/bancoAgrario/lista", name="recursohumano_utilidad_embargo_bancoAgrario_lista")
     **/
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fechaActual = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'data'=> $fechaActual,'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'data'=>$fechaActual,'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-default btn-default']])
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $raw=null;
            $respuesta="";
            if ($form->get('btnGenerar')->isClicked()) {
                $fechasDesde = $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null;
                $fechasHasta =$form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null;
                if ($fechasDesde == '') {
                    $respuesta = 'Debe ingresar una fecha desde';
                } elseif ($fechasHasta == '') {
                    $respuesta = 'Debe ingresar un fecha hasta';
                } else {
                    $raw = [
                        'fechaDesde' => $fechasDesde,
                        'fechaHasta' => $fechasHasta
                    ];
                }
                if ($respuesta == '') {
                    $arEmbargoPagos = $em->getRepository(RhuPagoDetalle::class)->embargos($raw);
                    if (count($arEmbargoPagos) > 0) {
                        $this->generarArchivoBancoAgrario($arEmbargoPagos, $fechasDesde, $fechasHasta);
                    } else {
                        Mensajes::error('No se encontraron pagos a embargos entre las fechas ingresadas');
                    }
                } else {
                    Mensajes::error($respuesta);
                }
            }
        }
        return $this->render('recursohumano/utilidad/embargo/bancoAgrario.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $arEmbargoPagos array
     * @param $fechaDesde string
     * @param $fechaHasta string
     */
    private function generarArchivoBancoAgrario($arEmbargoPagos, $fechaDesde, $fechaHasta)
    {
        /**
         * @var $arEmbargo RhuEmbargo
         * @var $arEmbargoPago RhuEmbargoPago
         */
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionGeneral = $em->getRepository(GenConfiguracion::class)->find(1);
        $arrErrores = [];
        if (strlen($arConfiguracionGeneral->getNit().$arConfiguracionGeneral->getDigitoVerificacion()) < 10 || strlen($arConfiguracionGeneral->getNit().$arConfiguracionGeneral->getDigitoVerificacion()) > 10) {
            $arrErrores[] = 'El nit de la empresa debe ser de 9 caracteres y el dígito de verificación de uno, por favor ajustar el nit en la configuración general';
        }
        if (count($arrErrores) == 0) {
            $strNombreArchivo = "GD" . date('Ymd').$this->RellenarNr($arConfiguracionGeneral->getNit(), 0, 11)."_01". ".TXT";
            $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
            ob_clean();
            $ar = fopen($strArchivo, "a") or die ("Problemas en la creación del archivo plano");
            $contador = 0;
            $consecutivo = 1;
            foreach ($arEmbargoPagos AS $arEmbargoPago) {
                $contador++;
            }
            //Espacio en blanco
            fputs($ar, '                       ');
            //Cantidad de registros
            fputs($ar, $this->RellenarNr($contador, 0, 10));
            //Espacio en blanco
            fputs($ar, '                                        ');
            //Tipo documento empresa
            fputs($ar, '3');
            //Espacio en blanco
            fputs($ar, ' ');
            //Numero documento empresa
            fputs($ar, $arConfiguracionGeneral->getNit().$arConfiguracionGeneral->getDigitoVerificacion());
            //Espacio en blanco
            fputs($ar, '            ');
            //Nombre de la empresa
            if (strlen($arConfiguracionGeneral->getNombre()) > 20) {
                fputs($ar, $this->RellenarNr2($arConfiguracionGeneral->getNombre(), ' ', 40, 'D'));

            } else {
                fputs($ar, '                    ');
                fputs($ar, $this->RellenarNr2($arConfiguracionGeneral->getNombre(), ' ', 20, 'D'));
            }
            //Espacio en blanco
            fputs($ar, '                                                               ');
            fputs($ar, "\n");
            $date = new \DateTime('now');
            $date = $date->format('Ymd');
            foreach ($arEmbargoPagos AS $arEmbargoPago) {
                $arEmbargo = $em->getRepository(RhuEmbargo::class)->find($arEmbargoPago->getCodigoEmbargoFk());
                //Numero consecutivo generado automáticamente para el archivo
                fputs($ar, $this->RellenarNr($consecutivo, 0, 6));
                //Fecha del dia que se genera el archivo
                fputs($ar, $date);
                //Codigo oficina origen
                fputs($ar, $this->RellenarNr('30', 0, 4));
                //Codigo oficina destino
                if ($arEmbargo->getOficinaDestino() == '' || $arEmbargo->getOficinaDestino() == null) {
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene oficina de destino";
                }
                fputs($ar, $arEmbargo->getOficinaDestino());
                //Tipo de embargo
                switch ($arEmbargo->getCodigoEmbargoTipoFk()) {
                    case 'JUD':
                        $tipoEmbargo = 1;
                        break;
                    case 'COM':
                        $tipoEmbargo = 2;
                        break;
                    case 'ALI':
                        $tipoEmbargo = 6;
                        break;
                }
                fputs($ar, $tipoEmbargo);
                //Numero expediente
                if (!$arEmbargo->getNumeroExpediente()) {
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene numero de expediente.";
                } elseif (strlen($arEmbargo->getNumeroExpediente()) > 10) {
                    $arrErrores[] = "El numero de expediente en el embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()} tiene demasiados caracteres, solo 10 permitidos.";
                }
                fputs($ar, $this->RellenarNr($arEmbargo->getNumeroExpediente(), 0, 10));
                //Numero de cuenta
                if ($arEmbargo->getCuenta() == '' || $arEmbargo->getCuenta() == null) {
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene numero de cuenta.";
                } elseif (strlen($arEmbargo->getCuenta()) > 12) {
                    $arrErrores[] = "El numero de cuenta en el embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()} tiene demasiados caracteres, solo 12 permitidos.";
                }
                fputs($ar, $this->RellenarNr($arEmbargo->getCuenta(), 0, 12));
                //Valor del deposito
                //Numero cuenta de ahorros (Siempre se llena con 0)
                fputs($ar,'000000000000');
                fputs($ar, $this->RellenarNr($arEmbargoPago->getVrPago() . '.00', 0, 16));
                //Tipo identificacion del demandante
                fputs($ar, '1');
                //Numero identificacion del demandante
                if (!$arEmbargo->getNumeroIdentificacionDemandante()) {
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene identificacion del demandante.";
                }
                fputs($ar, $this->RellenarNr($arEmbargo->getNumeroIdentificacionDemandante(), '0', '11'));
                //Tipo identificacion empleado
                $tipoDocumentoDemandado = $this->tipoDocumento($arEmbargo->getEmpleadoRel()->getCodigoIdentificacionFk());
                fputs($ar, $tipoDocumentoDemandado);
                //Numero identificacion empleado
                fputs($ar, $this->RellenarNr($arEmbargo->getEmpleadoRel()->getNumeroIdentificacion(), 0, 11));
                //Nombres del demandante
                if($arEmbargo->getNombreCortoDemandante() == '' || $arEmbargo->getNombreCortoDemandante() == null){
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene los nombres del demandante";
                }
                fputs($ar, $this->RellenarNr2($arEmbargo->getNombreCortoDemandante(), ' ', 20,'R'));
                //Apellidos del demandante
                if($arEmbargo->getApellidosDemandante() == '' || $arEmbargo->getApellidosDemandante() == null){
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene los apellidos del demandante";
                }
                fputs($ar, $this->RellenarNr2($arEmbargo->getApellidosDemandante(), ' ', 20,'R'));

                //Nombres del empleado
                fputs($ar, $this->RellenarNr2($arEmbargo->getEmpleadoRel()->getNombre1().$arEmbargo->getEmpleadoRel()->getNombre2(), ' ', 20,'R'));
                //Apellidos del empleado
                fputs($ar, $this->RellenarNr2($arEmbargo->getEmpleadoRel()->getApellido1().$arEmbargo->getEmpleadoRel()->getApellido2(), ' ', 20,'R'));
                //Se valida el numero de proceso
                if (!$arEmbargo->getConsecutivoJuzgado()) {
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene consecutivo del juzgado.";
                }
                if (!$arEmbargo->getCodigoInstancia()) {
                    $arrErrores[] = "El embargo {$arEmbargo->getCodigoEmbargoPk()}, del empleado con cédula {$arEmbargo->getEmpleadoRel()->getNumeroIdentificacion()}, no tiene codigo de la instancia.";
                }
                fputs($ar, $arEmbargo->getCodigoEmbargoJuzgadoFk() . $arEmbargo->getFecha()->format('Y') . $arEmbargo->getConsecutivoJuzgado() . $arEmbargo->getCodigoInstancia());
                fputs($ar, "\n");
                $consecutivo++;
            }
            if (count($arrErrores) > 0) {
                $this->imprimirarrErrores($arrErrores,$arConfiguracionGeneral);
            } else {
//            //Inicio cuerpo
                fclose($ar);
//            Fin cuerpo
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
        } else {
            $this->imprimirarrErrores($arrErrores,$arConfiguracionGeneral);
        }
    }

    //Rellenar numeros
    private function RellenarNr($Nro, $Str, $NroCr)
    {
        $Longitud = strlen($Nro);

        $Nc = $NroCr - $Longitud;
        for ($i = 0; $i < $Nc; $i++)
            $Nro = $Str . $Nro;

        return (string)$Nro;
    }

    private function RellenarNr2($Nro, $Str, $NroCr, $strPosicion)
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

    private function imprimirarrErrores($arrErrores,$arConfiguracionGeneral)
    {
        $strNombreArchivo = "Errores" . date('YmdHis') . ".txt";
        $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
        ob_clean();
        $ar = fopen($strArchivo, "a") or die ("Problemas en la creación del archivo plano");
        fputs($ar,'ERRORES A CORREGIR PARA LA CREACIÓN DEL ARCHIVO PLANO ()'."\n\n\n");
        foreach($arrErrores as $arrError){
            fputs($ar,$arrError."\n");
        }
        fclose($ar);
//      Fin cuerpo
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

    private function tipoDocumento($codigoTipo)
    {
        switch ($codigoTipo) {
            case 'CC':
                $tipoDocumento = 1;
                break;
            case 'CE':
                $tipoDocumento = 2;
                break;
            case 'NI':
                $tipoDocumento = 3;
                break;
            case 'PA':
                $tipoDocumento = 4;
                break;
            case 'TI':
                $tipoDocumento = 5;
                break;
        }
        return $tipoDocumento;
    }
}