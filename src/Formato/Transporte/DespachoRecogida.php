<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteRecogida;
use App\Utilidades\Estandares;

class DespachoRecogida extends \FPDF {
    public static $em;
    public static $codigoDespacho;

    public function Generar($em, $codigoDespacho) {
        ob_clean();
        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $pdf = new DespachoRecogida();
        $arDespachoRecogida = $em->getRepository(TteDespachoRecogida::class)->find($codigoDespacho);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arDespachoRecogida->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arDespachoRecogida->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("DespachoRecogida$codigoDespacho.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'DESPACHO RECOGIDA', self::$em);
        $arDespacho = new TteDespacho();
        $arDespacho = self::$em->getRepository(TteDespachoRecogida::class)->find(self::$codigoDespacho);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("CONDUCTOR:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, utf8_decode($arDespacho->getConductorRel()->getNombreCorto()) , 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, 'FLETE:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arDespacho->getVrFletePago()) , 1, 0, 'R', 1);
        //linea 2
        $this->SetXY(10, 46);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arDespacho->getFecha()->format('Y-m-d')  , 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "ANTICIPO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6,  number_format($arDespacho->getVrAnticipo()) , 1, 0, 'R', 1);
        //linea 3
        $this->SetXY(10, 52);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'NUMERO', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 6, $arDespacho->getNumero() , 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "IND COM::", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6,number_format($arDespacho->getVrIndustriaComercio()), 1, 0, 'R', 1);
        //linea 3
        $this->SetXY(10, 58);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, '', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','', 8);
        $this->Cell(66, 6, '' , 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "RTE FUENTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arDespacho->getVrRetencionFuente()), 1, 0, 'R', 1);

        $this->SetXY(10, 64);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, '', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','', 8);
        $this->Cell(66, 6, '' , 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "ESTAMPILLA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arDespacho->getVrDescuentoEstampilla()), 1, 0, 'R', 1);

        $this->SetXY(10, 70);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, '', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial','', 8);
        $this->Cell(66, 6, '' , 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "SALDO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 6, number_format($arDespacho->getVrSaldo()), 1, 0, 'R', 1);
        //linea 4
        //linea 4
        $this->SetXY(10, 76);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, 'COMENTARIOS:', 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(161, 6,  utf8_decode($arDespacho->getComentario()) , 1, 0, 'L', 1);


        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('GUIA', 'FECHA','CLIENTE', 'UND', 'PESO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(16, 25, 100, 10, 10);
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
        $arRecogidas = self::$em->getRepository(TteRecogida::class)->listaFormato(self::$codigoDespacho);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arRecogidas) {
            foreach ($arRecogidas as $arRecogida) {
                $pdf->Cell(16, 4, $arRecogida['codigoRecogidaPk'], 1, 0, 'L');
                $pdf->Cell(25, 4, $arRecogida['fecha']->format('Y-m-d H:i'), 1, 0, 'L');
                $pdf->Cell(100, 4, substr($arRecogida['clienteNombreCorto'],0,100), 1, 0, 'L');
                $pdf->Cell(10, 4, number_format($arRecogida['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arRecogida['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        //linea 1
        $this->SetXY(10, 212);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 6, utf8_decode("CLIENTE"), 1, 0, 'C', 1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(70, 6, utf8_decode("DIRECCION"), 1, 0, 'C', 1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("UNIDADES"), 1, 0, 'C', 1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode("FIRMA CLIENTE"), 1, 0, 'C', 1);
        $this->SetXY(10, 218);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(70, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetXY(10, 224);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(70, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetXY(10, 230);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(70, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetXY(10, 236);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(70, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetXY(10, 242);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(50, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(70, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode(''), 1, 0, 'C', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode(''), 1, 0, 'C', 1);

        $this->SetFont('Arial','', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

        $this->Text(10, 260, "______________________________________________");
        $this->Text(10, 264, "DESPACHADO POR");
        $this->Text(10, 268, "C.C:");

        $this->Text(120, 260, "______________________________________________");
        $this->Text(120, 264, "CONDUCTOR");
        $this->Text(120, 268, "C.C:");

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