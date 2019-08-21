<?php

namespace App\Formato\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurSoporteContrato;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Pago extends \FPDF
{

    public static $em;
    public static $codigoPago;
    public static $strLetras;

    /**
     * @param $em ObjectManager
     * @param $codigoPago integer
     */
    public function Generar($em, $codigoPago)
    {

        self::$em = $em;
        self::$codigoPago = $codigoPago;
        ob_clean();
        $pdf = new Pago('P', 'mm', 'letter');
        $arPago = $em->getRepository(RhuPago::class)->find($codigoPago);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arPago->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arPago->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Pago_$codigoPago.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $arPago RhuPago */
        $arPago = self::$em->getRepository(RhuPago::class)->find(self::$codigoPago);
        Estandares::generarEncabezado($this, 'COMPROBANTE DE PAGO DE NOMINA', self::$em);
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, $arPago->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arPago->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "CUENTA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arPago->getEmpleadoRel()->getCuenta(), 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "EMPLEADO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, utf8_decode($arPago->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'IDENTIFICACION:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arPago->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, 'BANCO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, utf8_decode($arPago->getEmpleadoRel()->getBancoRel() ? $arPago->getEmpleadoRel()->getBancoRel()->getNombre() : ''), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "CARGO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, $arPago->getEmpleadoRel()->getCargoRel() ? $arPago->getEmpleadoRel()->getCargoRel()->getNombre() : '', 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "DESDE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arPago->getFechaDesde()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "PENSION:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arPago->getContratoRel()->getEntidadPensionRel() ? $arPago->getContratoRel()->getEntidadPensionRel()->getNombre() : '', 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "HASTA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arPago->getFechaHasta()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "SALUD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arPago->getContratoRel()->getEntidadSaludRel() ? $arPago->getContratoRel()->getEntidadSaludRel()->getNombre() : '', 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(110, 4, utf8_decode($arPago->getComentario()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "SALARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, number_format($arPago->getContratoRel()->getVrSalario(), 0, '.', ','), 1, 0, 'R', 1);


        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {

        $this->Ln(14);
        $header = array('COD', 'CONCEPTO', 'HORAS', 'DIAS', 'VR.HORA', '%', 'DEVENGADO', 'DEDUCCION');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //Creamos la cabecera de la tabla.
        $w = array(15, 80, 12, 10, 25, 6, 21, 21);
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
        $arPago = self::$em->getRepository(RhuPago::class)->find(self::$codigoPago);
        $dateFecha = (new \DateTime('now'));
        $arrDiaSemana = FuncionesController::diasMes($dateFecha,  self::$em->getRepository(TurFestivo::class)->festivos($dateFecha->format('Y-m-') . '01', $dateFecha->format('Y-m-t')));

        $arPagoDetalles = self::$em->getRepository(RhuPagoDetalle::class)->findBy(['codigoPagoFk' => self::$codigoPago]);
        $arProgramaciones = self::$em->getRepository(TurProgramacion::class)->programacionEmpleado($arPago->getCodigoEmpleadoFk(), $arPago->getFechaDesde()->format('Y'), $arPago->getFechaHasta()->format('n'));

        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        /** @var  $arPagoDetalle RhuPagoDetalle */
        foreach ($arPagoDetalles as $arPagoDetalle) {
            $pdf->Cell(15, 4, $arPagoDetalle->getCodigoPagoDetallePk(), 1, 0, 'L');
            $pdf->Cell(80, 4, utf8_decode($arPagoDetalle->getConceptoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(12, 4, $arPagoDetalle->getHoras(), 1, 0, 'R');
            $pdf->Cell(10, 4, $arPagoDetalle->getDias(), 1, 0, 'R');
            $pdf->Cell(25, 4, number_format($arPagoDetalle->getVrHora(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(6, 4, $arPagoDetalle->getPorcentaje(), 1, 0, 'R');
            $pdf->Cell(21, 4, number_format($arPagoDetalle->getVrDevengado(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(21, 4, number_format($arPagoDetalle->getVrDeduccion(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

        //TOTALES
        $pdf->Ln();
        $pdf->Cell(130, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(35, 4, "TOTAL DEVENGADO:", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($arPago->getVrDevengado(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(130, 4, "", 0, 0, 'R');
        $pdf->Cell(35, 4, "TOTAL DEDUDCCIONES:", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($arPago->getVrDeduccion(), 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(130, 4, "", 0, 0, 'R');
        $pdf->Cell(35, 4, "NETO PAGAR", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($arPago->getVrNeto(), 0, '.', ','), 1, 0, 'R');
//        $pdf->Ln(-8);
        $pdf->Ln(8);


        $strDiaPago = $arPago->getFechaDesde()->format("d");
        //primera quicena del mes 1 al 15
        foreach ($arrDiaSemana as $arrDia) {
            if ($strDiaPago <= 15 && $arrDia["dia"] <= 15){
                $pdf->Cell(7, 4, $arrDia["dia"].$arrDia['diaSemana'], 1, 0, 'L');
            }
        }
        //segunda quicena del mes 1 al 16
        foreach ($arrDiaSemana as $arrDia) {
            //dias menores a 15 primera quicena del mes
            if ($strDiaPago > 15 && $arrDia["dia"] > 15){
                $pdf->Cell(7, 4, $arrDia["dia"].$arrDia['diaSemana'], 1, 0, 'L');
            }
        }
        $pdf->Ln();
        if ($strDiaPago <= 15 ){
            foreach ($arProgramaciones as $programacion)
            {
                $pdf->Cell(7, 4, $programacion["dia1"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia2"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia3"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia4"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia5"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia6"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia7"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia8"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia9"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia10"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia11"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia12"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia13"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia14"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia15"], 1, 0, 'L');
            }
        }else{
            foreach ($arProgramaciones as $programacion)
            {
                $pdf->Cell(7, 4, $programacion["dia16"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia17"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia18"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia19"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia20"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia21"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia22"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia23"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia24"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia25"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia26"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia27"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia28"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia29"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia30"], 1, 0, 'L');
                $pdf->Cell(7, 4, $programacion["dia31"]??"", 1, 0, 'L'); //validad si el 31 tiene datos
            }
        }
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