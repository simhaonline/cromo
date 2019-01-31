<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Estandares;

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
        $arConductor = self::$em->getRepository('App:Transporte\TteConductor')->find(self::$codigoConductor);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'HOJA DE VIDA', self::$em);
        $this->SetFont('Arial', 'b', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Text(15, 25, '');
        $this->SetXY(10, 50);
        $this->Cell(30,5,"NUEVO",1);
        $this->Cell(40,5,"ACTUALIZADO",1);
        $this->Cell(60,5,"FECHA REGISTRO",1, 0, 'R');
        $this->Cell(20,5,"",1);
        $this->Cell(20,5,"",1);
        $this->Cell(20,5,"",1);
        $this->Ln();
        $this->Cell(130,5," ",1);
        $this->Cell(20,5,"DIA" ,1);
        $this->Cell(20,5,"MES",1);
        $this->Cell(20,5,utf8_decode("AÑO"),1);
        $this->Ln();
        $this->Cell(190,5," ",1);
        $this->Ln();
        $this->SetFont('Arial', 'b', 9);
        $this->Cell(190,5,utf8_decode("REGISTRO DEL VEHÍCULO "),0, 0 ,'C');
        $this->Ln();
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(25,5,"PLACA:" ,1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(15,5, $arConductor->getCodigoVehiculo(),1);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(20,5,"MODELO",1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20,5,"",1);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(30,5,"MARCA",1, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(30,5,"",1, 0, 'L');
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(30,5,"CAP. TON",1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20,5,"",1);
        $this->Ln();
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(25,5,"COLOR:" ,1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(15,5, "",1);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(20,5,utf8_decode("CARROCERIA:"),1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20,5,"",1);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(30,5,"EJES:",1, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(30,5,"",1, 0, 'L');
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(30,5,"MOTOR:",1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20,5,"",1);
        $this->Ln();
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(25,5,"CHASIS/SERIE:" ,1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(15,5, "",1);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(20,5,utf8_decode("LÍNEA:"),1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20,5,"",1);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(30,5,"MATRICULA:",1, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(30,5,"",1, 0, 'L');
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(30,5,"",1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(20,5,"",1);

//        $this->SetFont('Arial', 'b', 13);
//        $this->SetXY(57, 12);
//        $this->Cell(30, 6, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'l', 1);
//        $this->SetFont('Arial', '', 10);
//        $this->SetXY(64, 17);
//        $this->Cell(30, 6, utf8_decode($arConfiguracion->getDireccion()), 0, 0, 'l', 1);
//        $this->SetXY(72, 22);
//        $this->Cell(30, 6, utf8_decode($arConfiguracion->getTelefono()), 0, 0, 'l', 1);
//        $this->SetXY(10, 29);
//        $this->SetFont('Arial', 'b', 10);
//        $this->Cell(25, 5, utf8_decode("NIT."), 0, 0, 'l', 1);
//        $this->SetXY(20, 29);
//        $this->SetFont('Arial', 'b', 10);
//        $this->Cell(25, 5, utf8_decode($arConfiguracion->getNit()."-".$arConfiguracion->getDigitoVerificacion()), 0, 0, 'l', 1);
//        $this->SetFont('Arial', '', 8);
//        $this->SetXY(50, 28);
//        $this->Cell(25, 4, utf8_decode("SOMOS REGIMEN COMUN NO RESPONSABLES DE IVA"), 0, 0, 'l', 1);
//        $this->SetXY(45, 31.5);
//        $this->Cell(25, 4, utf8_decode("CODIGO CIIU 4923 TRANSPORTE DE CARGA POR CARRETERA"), 0, 0, 'l', 1);
//
//        $y = 20;
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y);
//        $this->Cell(39, 6, "FACTURA DE VENTA", 1, 0, 'l', 1);
//        $this->SetFillColor(272, 272, 272);
//        $this->SetXY(160, $y+6);
//        $this->Cell(39, 6, "No.". " ". $arFactura->getFacturaTipoRel()->getPrefijo() . " " . $arFactura->getNumero(), 1, 0, 'l', 1);
//
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y+15);
//        $this->Cell(39, 6, "FECHA EXPEDICION", 1, 0, 'C', 1);
//        $this->SetFillColor(272, 272, 272);
//        $this->SetXY(160, $y+20);
//        $this->Cell(13, 7, $arFactura->getFecha()->format('d'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFecha()->format('m'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFecha()->format('Y'), 1, 0, 'C', 1);
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y+27);
//        $this->Cell(39, 6, "VENCE", 1, 0, 'C', 1);
//        $this->SetFillColor(272, 272, 272);
//        $this->SetXY(160, $y+33);
//        $this->Cell(13, 7, $arFactura->getFechaVence()->format('d'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFechaVence()->format('m'), 1, 0, 'C', 1);
//        $this->Cell(13, 7, $arFactura->getFechaVence()->format('Y'), 1, 0, 'C', 1);
//        $this->SetFillColor(170, 170, 170);
//        $this->SetXY(160, $y+40);
//        $this->Cell(13, 6, "DIA", 1, 0, 'C', 1);
//        $this->Cell(13, 6, "MES", 1, 0, 'C', 1);
//        $this->Cell(13, 6, utf8_decode("AÑO"), 1, 0, 'C', 1);
//
//        $arFactura = new TteFactura();
//        $arFactura = self::$em->getRepository(TteFactura::class)->find(self::$codigoFactura);
//        $this->SetFont('Arial', '', 10);
//        $y = 42;
//        $this->Rect(10, 36, 140, 30);
//        $this->Text(12, $y, utf8_decode("SEÑOR(ES):"));
//        $this->Text(45, $y, utf8_decode($arFactura->getClienteRel()->getNombreCorto()));
//        $this->Text(12, $y+5, utf8_decode("NIT:"));
//        $this->Text(45, $y+5, $arFactura->getClienteRel()->getNumeroIdentificacion() . "-" . $arFactura->getClienteRel()->getDigitoVerificacion());
//        $this->Text(12, $y+10, utf8_decode("DIRECCION:"));
//        $this->Text(45, $y+10, utf8_decode($arFactura->getClienteRel()->getDireccion()));
//        $this->Text(12, $y+15, utf8_decode("CIUDAD:"));
//        $this->Text(45, $y+15, "MEDELLIN");
//        $this->Text(12, $y+20, utf8_decode("TELEFONO:"));
//        $this->Text(45, $y+20, utf8_decode($arFactura->getClienteRel()->getTelefono()));
        $this->EncabezadoDetalles();

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

    }

    public function Footer() {
        $this->Text(165, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}
?>