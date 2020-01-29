<?php


namespace App\Formato\Tesoreria;


use App\Entity\Cartera\CarMovimiento;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\General\GenConfiguracion;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Utilidades\Estandares;

class Egreso extends \FPDF
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
        $pdf = new Egreso('P', 'mm', 'letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Body($pdf);
        $pdf->Output("EGRESO.pdf", 'D');
    }

    public function Header()
    {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        Estandares::generarEncabezado($this, 'EGRESO', self::$em);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(8);
    }

    public function Body($pdf)
    {
        $arMovimientos = self::$em->getRepository(TesMovimiento::class)->listaFormatoMasivo(self::$arrDatos);
        $numeroPagos = count($arMovimientos);
        $contador = 1;
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
            $pdf->Cell(66, 6, $arMovimiento['numeroDocumento'], 1, 0, 'L', 1);
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
            $header = array('COD', 'NUM', 'NIT', 'TERCERO','CTA','BANCO', 'CTA','DEBITO', 'CREDITO');
            $pdf->SetFillColor(236, 236, 236);
            $pdf->SetTextColor(0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('', 'B', 7);
            //creamos la cabecera de la tabla.
            $w = array(15, 15, 15, 50,  20, 20, 20,20, 20);
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
            $arMovimientosDetalle = self::$em->getRepository(TesMovimientoDetalle::class)->listaFormato($arMovimiento['codigoMovimientoPk']);
            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 7);
            if ($arMovimientosDetalle) {
                foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
                    $pdf->Cell(15, 4, $arMovimientoDetalle['codigoCuentaPagarTipoFk'], 1, 0, 'L');
                    $pdf->Cell(15, 4, $arMovimientoDetalle['numero'], 1, 0, 'L');
                    $pdf->Cell(15, 4, $arMovimientoDetalle['terceroNumeroIdentificacion'], 1, 0, 'L');
                    $pdf->Cell(50, 4, substr($arMovimientoDetalle['terceroNombreCorto'], 0, 30), 1, 0, 'L');
                    $pdf->Cell(20, 4, $arMovimientoDetalle['cuenta'], 1, 0, 'L');
                    $pdf->Cell(20, 4, $arMovimientoDetalle['banco'], 1, 0, 'L');
                    $pdf->Cell(20, 4, $arMovimientoDetalle['codigoCuentaFk'], 1, 0, 'L');
                    if($arMovimientoDetalle['naturaleza'] == 'D'){
                        $pdf->Cell(20, 4, number_format($arMovimientoDetalle['vrPago'], 0, '.', ','), 1, 0, 'R');
                        $pdf->Cell(20, 4, number_format(0, 0, '.', ','), 1, 0, 'R');
                    }else{
                        $pdf->Cell(20, 4, number_format(0, 0, '.', ','), 1, 0, 'R');
                        $pdf->Cell(20, 4, number_format($arMovimientoDetalle['vrPago'], 0, '.', ','), 1, 0, 'R');
                    }

                    $pdf->Ln();
                }
                //contraPartida
                $pdf->Cell(15, 4, "", 1, 0, 'L');
                $pdf->Cell(15, 4, "", 1, 0, 'L');
                $pdf->Cell(15, 4, "", 1, 0, 'L');
                $pdf->Cell(50, 4, "", 1, 0, 'L');
                $pdf->Cell(20, 4, $arMovimiento['cuenta'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arMovimiento['banco'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arMovimiento['codigoCuentaContableFk'], 1, 0, 'L');
                if($arMovimiento['codigoMovimientoClaseFk'] == 'D'){
                    $pdf->Cell(20, 4, number_format($arMovimientoDetalle['vrPago'], 0, '.', ','), 1, 0, 'R');
                    $pdf->Cell(20, 4, number_format(0, 0, '.', ','), 1, 0, 'R');
                }else{
                    $pdf->Cell(20, 4, number_format(0, 0, '.', ','), 1, 0, 'R');
                    $pdf->Cell(20, 4, number_format($arMovimientoDetalle['vrPago'], 0, '.', ','), 1, 0, 'R');
                }
                $pdf->Ln();
                if ($contador < $numeroPagos) {
                    $pdf->AddPage();
                }
                $contador++;
            }
        }
    }

    public function Footer()
    {

        //$pdf->SetFont('Arial','', 8);
        //$pdf->Text(185, 140, utf8_decode('Página ') . $pdf->PageNo() . ' de {nb}');
    }
}