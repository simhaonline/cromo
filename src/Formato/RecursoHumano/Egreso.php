<?php

namespace App\Formato\RecursoHumano;

use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoDetalle;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Egreso extends \FPDF
{

    public static $em;
    public static $codigoEgreso;
    public static $strLetras;

    /**
     * @param $em ObjectManager
     * @param $codigoEgreso integer
     */
    public function Generar($em, $codigoEgreso)
    {

        self::$em = $em;
        self::$codigoEgreso = $codigoEgreso;
        ob_clean();
        $pdf = new Egreso('P', 'mm', 'letter');
        $arEgreso = $em->getRepository(RhuEgreso::class)->find($codigoEgreso);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arEgreso->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arEgreso->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Egreso_$codigoEgreso.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $arEgreso RhuEgreso */
        $arEgreso = self::$em->getRepository(RhuEgreso::class)->find(self::$codigoEgreso);
        Estandares::generarEncabezado($this, 'EGRESO', self::$em);
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arEgreso->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "BANCO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, utf8_decode($arEgreso->getCuentaRel()->getBancoRel()->getNombre()) , 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "TIPO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, utf8_decode($arEgreso->getEgresoTipoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'REGISTROS:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, $arEgreso->getNumeroRegistros(), 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arEgreso->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "TOTAL:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 4, number_format($arEgreso->getVrTotal(),0,'.',','), 1, 0, 'R', 1);


        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(165, 4, utf8_decode($arEgreso->getComentario()), 1, 0, 'L', 1);


        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {

        $this->Ln(14);
        $header = array('ID', 'PAGO','NUMERO', 'IDENTIFICACION', 'NOMBRE', 'BANCO', 'CUENTA', 'VR PAGO');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //Creamos la cabecera de la tabla.
        $w = array(10,10 ,15, 30, 60, 25, 20, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1) {
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            } else {
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            }

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);

    }

    public function Body($pdf)
    {
        /** @var  $arPago RhuPago */
        $arEgresoDetalles = self::$em->getRepository(RhuEgresoDetalle::class)->findBy(['codigoEgresoFk' => self::$codigoEgreso]);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $vrTotal = 0;
        /** @var  $arEgresoDetalle RhuEgresoDetalle */
        foreach ($arEgresoDetalles as $arEgresoDetalle) {
            $vrTotal += $arEgresoDetalle->getVrPago();
            $pdf->Cell(10, 4, $arEgresoDetalle->getCodigoEgresoDetallePk(), 1, 0, 'L');
            $pdf->Cell(10, 4, $arEgresoDetalle->getCodigoPagoFk(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arEgresoDetalle->getPagoRel()->getNumero(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arEgresoDetalle->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(60, 4, strtoupper(utf8_decode($arEgresoDetalle->getEmpleadoRel()->getNombreCorto())), 1, 0, 'L');
            $pdf->Cell(25, 4, strtoupper(utf8_decode($arEgresoDetalle->getBancoRel() ? $arEgresoDetalle->getBancoRel()->getNombre() : '')), 1, 0, 'L');
            $pdf->Cell(20, 4, $arEgresoDetalle->getCuenta(), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arEgresoDetalle->getVrPago(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(170, 4, 'TOTAL', 1, 0, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(20, 4, number_format($vrTotal,0,'.',','), 1, 0, 'R');

    }

    public function Footer()
    {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
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