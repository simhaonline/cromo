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

class Vehiculo extends \FPDF {
    public static $em;
    public static $codigoVehiculo;

    public function Generar($em, $codigoVehiculo) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoVehiculo = $codigoVehiculo;
        $pdf = new Vehiculo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Vehiculo$codigoVehiculo.pdf", 'D');
    }
    public function Header() {
        Estandares::generarEncabezado($this,'VEHICULO', self::$em, 2);
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
        $arVehiculo = $em->getRepository(TteVehiculo::class)->find(self::$codigoVehiculo);
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
        $pdf->Cell(15,5, $arVehiculo->getCodigoVehiculoPk(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,"MODELO",1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo->getModelo(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"MARCA",1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,$arVehiculo->getMarcaRel()->getNombre(),1, 0, 'L');
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"CAP. TON",1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo->getCapacidad(),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(15,5,"COLOR" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(45,5, $arVehiculo->getColorRel()->getNombre(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CARROCERIA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,utf8_decode(strtoupper($arVehiculo->getTipoCarroceriaRel()->getNombre())),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,"EJES",1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo->getNumeroEjes(),1, 0, 'L');
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,"MOTOR",1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo->getMotor(),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(25,5,"CHASIS/SERIE" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35,5,  $arVehiculo->getChasis(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("LÍNEA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5,$arVehiculo->getLineaRel()->getNombre(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"MATRICULA",1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5,$arVehiculo->getPlaca(),1, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(40,5,"SEGURO OBLIGATORIO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, $arVehiculo->getNumeroPoliza(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("VENCE"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(80,5,$arVehiculo->getFechaVencePoliza()->format('Y-m-d'),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(40,5,"ASEGURADORA" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(70,5, utf8_decode(substr($arVehiculo->getAseguradoraRel()->getNombre(), 0 , 35)),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("REPOTENCIADO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5,$arVehiculo->getModeloRepotenciado(),1);
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
        $pdf->Cell(10,5,($arVehiculo->getFechaVenceTecnicomecanica() ? $arVehiculo->getFechaVenceTecnicomecanica()->format('d') : null),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("MES"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,($arVehiculo->getFechaVenceTecnicomecanica() ? $arVehiculo->getFechaVenceTecnicomecanica()->format('m') : null),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(10,5,utf8_decode("AÑO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,5,($arVehiculo->getFechaVenceTecnicomecanica() ? $arVehiculo->getFechaVenceTecnicomecanica()->format('Y') : null),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(40,5,"SEGURO NC" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, $arVehiculo->getRegistroNacionalCarga(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,utf8_decode("NUMERO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20,5,$arVehiculo->getRegistroNacionalCarga(),1);
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
        $pdf->Cell(50,5, $arVehiculo->getPoseedorRel()->getNombreCorto(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CEDULA"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, $arVehiculo->getPoseedorRel()->getNumeroIdentificacion(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, $arVehiculo->getPoseedorRel()->getCiudadRel()->getNombre(),1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"DIRECCION" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arVehiculo->getPoseedorRel()->getDireccion(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CIUDAD"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, $arVehiculo->getPoseedorRel()->getCiudadRel()->getNombre(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("BARRIO"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40,5, '',1);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(30,5,"TELEFONO" ,1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50,5, $arVehiculo->getPoseedorRel()->getTelefono(),1);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(20,5,utf8_decode("CELULAR"),1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30,5, $arVehiculo->getPoseedorRel()->getMovil(),1);
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
    }

    public function Footer() {
        $this->SetFont('Arial', 'b', 7);
        $this->Text(185, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
?>