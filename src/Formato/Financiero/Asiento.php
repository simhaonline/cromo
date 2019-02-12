<?php

namespace App\Formato\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Utilidades\Estandares;

class Asiento extends \FPDF
{
    public static $em;
    public static $codigoAsiento;

    public function Generar($em, $codigoAsiento)
    {
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

    public function Header()
    {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        $arAsiento = self::$em->getRepository(FinAsiento::class)->find(self::$codigoAsiento);
        Estandares::generarEncabezado($this, $arAsiento->getComprobanteRel()->getNombre(), self::$em);

        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arAsiento->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "COMPROBANTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(100, 6, $arAsiento->getComprobanteRel()->getNombre(), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arAsiento->getFechaContable()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(100, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);

        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(100, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);

        $this->SetXY(10, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(160, 4, $arAsiento->getComentario(), 1, 'L');


        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(10);
        $header = array('ID', 'CUENTA', 'NIT', 'TERCERO', 'DEBITO', 'CREDITO', 'BASE');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 65, 20, 35, 20, 20, 20);
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

    /**
     * @var $arAsientoDetalle FinAsientoDetalle
     */
    public function Body($pdf)
    {
        $arAsiento = self::$em->getRepository(FinAsiento::class)->find(self::$codigoAsiento);
        $arAsientosDetalles = self::$em->getRepository(FinAsientoDetalle::class)->formatoAsiento(self::$codigoAsiento);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arAsientosDetalles) {
            foreach ($arAsientosDetalles as $arAsientoDetalle) {
                $pdf->Cell(10, 4, $arAsientoDetalle['codigoAsientoDetallePk'], 1, 0, 'L');
                $pdf->Cell(65, 4, $arAsientoDetalle['codigoCuentaFk'] . ' - ' . substr($arAsientoDetalle['cuenta'],0, 55), 1, 0, 'L');
                $pdf->Cell(20, 4, $arAsientoDetalle['numeroIdentificacion'], 1, 0, 'L');
                $pdf->Cell(35, 4, substr($arAsientoDetalle['nombreCorto'],0, 25), 1, 0, 'L');
                $pdf->Cell(20, 4, number_format($arAsientoDetalle['vrDebito']), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arAsientoDetalle['vrCredito']), 1, 0, 'R');
                $pdf->Cell(20, 4, $arAsientoDetalle['vrBase'], 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 85);
            }
            $pdf->Cell(130, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arAsiento->getVrCredito(),0,'.',','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arAsiento->getVrDebito(),0,'.',','), 1, 0, 'R');
            $pdf->Cell(20, 4, '', 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer()
    {
        $this->SetFont('Arial', '', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>