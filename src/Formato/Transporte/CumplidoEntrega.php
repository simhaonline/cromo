<?php

namespace App\Formato\Transporte;

use App\Entity\General\TteConfiguracion;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteNovedad;
use App\Utilidades\Estandares;

class CumplidoEntrega extends \FPDF {
    public static $em;
    public static $codigoCumplido;

    public function Generar($em, $codigoCumplido) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoCumplido = $codigoCumplido;
        $pdf = new CumplidoEntrega("L");
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CumplidoEntrega$codigoCumplido.pdf", 'D');
    }

    public function Header() {

        $arCumplido = self::$em->getRepository(TteCumplido::class)->find(self::$codigoCumplido);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'RELACION CUMPLIDOS (ENTREGA)', self::$em);
        $arCumplido = new TteCumplido();
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arCumplido->getCodigoCumplidoPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "CLIENTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(103, 6, utf8_decode($arCumplido->getClienteRel()->getNombreCorto()), 1, 0, 'L', 1);

        //linea 2
        $this->SetXY(10, 45);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(30, 5, $arCumplido->getFecha()->format('Y-m-d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 5, "CANTIDAD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(103, 5, $arCumplido->getCantidad(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(163,4,utf8_decode($arCumplido->getComentario()),1,'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('GUIA', 'DOUMENTO','DESTINATARIO','CIUDAD', 'UND', 'PES', 'EMP', 'NOVEDAD', 'COMENTARIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(20, 20, 35, 28, 10, 10, 10, 70, 70);
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
        $arGuias = self::$em->getRepository(TteGuia::class)->cumplido(self::$codigoCumplido);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arGuias) {
            foreach ($arGuias as $arGuia) {
                $arNovedad =  self::$em->getRepository(TteNovedad::class)->findOneBy(array('codigoGuiaFk' => $arGuia['codigoGuiaPk']));
                $novedad = "";
                $novedadComentario = "";
                if($arNovedad) {
                    $novedad = $arNovedad->getNovedadTipoRel()->getNombre();
                    $novedadComentario = $arNovedad->getDescripcion();
                }
                $pdf->Cell(20, 4, $arGuia['codigoGuiaPk'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arGuia['documentoCliente'], 1, 0, 'L');
                $pdf->Cell(35, 4, substr(utf8_decode($arGuia['nombreDestinatario']),0,15), 1, 0, 'L');
                $pdf->Cell(28, 4, substr(utf8_decode($arGuia['ciudadDestino']),0,15), 1, 0, 'L');
                $pdf->Cell(10, 4, $arGuia['unidades'], 1, 0, 'R');
                $pdf->Cell(10, 4, number_format($arGuia['pesoReal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(10, 4, $arGuia['empaqueReferencia'], 1, 0, 'L');
                $pdf->Cell(70, 4, substr($novedad, 0 , 50), 1, 0, 'L');
                $pdf->Cell(70, 4, substr($novedadComentario, 0 , 50), 1, 0, 'L');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);

            }
        }
    }

    public function Footer() {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>