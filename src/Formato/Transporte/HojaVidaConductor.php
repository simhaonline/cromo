<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteVehiculo;
use App\Utilidades\Estandares;
use Doctrine\ORM\EntityManager;

class HojaVidaConductor extends \FPDF {
    public static $em;
    public static $codigoConductor;

    public function Generar($em, $codigoConductor) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoConductor = $codigoConductor;
        $pdf = new HojaVidaConductor();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("HojaDeVida$codigoConductor.pdf", 'D');
    }
    public function Header() {
        Estandares::generarEncabezado($this,'HOJA DE VIDA', self::$em, 2);
        $this->SetFont('Arial', 'b', 7);
        $this->SetFillColor(272, 272, 272);

        $this->EncabezadoDetalles();
        $this->Ln(16);
    }

    public function EncabezadoDetalles() {
//        $this->Ln(20);
//        $header = array('GUIA', 'DOCUMENTO','DESTINATARIO', 'DESTINO', 'UND', 'KF', 'DECLARA', 'FLETE', 'MANEJO', 'TOTAL');
//        $this->SetFillColor(170, 170, 170);
//        $this->SetTextColor(0);
//        $this->SetDrawColor(0, 0, 0);
//        $this->SetLineWidth(.2);
//        $this->SetFont('', 'B', 7);
//        //creamos la cabecera de la tabla.
//        $w = array(17, 24, 43, 26, 10, 10, 15, 15, 15, 15);
//        for ($i = 0; $i < count($header); $i++)
//            if ($i == 0 || $i == 1)
//                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
//            else
//                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
//        //Restauración de colores y fuentes
//        $this->SetFillColor(224, 235, 255);
//        $this->SetTextColor(0);
//        $this->SetFont('');
//        $this->Ln(4);
    }

    public function Body($pdf) {
        /** @var  $em EntityManager */
        $em = self::$em;
        $arConductor = $em->getRepository('App:Transporte\TteConductor')->find(self::$codigoConductor);
        $arVehiculo = $em->getRepository(TteVehiculo::class)->findOneBy(['placa' => $arConductor->getCodigoVehiculo()]);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->SetFillColor(272, 272, 272);
        $pdf->Text(15, 25, '');
        $pdf->SetX(10);
        $pdf->Cell(30,5,"NUEVO",1);
        $pdf->Cell(40,5,"ACTUALIZADO",1);
        $pdf->Cell(60,5,"FECHA REGISTRO",1, 0, 'R');
        $pdf->Cell(20,5,"",1);
        $pdf->Cell(20,5,"",1);
        $pdf->Cell(20,5,"",1);
        $pdf->Ln();
        $pdf->Cell(130,5," ",1);
        $pdf->Cell(20,5,"DIA" ,1);
        $pdf->Cell(20,5,"MES",1);
        $pdf->Cell(20,5,utf8_decode("AÑO"),1);
        $pdf->Ln();
        $pdf->Cell(190,5," ",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("REGISTRO DEL VEHÍCULO "),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(25,5,"PLACA" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15,5, $arConductor->getCodigoVehiculo(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,"MODELO",1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo ? $arVehiculo->getModelo() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"MARCA",1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,$arVehiculo ? $arVehiculo->getMarcaRel()->getNombre() : '' ,1, 0, 'L');
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"CAP. TON",1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo ? $arVehiculo->getCapacidad() : '',1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(15,5,"COLOR" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(45,5, $arVehiculo ? $arVehiculo->getColorRel()->getNombre() : '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CARROCERIA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,utf8_decode(strtoupper($arVehiculo ? $arVehiculo->getTipoCarroceriaRel()->getNombre() : '' )),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,"EJES",1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo ? $arVehiculo->getNumeroEjes() : '' ,1, 0, 'L');
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,"MOTOR",1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo ? $arVehiculo->getMotor() : '',1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(25,5,"CHASIS/SERIE" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5,  $arVehiculo ? $arVehiculo->getChasis() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("LÍNEA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,$arVehiculo ? $arVehiculo->getLineaRel()->getNombre() : '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"MATRICULA",1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5,$arVehiculo ? $arVehiculo->getPlaca() : '',1, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(40,5,"SEGURO OBLIGATORIO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, $arVehiculo ? $arVehiculo->getNumeroPoliza() : '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("VENCE"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(80,5,$arVehiculo ? $arVehiculo->getFechaVencePoliza()->format('Y-m-d') : '' ,1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(40,5,"ASEGURADORA" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(70,5, utf8_decode($arVehiculo ? $arVehiculo->getAseguradoraRel()->getNombre() : '' ),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("REPOTENCIADO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5,$arVehiculo ? $arVehiculo->getModeloRepotenciado() : '',1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(40,5,"REVISION TECNICOMECANICA" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("NUMERO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,"",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("DIA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,($arVehiculo ? $arVehiculo->getFechaVenceTecnicomecanica()->format('d') : null),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("MES"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,($arVehiculo ? $arVehiculo->getFechaVenceTecnicomecanica()->format('m') : null),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("AÑO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,($arVehiculo ? $arVehiculo->getFechaVenceTecnicomecanica()->format('Y') : null),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(40,5,"SEGURO NC" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, $arVehiculo ? $arVehiculo->getRegistroNacionalCarga() : '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("NUMERO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo ? $arVehiculo->getRegistroNacionalCarga() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("DIA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,"",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("MES"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,"",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("AÑO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("REGISTRO PROPIETARIO O POSEEDOR "),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"NOMBRE COMPLETO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arVehiculo ? $arVehiculo->getPoseedorRel()->getNombreCorto() : '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CEDULA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, $arVehiculo ? $arVehiculo->getPoseedorRel()->getNumeroIdentificacion(): '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, $arVehiculo ? $arVehiculo->getPoseedorRel()->getCiudadRel()->getNombre() : '' ,1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"DIRECCION" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arVehiculo ? $arVehiculo->getPoseedorRel()->getDireccion() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, $arVehiculo ? $arVehiculo>getPoseedorRel()->getCiudadRel()->getNombre() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("BARRIO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, '',1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"TELEFONO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arVehiculo ? $arVehiculo->getPoseedorRel()->getTelefono() : '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CELULAR"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, $arVehiculo ? $arVehiculo->getPoseedorRel()->getMovil() : '' ,1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode(""),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"REFERENCIA PER" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("TELEFONO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,"",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CELULAR"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"CIDUAD" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(45,5,utf8_decode("NOMBRE DE QUIEN CONFIRMA REF"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(65,5,"",1);
        $pdf->Ln();
        $pdf->Cell(190,5," ",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("REGISTRO DEL CONDUCTOR "),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"NOMBRE COMPLETO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arConductor->getNombreCorto(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CEDULA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,$arConductor->getNumeroIdentificacion(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5,$arConductor->getCiudadRel()->getNombre(),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"CELULAR" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arConductor->getMovil(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("TELEFONO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, $arConductor->getTelefono(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("BARRIO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5,$arConductor->getBarrio(),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"FECHA NACIMIENTO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arConductor->getFechaNacimiento()->Format('Y-m-d'),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("RH"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("EDAD"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"REFERENCIA PER" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arConductor->getTelefono(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("TELEFONO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,"",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CELULAR"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"LICENCIA" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arConductor->getNumeroLicencia(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CATEGORIA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,$arConductor->getCategoriaLicencia(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("FECHA VENCE"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5,$arConductor->getFechaVenceLicencia()->Format('Y-m-d'),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"CIDUAD" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(45,5,utf8_decode("NOMBRE DE QUIEN CONFIRMA REF"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(65,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
//        $pdf->Cell(190,5,utf8_decode("TIPO CONTRATO "),0, 0 ,'C');
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,"TIPO CONTRATO" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(50,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(45,5,utf8_decode("AÑOS EXPERIENCIA"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(65,5,"",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 9);
//        $pdf->Cell(190,5,utf8_decode("RUTAS CONOCIDAS "),0, 0 ,'C');
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(190,5,"" ,1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(190,5,"" ,1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 9);
//        $pdf->Cell(190,5,utf8_decode("REFERENCIAS LABORALES"),0, 0 ,'C');
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,"RAZON SOCIAL" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("NIT"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,"DIRECCION" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(50,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("TELEFONO"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(30,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(60,5,"NOMBRE DE QUIEN CONFIRMA REFERENCIA" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("CARGO"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(45,5,"ZONAS A LAS QUE HA CARGADO" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(35,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,utf8_decode("TIPO DE PRODUCTOS"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(21,5,utf8_decode("NUM DE VIAJES"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(19,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(80,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(80,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5, "OBSERVACIONES",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(160,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(190,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(190,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,"RAZON SOCIAL" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("NIT"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,"DIRECCION" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(50,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("TELEFONO"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(30,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(60,5,"NOMBRE DE QUIEN CONFIRMA REFERENCIA" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("CARGO"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(45,5,"ZONAS A LAS QUE HA CARGADO" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(35,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,utf8_decode("TIPO DE PRODUCTOS"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(21,5,utf8_decode("NUM DE VIAJES"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(19,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(80,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(80,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5, "OBSERVACIONES",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(160,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(190,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(190,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,"RAZON SOCIAL" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("NIT"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,"DIRECCION" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(50,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("TELEFONO"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(30,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(60,5,"NOMBRE DE QUIEN CONFIRMA REFERENCIA" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(20,5,utf8_decode("CARGO"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(45,5,"ZONAS A LAS QUE HA CARGADO" ,1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(35,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5,utf8_decode("TIPO DE PRODUCTOS"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(21,5,utf8_decode("NUM DE VIAJES"),1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(19,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(80,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(80,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(70,5, "",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(40,5, "",1);
//        $pdf->Ln();
//        $pdf->SetFont('Arial', 'b', 7);
//        $pdf->Cell(30,5, "OBSERVACIONES",1);
//        $pdf->SetFont('Arial', '', 8);
//        $pdf->Cell(160,5, "",1);
//        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(190,5, "",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(190,5, "",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("ARCHIVOS FOTOGRÁFICOS DEL VEHÍCULO Y CONDUCTOR"),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(95,5,utf8_decode("Anexar en el modulo vehículo de sistema operativo") ,1, 0, 'C');
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(95,5,utf8_decode("Anexar en el modulo Datos Básicos de sistema operativo"),1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("DOCUMENTOS SOLICITADOS (Originales)"),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,"LICENCIA CONDUCCION" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5, $arConductor->getNumeroLicencia(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,utf8_decode("REVISION TECNICOMEC"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5,$arVehiculo ? $arVehiculo->getNumeroTecnicomecanica() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(25,5,utf8_decode("ARL"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25,5,$arConductor->getArl(),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,"MATRICULA VEHÍCULO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5, $arVehiculo ? $arVehiculo->getPlaca() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,utf8_decode("SOAT"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5,$arVehiculo ? $arVehiculo->getNumeroPoliza() : '',1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(25,5,utf8_decode("EPS"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25,5,'',1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,"CEDULA CONDUCTOR" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5, $arConductor->getNumeroIdentificacion(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,utf8_decode("EXAMENES MEDICOS"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5,"",1);
        $pdf->SetFont('Arial', 'b', 5);
        $pdf->Cell(25,5,utf8_decode("PLAN MANTENIMIENTO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25,5,'',1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,"PRUEBA PRACTICA" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5, $arConductor->getNumeroIdentificacion(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,utf8_decode("CERT CAP"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(85,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("REVISION CENTRAL DE RIESGOS (Verificar y anexar)"),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("DEFENCARGA VEHÍCULO") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(48,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("SIMIT"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(48,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("DEFENCARGA CONDUCTOR") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(48,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("ANTECEDENTES JUDICIALES"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(48,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("DEFENCARGA PROP O TEN") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(48,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("RUN"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(48,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("CERTIFICADO PROCURADURIA ") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(143,5, "",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5, utf8_decode("FECHA") ,1, 0, 'C');
        $pdf->Cell(48,5, utf8_decode("REPORTE DE ACCIDENTE"),1, 0, 'C');
        $pdf->Cell(47,5, utf8_decode("DEPORTE DE INCIDENTE"),1, 0, 'C');
        $pdf->Cell(48,5, utf8_decode("VERIFICACION EN EL SIMIT"),1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5, "" ,1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Cell(47,5, "",1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5, "" ,1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Cell(47,5, "",1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5, "" ,1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Cell(47,5, "",1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5, "" ,1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Cell(47,5, "",1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5, "" ,1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Cell(47,5, "",1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5, "" ,1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Cell(47,5, "",1, 0, 'C');
        $pdf->Cell(48,5, "",1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("ELABORADO POR PERSONAL DE MONITOREO"),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(57,5,utf8_decode("NOMBRE COMPLETO") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(68,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(27,5,utf8_decode("FECHA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("VERIFICADO POR PERSONAL DE MONITOREO"),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(57,5,utf8_decode("NOMBRE COMPLETO") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(68,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(27,5,utf8_decode("FECHA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("APROBACIÓN DIRECTOR DE RIESGOS"),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(47,5,utf8_decode("APROBADO") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(24,5, "SI",1);
        $pdf->Cell(24,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(24,5,utf8_decode("NO"),1);
        $pdf->Cell(24,5,utf8_decode(""),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(48,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(191,5,"" ,1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(190,5,"APROBADO POR" ,0, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(57,5,utf8_decode("NOMBRE COMPLETO") ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(68,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(27,5,utf8_decode("FECHA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(38,5,"",1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(190,5,utf8_decode("FIRMA CONDUCTOR"),0, 0 ,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("NOMBRE COMPLETO") ,1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(68,5, "",1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(35,5,utf8_decode("IDENTIFICACION"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(27,5,"",1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,24,"",1);
    }

    public function Footer() {
        $this->SetFont('Arial', 'b', 7);
        $this->Text(185, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
?>