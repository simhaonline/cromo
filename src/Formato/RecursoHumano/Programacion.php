<?php

namespace App\Formato\RecursoHumano;

use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Utilidades\Estandares;
use Doctrine\Common\Persistence\ObjectManager;

class Programacion extends \FPDF
{

    public static $em;
    public static $codigoProgramacion;
    public static $strLetras;

    /**
     * @param $em ObjectManager
     * @param $codigoProgramacion integer
     */
    public function Generar($em, $codigoProgramacion)
    {

        self::$em = $em;
        self::$codigoProgramacion = $codigoProgramacion;
        ob_clean();
        $pdf = new Programacion('P', 'mm', 'letter');
        $arProgramacion = $em->getRepository(RhuProgramacion::class)->find($codigoProgramacion);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arProgramacion->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arProgramacion->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Programacion_$codigoProgramacion.pdf", 'D');
    }

    public function Header()
    {
        /** @var  $arProgramacion RhuProgramacion */
        $arProgramacion = self::$em->getRepository(RhuProgramacion::class)->find(self::$codigoProgramacion);
        Estandares::generarEncabezado($this, 'PROGRAMACION PAGO', self::$em);
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(25, 4, "CODIGO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, $arProgramacion->getCodigoProgramacionPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "NOMBRE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, utf8_decode($arProgramacion->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA PAGADO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(25, 4, $arProgramacion->getFechaPagado() ? $arProgramacion->getFechaPagado()->format('Y/m/d') : 'SIN PAGAR', 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "TIPO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, $arProgramacion->getPagoTipoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "DESDE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arProgramacion->getFechaDesde()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'DIAS:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(25, 4, $arProgramacion->getDias(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "GRUPO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, utf8_decode($arProgramacion->getGrupoRel()->getNombre()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'HASTA:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arProgramacion->getFechaHasta()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'USUARIO:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(25, 4, utf8_decode($arProgramacion->getUsuario()), 1, 0, 'L', 1);


        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(25, 4, "NUMERO PAGOS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(50, 4, $arProgramacion->getCantidad(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "HASTA PERIODO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 4, $arProgramacion->getFechaHastaPeriodo()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'NETO A PAGAR:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(25, 4, number_format($arProgramacion->getVrNeto(), 0, '.', ','), 1, 0, 'L', 1);


        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {

        $this->Ln(14);
        $arPago = new RhuPago();
        $header = array('COD', 'IDENTIFICACION', 'EMPLEADO', 'DEVENGADO', 'DEDUCCION','NETO');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //Creamos la cabecera de la tabla.
        $w = array(15, 30, 79, 23, 23, 20);
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
        $arPagos = self::$em->getRepository(RhuPago::class)->findBy(['codigoProgramacionFk' => self::$codigoProgramacion]);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        /** @var  $arPago RhuPago */
        $vrDeducciones = 0;
        $vrDevengado = 0;
        foreach ($arPagos as $arPago) {
            $pdf->Cell(15, 4, $arPago->getCodigoPagoPk(), 1, 0, 'L');
            $pdf->Cell(30, 4, $arPago->getEmpleadoRel()->getNumeroIdentificacion(), 1, 0, 'L');
            $pdf->Cell(79, 4, utf8_decode($arPago->getEmpleadoRel()->getNombreCorto()), 1, 0, 'L');
            $pdf->Cell(23, 4, number_format($arPago->getVrDevengado(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(23, 4, number_format($arPago->getVrDeduccion(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(20, 4, number_format($arPago->getVrNeto(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            $vrDeducciones += $arPago->getVrDeduccion();
            $vrDevengado += $arPago->getVrDevengado();
        }

        //TOTALES
        $pdf->Ln();
        $pdf->Cell(130, 4, "", 0, 0, 'R');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(35, 4, "TOTAL DEVENGADO:", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($vrDevengado, 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(130, 4, "", 0, 0, 'R');
        $pdf->Cell(35, 4, "TOTAL DEDUDCCIONES:", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($vrDeducciones, 0, '.', ','), 1, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(130, 4, "", 0, 0, 'R');
        $pdf->Cell(35, 4, "NETO PAGAR", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format(self::$em->getRepository(RhuProgramacion::class)->getNeto(self::$codigoProgramacion)['vrNeto'], 0, '.', ','), 1, 0, 'R');
        $pdf->Ln(-8);
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