<?php

namespace App\Formato\Transporte;

use App\Entity\General\TteConfiguracion;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteDocumental;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Estandares;

class Documental extends \FPDF {
    public static $em;
    public static $codigoDocumental;

    public function Generar($em, $codigoDocumental) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDocumental = $codigoDocumental;
        $pdf = new Documental();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("TteDocumental$codigoDocumental.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'RELACION DOCUMENTAL', self::$em);
        $arDocumental = new TteDocumental();
        $arDocumental = self::$em->getRepository(TteDocumental::class)->find(self::$codigoDocumental);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(33, 6, $arDocumental->getCodigoDocumentalPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(35, 6, $arDocumental->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("CANTIDAD:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(35, 6, $arDocumental->getCantidad(), 1, 'R', 1);


        //linea 2
        $this->SetXY(10, 46);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(163,6,utf8_decode($arDocumental->getComentario()),1,'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('GUIA', 'DOC CLIENTE','DESTINATARIO','CIUDAD', 'UND', 'PES', 'VOL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(20, 20, 65, 58, 10, 10, 10);
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
        /**
         * @var $arGuias TteGuia
         */
        $arGuias = self::$em->getRepository(TteGuia::class)->documental(self::$codigoDocumental);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            $numeroGuias = count($arGuias);
            $indiceGuia = 0;
            $imprimirTotalGrupo = false;
            $unidades = 0;
            $peso = 0;
            $volumen = 0;
            $unidadesTotal = 0;
            $pesoTotal = 0;
            $declaraTotal = 0;
            $volumenTotal = 0;
            $manejoTotal = 0;
            $recaudoTotal = 0;
            $fleteTotal = 0;
            foreach ($arGuias as $arGuia) {
                $pdf->Cell(20, 4, $arGuia['numero'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(65, 4, substr(utf8_decode($arGuia['nombreDestinatario']),0,20), 1, 0, 'L');
                $pdf->Cell(58, 4, $arGuia['ciudadDestino'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arGuia['unidades'], 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoVolumen'], 0, '.', ','), 1, 0, 'R');
                $unidades += $arGuia['unidades'];
                $peso += $arGuia['pesoReal'];
                $volumen += $arGuia['pesoVolumen'];
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
                    $pdf->Cell(10, 4, $peso, 1, 0, 'R');
                    $pdf->Cell(10, 4, $volumen, 1, 0, 'R');
                    $pdf->Ln();
                    $pdf->SetAutoPageBreak(true, 15);
                    $unidades = 0;
                    $peso = 0;
                    $volumen = 0;
                }

            }
            $pdf->Cell(163, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(10, 4,  $unidadesTotal, 1, 0,'R');
            $pdf->Cell(10, 4,  $pesoTotal, 1, 0,'R');
            $pdf->Cell(10, 4,  $volumen, 1, 0,'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>