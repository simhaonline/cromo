<?php

namespace App\Formato\Transporte;

use App\Entity\General\TteConfiguracion;
use App\Entity\Transporte\TteRecaudo;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Estandares;

class Recaudo extends \FPDF {
    public static $em;
    public static $id;

    public function Generar($em, $id) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$id = $id;
        $pdf = new Recaudo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("TteRecaudo$id.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'RELACION RECAUDO DEVOLUCION');
        $arRecaudo = new TteRecaudo();
        $arRecaudo = self::$em->getRepository(TteRecaudo::class)->find(self::$id);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(60, 6, $arRecaudo->getCodigoRecaudoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(70, 6, $arRecaudo->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("CLIENTE:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(60, 5, utf8_decode($arRecaudo->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "CANTIDAD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(70, 5, $arRecaudo->getCantidad(), 1, 'R', 1);

        //linea 3
        $this->SetXY(10, 50);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode(""), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(60, 5, '', 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "TOTAL:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(70, 5, number_format($arRecaudo->getVrTotal()), 1, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(160,4,utf8_decode($arRecaudo->getComentario()),1,'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(6);
        $header = array('GUIA', 'DOC CLIENTE','DESTINATARIO','CIUDAD', 'UND', 'PES', 'VOL', 'DECLARA', 'MANEJO', 'FLETE', 'RECAUDO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(20, 20, 35, 28, 10, 10, 10, 15, 15, 15, 15);
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
        $arGuias = self::$em->getRepository(TteGuia::class)->recaudo(self::$id);
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
                $pdf->Cell(35, 4, substr(utf8_decode($arGuia['nombreDestinatario']),0,20), 1, 0, 'L');
                $pdf->Cell(28, 4, $arGuia['ciudadDestino'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arGuia['unidades'], 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoVolumen'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arGuia['vrRecaudo'], 0, '.', ','), 1, 0, 'R');
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
            $pdf->Cell(103, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(10, 4,  $unidadesTotal, 1, 0,'R');
            $pdf->Cell(10, 4,  $pesoTotal, 1, 0,'R');
            $pdf->Cell(10, 4,  $volumen, 1, 0,'R');
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
}

?>