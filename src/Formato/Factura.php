<?php

namespace App\Formato;

use App\Entity\Guia;

class Factura extends \FPDF {
    public static $em;
    public static $codigoFactura;

    public function Generar($em, $codigoFactura) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoFactura = $codigoFactura;
        $pdf = new Factura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Factura$codigoFactura.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',10);


        $this->SetXY(50, 10);
        $this->Cell(238, 7, utf8_decode("Factura"), 0, 0, 'C', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array(utf8_decode('GUIA'), 'FECHA', 'DESTINO', 'UND', 'PES', 'VOL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 15, 70, 20, 20, 20);
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
        $arGuias = self::$em->getRepository(Guia::class)->factura(self::$codigoFactura);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arGuias as $arGuia) {
            $pdf->Cell(15, 4, $arGuia['numero'], 1, 0, 'L');
            //$pdf->Cell(15, 4, $arGuia['fechaIngreso'], 1, 0, 'L');
            $pdf->Cell(70, 4, $arGuia['ciudadDestino'], 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arGuia['pesoVolumen'], 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(178, 5, "TOTAL: ", 1, 0, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(15, 5, number_format(0, 0, '.', ','), 1, 0, 'R');
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>