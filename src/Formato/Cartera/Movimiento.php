<?php

namespace App\Formato\Cartera;


use App\Entity\Cartera\CarIngreso;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Cartera\CarMovimiento;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Utilidades\Estandares;

class Movimiento extends \FPDF
{
    public static $em;
    public static $codigoMovimiento;

    public function Generar($em, $codigoMovimiento)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoMovimiento = $codigoMovimiento;
        $pdf = new Movimiento();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $arMovimiento = $em->getRepository(CarMovimiento::class)->find($codigoMovimiento);
        $pdf->SetTextColor(255, 220, 220);
        if ($arMovimiento->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arMovimiento->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Movimiento$codigoMovimiento.pdf", 'D');
    }

    public function Header()
    {
        /**
         * @var $arMovimiento CarMovimiento
         */
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this, 'RECIBO CAJA', self::$em);
        $arMovimiento = self::$em->getRepository(CarMovimiento::class)->find(self::$codigoMovimiento);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("TERCERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, utf8_decode($arMovimiento->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, 'NUMERO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arMovimiento->getNumero(), 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, 46);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("IDENTIFICACION:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arMovimiento->getClienteRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, $arMovimiento->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        //linea 3
        $this->SetXY(10, 52);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'DIRECCION', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arMovimiento->getClienteRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "TOTAL NETO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arMovimiento->getVrTotalNeto()), 1, 0, 'L', 1);
        //linea 4
        $this->SetXY(10, 58);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'TELEFONO', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arMovimiento->getClienteRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, "", 1, 0, 'R', 1);

        //linea 7
        $this->SetXY(10, 64);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'NUM DOCUMENTO', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arMovimiento->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, '', 1, 0, 'R', 1);
        //linea 8
        $this->SetXY(10, 70);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIOS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(161, 4, utf8_decode($arMovimiento->getComentarios()), 1, 'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(12);
        $header = array('COD', 'NUM', 'NIT', 'TERCERO','CTA','BANCO', 'CTA','DEBITO', 'CREDITO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 15, 15, 50,  20, 20, 20,20, 20);
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

    public function Body($pdf)
    {
        $arMovimientosDetalle = self::$em->getRepository(CarMovimientoDetalle::class)->listaFormato(self::$codigoMovimiento);
        $arMovimiento = self::$em->getRepository(CarMovimiento::class)->find(self::$codigoMovimiento );
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if ($arMovimientosDetalle) {
            foreach ($arMovimientosDetalle as $arMovimientoDetalle) {
                $pdf->Cell(15, 4, $arMovimientoDetalle['codigoCuentaCobrarTipoFk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arMovimientoDetalle['numero'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arMovimientoDetalle['clienteNumeroIdentificacion'], 1, 0, 'L');
                $pdf->Cell(50, 4, substr($arMovimientoDetalle['clienteNombreCorto'], 0, 30), 1, 0, 'L');
                $pdf->Cell(20, 4, $arMovimientoDetalle['cuenta'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arMovimientoDetalle['banco'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arMovimientoDetalle['codigoCuentaPk'], 1, 0, 'L');
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
            $pdf->Cell(20, 4, $arMovimiento->getCuentaRel()->getCuenta(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arMovimiento->getCuentaRel()->getBancoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arMovimiento->getCuentaRel()->getcodigoCuentaPk(), 1, 0, 'L');
            if($arMovimientoDetalle['naturaleza'] == 'D'){
                $pdf->Cell(20, 4, number_format($arMovimientoDetalle['vrPago'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format(0, 0, '.', ','), 1, 0, 'R');
            }else{
                $pdf->Cell(20, 4, number_format(0, 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arMovimientoDetalle['vrPago'], 0, '.', ','), 1, 0, 'R');
            }
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    var $angle = 0;

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }

}

?>