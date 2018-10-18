<?php

namespace App\Formato\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Utilidades\Estandares;

class Asiento extends \FPDF {
    public static $em;
    public static $codigoAsiento;

    public function Generar($em, $codigoAsiento) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoAsiento = $codigoAsiento;
        $pdf = new Asiento();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Asiento$codigoAsiento.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'ASIENTO');
        $arAsiento = new FinAsiento();
        $arAsiento = self::$em->getRepository(FinAsiento::class)->find(self::$codigoAsiento);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(5, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arAsiento->getCodigoAsientoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CONDUCTOR:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 6, utf8_decode("hola mundo"), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(5, 45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arAsiento->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "VEHICULO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $this->SetX(5);
        $header = array('CUENTA');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(8);
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
        $arAsientosDetalles = self::$em->getRepository(FinAsientoDetalle::class)->formatoAsiento(self::$codigoAsiento);
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 7);
        if($arAsientosDetalles) {
            $cobroTotal = 0;
            $fleteManejoTotal = 0;
            $recaudoTotal = 0;
            foreach ($arAsientosDetalles as $arAsientoDetalle) {
                $pdf->SetX(5);
                $pdf->Cell(8, 4, $arAsientoDetalle['codigoCuentaFk'], 1, 0, 'L');
                $pdf->Ln();
                $pdf->SetX(5);
                $pdf->SetAutoPageBreak(true, 15);

            }
            $pdf->SetX(5);
            $pdf->Cell(139, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(18, 4, number_format($fleteManejoTotal), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($recaudoTotal), 1, 0, 'R');
            $pdf->Cell(18, 4, number_format($cobroTotal), 1, 0, 'R');
            $pdf->SetX(5);
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>