<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\TteConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Estandares;

class Despacho extends \FPDF {
    public static $em;
    public static $codigoDespacho;



    public function Generar($em, $codigoDespacho) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();

        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $pdf = new Despacho();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $arDespacho = self::$em->getRepository(TteDespacho::class)->find(self::$codigoDespacho);
        $pdf->SetFont('Arial', '', 30);
        $pdf->SetTextColor(255, 220, 220);
        if ($arDespacho->getEstadoAprobado() != true){
            $pdf->RotatedText(50, 200, 'SIN APROBAR', 45);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("TteDespacho$codigoDespacho.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'ORDEN DE DESPACHO', self::$em);
        $arDespacho = new TteDespacho();
        $arDespacho = self::$em->getRepository(TteDespacho::class)->find(self::$codigoDespacho);
        $this->Image('../public/img/recursos/transporte/supertransporte.png', 13, 1, 30, 20);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(5, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arDespacho->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CONDUCTOR:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 6, utf8_decode($arDespacho->getConductorRel() ? $arDespacho->getConductorRel()->getNombreCorto()  : ''), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(5, 45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("CODIGO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arDespacho->getCodigoDespachoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "VEHICULO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 5, $arDespacho->getCodigoVehiculoFk(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 7);
        //linea 3
        $this->SetXY(5, 50);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arDespacho->getFechaRegistro()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "TENEDOR:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 5,utf8_decode($arDespacho->getPoseedorRel() ? $arDespacho->getPoseedorRel()->getNumeroIdentificacion()."-".$arDespacho->getPoseedorRel()->getNombreCorto()  : ''), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 7);
        //linea 4
        $this->SetXY(5, 55);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("TIPO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arDespacho->getDespachoTipoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 5, "", 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 7);

        $this->SetXY(5, 60);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("RUTA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arDespacho->getRutaRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "CANTIDAD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 5, $arDespacho->getCantidad(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 7);

        //linea 5
        $this->SetXY(5, 65);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("COMENTARIO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(163, 6, $arDespacho->getComentario(), 1, 0, 'L', 1);

        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $this->SetX(5);
        $header = array('TP', 'GUIA', 'FECHA', 'SER', 'NUMERO', 'DOC', 'CLIENTE','DESTINATARIO', 'DIRECCION', 'EMP', 'UND', 'PES');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(8, 15, 10, 7, 15, 18, 30, 34, 36, 10, 10, 10);
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
        $arGuias = self::$em->getRepository(TteDespachoDetalle::class)->imprimirDetalles(self::$codigoDespacho);
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 6.9);
        if($arGuias) {
            $numeroGuias = count($arGuias);
            $indiceGuia = 0;
            $imprimirTotalGrupo = false;
            $unidades = 0;
            $peso = 0;
            $volumen = 0;
            $unidadesTotal = 0;
            $pesoTotal = 0;
            $volumenTotal = 0;
            foreach ($arGuias as $arGuia) {
                $pdf->SetX(5);
                $pdf->Cell(8, 4, $arGuia['codigoGuiaTipoFk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['codigoGuiaFk'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arGuia['fechaIngreso']->format('m-d'), 1, 0, 'L');
                $pdf->Cell(7, 4, $arGuia['codigoServicioFk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(18, 4, substr($arGuia['documentoCliente'], 0 ,10), 1, 0, 'L');
                $pdf->Cell(30, 4, substr(utf8_decode($arGuia['clienteNombre']),0,18), 1, 0, 'L');
                $pdf->Cell(34, 4, substr(utf8_decode($arGuia['nombreDestinatario']),0,19), 1, 0, 'L');
                $pdf->Cell(36, 4, substr(utf8_decode($arGuia['direccionDestinatario']),0,25), 1, 0, 'L');
                $pdf->Cell(10, 4, substr($arGuia['empaqueReferencia'],0, 6), 1, 0, 'L');
                $pdf->Cell(10, 4, number_format($arGuia['unidades'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $unidades += $arGuia['unidades'];
                $peso += $arGuia['pesoReal'];
                $volumen += $arGuia['pesoVolumen'];
                $unidadesTotal += $arGuia['unidades'];
                $pesoTotal += $arGuia['pesoReal'];
                $volumenTotal += $arGuia['pesoVolumen'];
                $pdf->Ln();
                $pdf->SetX(5);
                $pdf->SetAutoPageBreak(true, 45);

                $indiceGuia++;
                if($indiceGuia < $numeroGuias) {
                    if($arGuias[$indiceGuia-1]['codigoCiudadDestinoFk'] != $arGuias[$indiceGuia]['codigoCiudadDestinoFk']) {
                        $imprimirTotalGrupo = true;
                    } else {
                        $imprimirTotalGrupo = false;
                    }
                } else {
                    $imprimirTotalGrupo = true;
                }
                if($imprimirTotalGrupo) {
                    $pdf->SetX(5);
                    $pdf->Cell(183, 4, "TOTAL CIUDAD: ". $arGuia['ciudadDestino'], 1, 0, 'L');
                    $pdf->Cell(10, 4, $unidades, 1, 0, 'R');
                    $pdf->Cell(10, 4, $peso, 1, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetAutoPageBreak(true, 15);
                    $unidades = 0;
                    $peso = 0;
                    $volumen = 0;
                }

            }
            $pdf->SetX(5);
            $pdf->Cell(183, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(10, 4, $unidadesTotal, 1, 0, 'R');
            $pdf->Cell(10, 4, $pesoTotal, 1, 0, 'R');
            $pdf->SetX(5);
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

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
            $angle *= M_PI / 190;
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