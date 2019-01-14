<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class PendienteFacturaCliente extends \FPDF {
    public static $em;


    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new PendienteFacturaCliente();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("PendienteFacturaCliente.pdf", 'D');
    }

    public function Header() {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'PENDIENTE FACTURAR', self::$em);
        $this->Ln(16);
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(1);
        $header = array('TIPO', 'GUIA', 'FECHA', 'SERV', 'OI', 'OC', 'NUMERO', 'DOCUMENTO', 'DESTINO', 'UND','PES','VOL','DECLARADO','FLET','MANEJO');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        $this->SetX(3);
        //creamos la cabecera de la tabla.
        $w = array(7, 15, 15, 8, 6, 6, 25,25, 25, 8,8,8,18,12,12);
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
        $arGuias = $em->getRepository(TteGuia::class)->pendienteFacturarCliente();
        $cliente=null;
        $pdf->SetX(3);
        $pdf->SetFont('Arial', '', 6);
        if($arGuias) {
            foreach ($arGuias as $key=>$arGuia) {
                if(!$cliente || $cliente!=$arGuia['codigoClienteFk']){
                    if($key>0){
                        $pdf->Ln(4);
                    }
                    $cliente=$arGuia['codigoClienteFk'];
                    $pdf->SetX(3);
                    $pdf->Cell(198,4,$arGuia['clienteNombreCorto'],1,0,'L');
                    $pdf->Ln(4);
                }
                $pdf->SetX(3);
                $pdf->Cell(7, 4, $arGuia['codigoGuiaTipoFk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['codigoGuiaPk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['fechaIngreso']?$arGuia['fechaIngreso']->format('Y-m-d'):"", 1, 0, 'L');
                $pdf->Cell(8, 4, $arGuia['codigoServicioFk'], 1, 0, 'L');
                $pdf->Cell(6, 4, $arGuia['codigoOperacionIngresoFk'], 1, 0, 'L');
                $pdf->Cell(6, 4, $arGuia['codigoOperacionIngresoFk'], 1, 0, 'L');
                $pdf->Cell(25, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(25, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(25, 4, $arGuia['ciudadDestino'], 1, 0, 'L');
                $pdf->Cell(8, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(8, 4, number_format($arGuia['pesoVolumen'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(18, 4, number_format($arGuia['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(12, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(12, 4, number_format($arGuia['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 25);
            }
        }
    }

}
?>