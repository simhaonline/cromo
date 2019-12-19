<?php


namespace App\Formato\Cartera;


use App\Entity\Cartera\CarMovimiento;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\General\GenConfiguracion;
use App\Utilidades\Estandares;

class ReciboMasivo extends \FPDF
{
    public static $em;
    public static $arrDatos;

    public function Generar($em, $arrDatos)
    {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        self::$em = $em;
        self::$arrDatos = $arrDatos;
        $pdf = new ReciboMasivo('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Body($pdf);
        $pdf->Output("RECIBO.pdf", 'D');
    }

    public function Header()
    {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        Estandares::generarEncabezado($this, 'RECIBO', self::$em);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {

    }

    public function Body($pdf)
    {
        $arMovimientos = self::$em->getRepository(CarMovimiento::class)->listaFormatoMasivo(self::$arrDatos);
        foreach ($arMovimientos as $arMovimiento) {
            //linea 1
            $pdf->SetXY(10, 40);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 6, utf8_decode("TERCERO:"), 1, 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(66, 6, utf8_decode($arMovimiento['nombreCorto']), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 6, 'NUMERO:', 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(65, 6, $arMovimiento['numero'], 1, 0, 'R', 1);
            //linea 2
            $pdf->SetXY(10, 46);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 6, utf8_decode("IDENTIFICACION:"), 1, 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(66, 6, $arMovimiento['numeroIdentificacion'], 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(65, 6, $arMovimiento['fecha']->format('Y-m-d'), 1, 0, 'L', 1);
            //linea 3
            $pdf->SetXY(10, 52);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 6, 'DIRECCION', 1, 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(66, 6, utf8_decode($arMovimiento['direccion']), 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 6, "TOTAL NETO:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(65, 6, number_format($arMovimiento['vrTotalNeto']), 1, 0, 'L', 1);
            //linea 4
            $pdf->SetXY(10, 58);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 6, 'TELEFONO', 1, 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(66, 6, $arMovimiento['telefono'], 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 6, "", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(65, 6, "", 1, 0, 'R', 1);

            //linea 7
            $pdf->SetXY(10, 64);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 6, 'NUM DOCUMENTO', 1, 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(66, 6, $arMovimiento['numero'], 1, 0, 'L', 1);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 6, "", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->Cell(65, 6, '', 1, 0, 'R', 1);
            //linea 8
            $pdf->SetXY(10, 70);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(200, 200, 200);
            $pdf->Cell(30, 4, "COMENTARIOS:", 1, 0, 'L', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->MultiCell(161, 4, utf8_decode($arMovimiento['comentarios']), 1, 'L');

            $pdf->Ln(12);
            $header = array('ID', 'TIPO', 'DOC', 'NIT', 'TERCERO', 'CTA', 'N', 'VALOR');
            $pdf->SetFillColor(236, 236, 236);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('', 'B', 7);
            //creamos la cabecera de la tabla.
            $w = array(15, 20, 20, 25, 60, 20, 5, 20);
            for ($i = 0; $i < count($header); $i++)
                if ($i == 0 || $i == 1)
                    $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
                else
                    $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            //Restauración de colores y fuentes
            $pdf->SetFillColor(224, 235, 255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');
            $pdf->Ln(4);
            $arMovimientosDetalle = self::$em->getRepository(CarMovimientoDetalle::class)->listaFormato($arMovimiento['codigoMovimientoPk']);
            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 7);
            if ($arMovimientosDetalle) {
                foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
                    $pdf->Cell(15, 4, $arMovimientoDetalle['codigoMovimientoDetallePk'], 1, 0, 'L');
                    $pdf->Cell(20, 4, $arMovimientoDetalle['codigoCuentaCobrarTipoFk'], 1, 0, 'L');
                    $pdf->Cell(20, 4, $arMovimientoDetalle['numero'], 1, 0, 'L');
                    $pdf->Cell(25, 4, $arMovimientoDetalle['clienteNumeroIdentificacion'], 1, 0, 'L');
                    $pdf->Cell(60, 4, substr($arMovimientoDetalle['clienteNombreCorto'], 0, 30), 1, 0, 'L');
                    $pdf->Cell(20, 4, $arMovimientoDetalle['codigoCuentaFk'], 1, 0, 'L');
                    $pdf->Cell(5, 4, $arMovimientoDetalle['naturaleza'], 1, 0, 'L');
                    $pdf->Cell(20, 4, number_format($arMovimientoDetalle['vrPago'], 0, '.', ','), 1, 0, 'R');
                    $pdf->Ln();
                }
                $pdf->AddPage();

            }
        }
    }

    public function Footer()
    {

        //$pdf->SetFont('Arial','', 8);
        //$pdf->Text(185, 140, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}');
    }
}