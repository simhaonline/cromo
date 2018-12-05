<?php

namespace App\Formato\Cartera;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class CuentaCobrar extends \FPDF {
    public static $em;


    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new CuentaCobrar();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CuentasCobrar.pdf", 'D');
    }

    public function Header() {
        Estandares::generarEncabezado($this,'CUENTAS POR COBRAR', self::$em);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('TIPO', 'NUMERO','NIT', 'NOMBRE', 'FECHA', 'VENCE', 'PLAZO', 'VALOR', 'ABONO', 'SALDO');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(30, 15, 15, 45, 15, 15, 10, 15, 15, 15);
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
        $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->lista()->getQuery()->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arCuentasCobrar as $arCuentaCobrar) {
            $pdf->Cell(30, 4, $arCuentaCobrar['tipo'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arCuentaCobrar['numeroDocumento'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arCuentaCobrar['numeroIdentificacion'], 'LRB', 0, 'L');
            $pdf->Cell(45, 4, substr($arCuentaCobrar['nombreCorto'],0,28), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arCuentaCobrar['fecha']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arCuentaCobrar['fechaVence']->format('Y-m-d'), 'LRB', 0, 'R');
            $pdf->Cell(10, 4, $arCuentaCobrar['plazo'], 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arCuentaCobrar['vrTotal']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arCuentaCobrar['vrAbono']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arCuentaCobrar['vrSaldoOperado']), 'LRB', 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }


    }

}
?>