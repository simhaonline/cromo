<?php

namespace App\Formato\Cartera;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class CarteraEdadCliente extends \FPDF {
    public static $em;


    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new CarteraEdadCliente();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CuentasCobrar.pdf", 'D');
    }

    public function Header() {
        Estandares::generarEncabezado($this,'CARTERA EDADES (CLIENTE)');
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('TIPO', 'NUMERO', 'FECHA', 'VENCE', 'DIAS', 'P_V', '30', '60', '90', '180', 'MAS');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 20, 17, 17, 10, 18, 18, 18, 18, 18, 18);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
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
        $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->carteraEdadesCliente()->getQuery()->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $arrTotalesCliente = array('0'=>0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0);
        $arrTotalesGeneral = array('0'=>0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0);
        $primerCliente = true;
        foreach ($arCuentasCobrar as $arCuentaCobrar) {
            if($primerCliente){
                $pdf->Cell(187,4, $arCuentaCobrar['clienteNombre'],1,0,'L');
                $primerCliente = false;
                $cliente = $arCuentaCobrar['codigoClienteFk'];
                $pdf->Ln(4);
            }
            if($arCuentaCobrar['codigoClienteFk'] != $cliente){
                $totalCliente = array_sum($arrTotalesCliente);
                $pdf->SetX(45);
                $pdf->Cell(17, 4, "TOTAL:", 'LRB', 0, 'L');
                $pdf->Cell(27, 4, number_format($totalCliente), 'LRB', 0, 'R');
                $pdf->Cell(18, 4, number_format($arrTotalesCliente[0]+$arrTotalesCliente[1]), 'LRB', 0, 'R');
                $pdf->Cell(18, 4, number_format($arrTotalesCliente[2]), 'LRB', 0, 'R');
                $pdf->Cell(18, 4, number_format($arrTotalesCliente[3]), 'LRB', 0, 'R');
                $pdf->Cell(18, 4, number_format($arrTotalesCliente[4]), 'LRB', 0, 'R');
                $pdf->Cell(18, 4, number_format($arrTotalesCliente[5]), 'LRB', 0, 'R');
                $pdf->Cell(18, 4, number_format($arrTotalesCliente[6]), 'LRB', 0, 'R');
                $pdf->Ln(4);
                $pdf->Cell(187,4,$arCuentaCobrar['clienteNombre'],1,0,'L');
                $cliente = $arCuentaCobrar['codigoClienteFk'];
                $arrTotalesCliente = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0);
                $pdf->Ln(4);
            }
            $arrRango = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0);
            $arrRango[$arCuentaCobrar['rango']] = $arCuentaCobrar['vrSaldoOperado'];
            for($i = 0; $i <= 6; $i++) {
                $arrTotalesCliente[$i] += $arrRango[$i];
                $arrTotalesGeneral[$i] += $arrRango[$i];
            }
            $pdf->Cell(15, 4, $arCuentaCobrar['codigoCuentaCobrarTipoFk'], 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $arCuentaCobrar['numeroDocumento'], 'LRB', 0, 'L');
            $pdf->Cell(17, 4, $arCuentaCobrar['fecha']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(17, 4, $arCuentaCobrar['fechaVence']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(10, 4, number_format($arCuentaCobrar['diasVencimiento']), 'LRB', 0, 'R');
            $pdf->Cell(18, 4, number_format($arrRango[0]+$arrRango[1]), 'LRB', 0, 'R');
            $pdf->Cell(18, 4, number_format($arrRango[2]), 'LRB', 0, 'R');
            $pdf->Cell(18, 4, number_format($arrRango[3]), 'LRB', 0, 'R');
            $pdf->Cell(18, 4, number_format($arrRango[4]), 'LRB', 0, 'R');
            $pdf->Cell(18, 4, number_format($arrRango[5]), 'LRB', 0, 'R');
            $pdf->Cell(18, 4, number_format($arrRango[6]), 'LRB', 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $totalGeneral = array_sum($arrTotalesGeneral);
        //$pdf->SetX(45);
        $pdf->Cell(52, 4, "TOTAL GENERAL:", 'LRB', 0, 'L');
        $pdf->Cell(27, 4, number_format($totalGeneral), 'LRB', 0, 'R');
        $pdf->Cell(18, 4, number_format($arrTotalesGeneral[0]+$arrTotalesGeneral[1]), 'LRB', 0, 'R');
        $pdf->Cell(18, 4, number_format($arrTotalesGeneral[2]), 'LRB', 0, 'R');
        $pdf->Cell(18, 4, number_format($arrTotalesGeneral[3]), 'LRB', 0, 'R');
        $pdf->Cell(18, 4, number_format($arrTotalesGeneral[4]), 'LRB', 0, 'R');
        $pdf->Cell(18, 4, number_format($arrTotalesGeneral[5]), 'LRB', 0, 'R');
        $pdf->Cell(18, 4, number_format($arrTotalesGeneral[6]), 'LRB', 0, 'R');
        $pdf->Ln(4);

    }

}
?>