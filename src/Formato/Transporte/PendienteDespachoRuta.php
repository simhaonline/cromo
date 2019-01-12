<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class PendienteDespachoRuta extends \FPDF {
    public static $em;


    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new PendienteDespachoRuta();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("PendienteDespachoRuta.pdf", 'D');
    }

    public function Header() {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'PENDIENTE DESPACHO RUTA', self::$em);
        $this->Ln(16);
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(1);
        $header = array('ID', 'DOC_CLI', 'NUMERO', 'FECHA', 'CLIENTE', 'DESTINO', 'DESTINATARIO', 'DECLARA', 'FLETE', 'UND', 'PES','VOL');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        $this->SetX(3);
        //creamos la cabecera de la tabla.
        $w = array(15, 16, 15, 13, 35, 25, 30, 15, 15, 8,8,8);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf) {
        $em = BaseDatos::getEm();
        $arGuias = $em->getRepository(TteGuia::class)->informeDespachoPendienteRuta();
        $vrTotalDeclara = 0;
        $vrTotalFlete = 0;
        $vrTotalUnidad = 0;
        $vrTotalPeso = 0;
        $vrTotalVolumen = 0;
        $primeraRuta = true;
        $pdf->SetX(3);
        $pdf->SetFont('Arial', '', 6);
        if($arGuias) {
            foreach ($arGuias as $arGuia) {
                if($primeraRuta){
                    $pdf->SetX(3);
                    $pdf->Cell(203,4,$arGuia['nombreRuta'],1,0,'L');
                    $primeraRuta = false;
                    $ruta = $arGuia['codigoRutaFk'];
                    $pdf->Ln(4);
                }
                if($arGuia['codigoRutaFk'] != $ruta){
                    $pdf->SetX(152);
                    $pdf->Cell(15,4,number_format($vrTotalDeclara, 0, '.', ',') ,1,0,'R');
                    $pdf->Cell(15,4,number_format($vrTotalFlete, 0, '.', ','),1,0,'R');
                    $pdf->Cell(8,4,number_format($vrTotalUnidad, 0, '.', ','),1,0,'R');
                    $pdf->Cell(8,4,number_format($vrTotalPeso, 0, '.', ','),1,0,'R');
                    $pdf->Cell(8,4,number_format($vrTotalVolumen, 0, '.', ','),1,0,'R');
                    $pdf->Ln(4);
                    $pdf->SetX(3);
                    $pdf->Cell(203,4,$arGuia['nombreRuta'],1,0,'L');
                    $ruta = $arGuia['codigoRutaFk'];
                    $pdf->Ln(4);
                }
                $pdf->SetX(3);
                $pdf->Cell(15, 4, $arGuia['codigoGuiaPk'], 1, 0, 'L');
                $pdf->Cell(16, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(13, 4, $arGuia['fechaIngreso']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(35, 4, utf8_decode(substr($arGuia['clienteNombreCorto'],0,22)), 1, 0, 'L');
                $pdf->Cell(25, 4, utf8_decode(substr($arGuia['ciudadDestino'], 0, 15)), 1, 0, 'L');
                $pdf->Cell(30, 4, utf8_decode(substr($arGuia['nombreDestinatario'], 0, 20)), 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arGuia['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['pesoVolumen'], 0, '.', ','), 1, 0, 'R');
                $vrTotalDeclara += $arGuia['vrDeclara'];
                $vrTotalFlete += $arGuia['vrFlete'];
                $vrTotalUnidad += $arGuia['unidades'];
                $vrTotalPeso += $arGuia['pesoReal'];
                $vrTotalVolumen += $arGuia['pesoVolumen'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 25);
            }
        }
    }

}
?>