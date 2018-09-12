<?php

namespace App\Formato\Transporte;

use App\Entity\General\TteConfiguracion;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteRecaudoCobro;
use App\Utilidades\Estandares;

class RecaudoCobro extends \FPDF {
    public static $em;
    public static $id;

    public function Generar($em, $id) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$id = $id;
        $pdf = new RecaudoCobro();
        $arRecaudoCobro = $em->getRepository(TteRecaudoCobro::class)->find($id);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 40);
        $pdf->SetTextColor(255, 220, 220);
        if ($arRecaudoCobro->getEstadoAnulado()) {
            $pdf->RotatedText(90, 150, 'ANULADO', 45);
        } elseif (!$arRecaudoCobro->getEstadoAprobado()) {
            $pdf->RotatedText(90, 150, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("TteRecaudoCobro$id.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'RELACION RECAUDO COBRO');
        $arRecaudoCobro = new TteRecaudoCobro();
        $arRecaudoCobro = self::$em->getRepository(TteRecaudoCobro::class)->find(self::$id);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 6, $arRecaudoCobro->getCodigoRecaudoCobroPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 6, $arRecaudoCobro->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("USUARIO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(60, 5, utf8_decode($arRecaudoCobro->getUsuario()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "CANTIDAD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(70, 5, $arRecaudoCobro->getCantidad(), 1, 'R', 1);

        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("SOPORTE:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(60, 5, utf8_decode($arRecaudoCobro->getSoporte()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "TOTAL:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(70, 5, number_format($arRecaudoCobro->getVrTotal()), 1, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(160,4,utf8_decode($arRecaudoCobro->getComentario()),1,'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(6);
        $header = array('GUIA','NUMERO', 'FECHA', 'DOC CLIENTE','CLIENTE','CIUDAD', 'UND', 'DECLARA', 'MANEJO', 'FLETE', 'RECAUDO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 16, 15, 20, 40, 20, 10, 15, 15, 15, 15);
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
        $arGuias = self::$em->getRepository(TteGuia::class)->recaudoCobro(self::$id);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            $numeroGuias = count($arGuias);
            $indiceGuia = 0;
            $imprimirTotalGrupo = false;
            $unidades = 0;
            $unidadesTotal = 0;
            $pesoTotal = 0;
            $declaraTotal = 0;
            $volumenTotal = 0;
            $manejoTotal = 0;
            $recaudoTotal = 0;
            $fleteTotal = 0;
            foreach ($arGuias as $arGuia) {
                $pdf->Cell(15, 4, $arGuia['codigoGuiaPk'], 1, 0, 'L');
                $pdf->Cell(16, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['fechaIngreso']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(20, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(40, 4, substr($arGuia['clienteNombreCorto'],0, 22), 1, 0, 'L');
                $pdf->Cell(20, 4, substr($arGuia['ciudadDestino'],0, 14), 1, 0, 'L');
                $pdf->Cell(10, 4, $arGuia['unidades'], 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrRecaudo'], 0, '.', ','), 1, 0, 'R');
                $unidades += $arGuia['unidades'];
                $unidadesTotal += $arGuia['unidades'];
                $pesoTotal += $arGuia['pesoReal'];
                $volumenTotal += $arGuia['pesoVolumen'];
                $declaraTotal += $arGuia['vrDeclara'];
                $manejoTotal += $arGuia['vrManejo'];
                $fleteTotal += $arGuia['vrFlete'];
                $recaudoTotal += $arGuia['vrRecaudo'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);

                $indiceGuia++;
                if($imprimirTotalGrupo) {
                    $pdf->Cell(163, 4, '', 1, 0, 'L');
                    $pdf->Cell(10, 4, $unidades, 1, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetAutoPageBreak(true, 15);
                    $unidades = 0;
                    $peso = 0;
                    $volumen = 0;
                }

            }
            $pdf->Cell(126, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(10, 4,  $unidadesTotal, 1, 0,'R');
            $pdf->Cell(15, 4, number_format($declaraTotal,0,'.',','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($manejoTotal,0,'.',','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($fleteTotal,0,'.',','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($recaudoTotal,0,'.',','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
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