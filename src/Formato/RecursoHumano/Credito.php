<?php

namespace App\Formato\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Credito extends \FPDF
{
    public static $em;
    public static $codigoCredito;

    public function Generar($em, $codigoCredito)
    {
        self::$em = $em;
        self::$codigoCredito = $codigoCredito;
        ob_clean();
        $pdf = new Credito('P', 'mm', 'letter');
        $arCredito = $em->getRepository(RhuCredito::class)->find($codigoCredito);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arCredito->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arCredito->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Credito_$codigoCredito.pdf", 'D');

    }

    public function Header()
    {
        /**
         * @var $arCredito RhuCredito
         */
        $arCredito = self::$em->getRepository(RhuCredito::class)->find(self::$codigoCredito);
        Estandares::generarEncabezado($this, 'INFORMACION DEL CREDITO', self::$em);
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 4, "CODIGO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arCredito->getCodigoCreditoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, $arCredito->getFecha()->format('Y-m-d') , 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "EMPLEADO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arCredito->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'TIPO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, $arCredito->getCreditoTipoRel()->getNombre(), 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "CUENTA", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arCredito->getEmpleadoRel()->getCuenta(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "BANCO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, $arCredito->getEmpleadoRel()->getBancoRel()->getNombre(), 1, 0, 'R', 1);


        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "CREDITO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, number_format($arCredito->getVrCredito(),0,'.',','), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "VALOR CUOTA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, number_format($arCredito->getVrCuota(),0,'.',','), 1, 0, 'R', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "SALDO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4,number_format($arCredito->getVrSaldo(),0,'.',','), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "PAGADO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, $arCredito->getEstadoPagado() ? 'SI' : 'NO', 1, 0, 'R', 1);

        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "NUM CUOTAS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arCredito->getNumeroCuotas(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "CUOTA ACTUAL:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, $arCredito->getNumeroCuotaActual(), 1, 0, 'R', 1);

        $this->SetXY(10, $intY + 24);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "TIPO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arCredito->getCreditoTipoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "SUSPENDIDO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, $arCredito->getEstadoSuspendido() ? 'SI' : 'NO', 1, 0, 'R', 1);

        $this->SetXY(10, $intY + 28);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(165, 4, $arCredito->getComentario(), 1, 0, 'L', 1);


        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(8);
        $header = array('ID', 'TIPO PAGO','FECHA PAGO', 'VR CUOTA');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('Arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 50, 20, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);

        //Restauraci�n de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf)
    {
        /**
         * @var $arCreditoPago RhuCreditoPago
         */
        $arCreditoPagos = self::$em->getRepository(RhuCreditoPago::class)->findBy(['codigoCreditoFk' => self::$codigoCredito]);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $vrTotal = 0;
        foreach ($arCreditoPagos as $arCreditoPago) {
            $vrTotal += $arCreditoPago->getVrPago();
            $pdf->Cell(10, 4, $arCreditoPago->getCodigoCreditoPagoPk(), 1, 0, 'L');
            $pdf->Cell(50, 4, $arCreditoPago->getCreditoPagoTipoRel()->getNombre(), 1, 0, 'L');
            $pdf->Cell(20, 4, $arCreditoPago->getFechaPago()->format('Y-m-d'), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arCreditoPago->getVrPago(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(80, 4, 'TOTAL', 1, 0, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(20, 4, number_format($vrTotal,0,'.',','), 1, 0, 'R');
    }

    public function Footer()
    {
        $this->SetXY(235, 190);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 35, utf8_decode('   Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'L', 0);
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


