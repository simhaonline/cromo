<?php

namespace App\Formato\Compra;

use App\Entity\Compra\ComCuentaPagar;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class CuentaPagar extends \FPDF
{
    public static $em;


    public function Generar($em)
    {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new CuentaPagar();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CuentasPagar.pdf", 'D');
    }

    public function Header()
    {
        Estandares::generarEncabezado($this, 'CUENTAS POR PAGAR');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(12);
        $header = array('TIPO', 'NUMERO', 'NIT', 'NOMBRE', 'FECHA', 'VENCE', 'PLAZO', 'VALOR', 'ABONO', 'SALDO');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(25, 15, 20, 45, 15, 15, 10, 15, 15, 15);
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

    public function Body($pdf)
    {
        $em = BaseDatos::getEm();
        $arCuentasPagar = $em->getRepository(ComCuentaPagar::class)->lista()->getQuery()->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arCuentasPagar as $arCuentaPagar) {
            $pdf->Cell(25, 4, $arCuentaPagar['tipo'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arCuentaPagar['numeroDocumento'], 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $arCuentaPagar['numeroIdentificacion'], 'LRB', 0, 'L');
            $pdf->Cell(45, 4, substr($arCuentaPagar['nombreCorto'], 0, 28), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arCuentaPagar['fecha']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arCuentaPagar['fechaVence']->format('Y-m-d'), 'LRB', 0, 'R');
            $pdf->Cell(10, 4, $arCuentaPagar['plazo'], 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arCuentaPagar['vrTotal']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arCuentaPagar['vrAbono']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arCuentaPagar['vrSaldoOperado']), 'LRB', 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }


    }

}

?>