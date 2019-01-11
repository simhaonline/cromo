<?php

namespace App\Formato\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinRegistro;
use App\Utilidades\Estandares;

class Auxiliar1 extends \FPDF
{
    public static $em;
    public static $imagen;
    public static $extension;

    public function Generar($em)
    {
        ob_clean();
        self::$em = $em;
        $pdf = new Auxiliar1();
        $pdf->AliasNbPages();
        $logo = self::$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
        self::$imagen = "data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen()));
        self::$extension = $logo->getExtension();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Auxiliar.pdf", 'I');
    }

    public function Header()
    {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this, 'LIBRO AUXILIAR', self::$em, self::$imagen, self::$extension);
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(10);
        $header = array('TERCERO', 'FECHA', 'DESCRIPCION', 'DOC', 'DEBITO', 'CREDITO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(60, 20, 40, 30, 20, 20);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        }
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);


    }

    /**
     * @param $pdf
     */
    public function Body($pdf)
    {
        $arRegistros = self::$em->getRepository(FinRegistro::class)->auxiliar()->getQuery()->execute();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $codigoCuenta = 0;
        $vrCreditoAcumulado = 0;
        $vrDebitoAcumulado = 0;
        $cantRegistros = count($arRegistros);
        $registroActual = 0;
        if ($arRegistros) {
            foreach ($arRegistros as $arRegistro) {
                $registroActual++;
                if ($arRegistro['cuenta'] != $codigoCuenta) {
                    if ($codigoCuenta != 0) {
                        $pdf->SetFillColor(200, 200, 200);
                        $pdf->SetFont('Arial', 'B', 7);
                        $pdf->Cell(150, 4, '', 1, 0, 'L', 1);
                        $pdf->Cell(20, 4, number_format($vrDebitoAcumulado, 0, '.', ','), 1, 0, 'R', 1);
                        $pdf->Cell(20, 4, number_format($vrCreditoAcumulado, 0, '.', ','), 1, 0, 'R', 1);
                        $pdf->Ln();
                        $vrCreditoAcumulado = 0;
                        $vrDebitoAcumulado = 0;
                    }
                    $pdf->SetFillColor(220, 220, 220);
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(190, 4, $codigoCuenta . ' - ' . $arRegistro['cuentaNombre'], 1, 0, 'L', 1);
                    $pdf->Ln();
                    $pdf->SetFillColor(224, 235, 255);
                    $pdf->SetTextColor(0);
                    $pdf->SetFont('Arial', '', 7);
                    if ($vrCreditoAcumulado == 0 && $vrDebitoAcumulado == 0) {
                        $vrCreditoAcumulado = $arRegistro['vrCredito'];
                        $vrDebitoAcumulado = $arRegistro['vrDebito'];
                    }
                    $codigoCuenta = $arRegistro['cuenta'];
                } else {
                    $vrCreditoAcumulado += $arRegistro['vrCredito'];
                    $vrDebitoAcumulado += $arRegistro['vrDebito'];
                }
                $pdf->Cell(60, 4, utf8_decode($arRegistro['nombreCorto']), 1, 0, 'L');
                $pdf->Cell(20, 4, $arRegistro['fecha']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(40, 4, $arRegistro['descripcion'], 1, 0, 'L');
                $pdf->Cell(30, 4, $arRegistro['codigoDocumento'], 1, 0, 'L');
                $pdf->Cell(20, 4, number_format($arRegistro['vrDebito'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arRegistro['vrCredito'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 10);
            }
            if ($cantRegistros = $registroActual) {
                $pdf->SetFillColor(200, 200, 200);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(150, 4, '', 1, 0, 'L', 1);
                $pdf->Cell(20, 4, number_format($vrDebitoAcumulado, 0, '.', ','), 1, 0, 'R', 1);
                $pdf->Cell(20, 4, number_format($vrCreditoAcumulado, 0, '.', ','), 1, 0, 'R', 1);
                $pdf->Ln();
            }
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