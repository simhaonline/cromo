<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteRelacionCaja;
use App\Entity\Transporte\TteRecibo;
use App\Utilidades\Estandares;

class NotaCredito extends \FPDF {
    public static $em;
    public static $codigoNotaCredito;
    public static $numeroRegistros;

    public function Generar($em, $codigoNotaCredito) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoNotaCredito = $codigoNotaCredito;
        $pdf = new NotaCredito();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("NotaCredito$codigoNotaCredito.pdf", 'D');
    }

    public function Header() {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);

        Estandares::generarEncabezado($this,'NOTA CREDITO', self::$em);

        $arNotaCredito = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoNotaCredito);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);

        //ENCABEZADO ORDEN DE COMPRA
        $intY = 40;
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, "NUMERO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arNotaCredito->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arNotaCredito->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        //BLOQUE 2
        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "CLIENTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arNotaCredito->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'NIT:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arNotaCredito->getClienteRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        //BLOQUE 3
        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "GUIAS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arNotaCredito->getGuias(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, '', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);
        //
        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "COMENTARIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->MultiCell(160,4,utf8_decode($arNotaCredito->getComentario()),1,'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'GUIA', 'NUMERO', 'DESTINO',  'UND', 'PESO','VOLUMEN', 'DECLARADO', 'FLETE', 'MANEJO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 20, 20, 40, 10, 15, 15, 20, 20 , 20);
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
        $arNotaCreditoDetalle = self::$em->getRepository(TteFacturaDetalle::class)->factura(self::$codigoNotaCredito);
        self::$numeroRegistros = count($arNotaCreditoDetalle);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arNotaCreditoDetalle) {
            $fleteTotal = 0;
            $manejoTotal = 0;
            foreach ($arNotaCreditoDetalle as $arNotasCreditoDetalle) {
                $pdf->Cell(10, 4, $arNotasCreditoDetalle['codigoFacturaDetallePk'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arNotasCreditoDetalle['codigoGuiaFk'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arNotasCreditoDetalle['numero'], 1, 0, 'L');
                $pdf->Cell(40, 4, $arNotasCreditoDetalle['ciudadDestino'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arNotasCreditoDetalle['unidades'], 1, 0, 'R');
                $pdf->Cell(15, 4, $arNotasCreditoDetalle['pesoReal'], 1, 0, 'R');
                $pdf->Cell(15, 4, $arNotasCreditoDetalle['pesoVolumen'], 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arNotasCreditoDetalle['vrDeclara'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arNotasCreditoDetalle['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(20, 4, number_format($arNotasCreditoDetalle['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $fleteTotal += $arNotasCreditoDetalle['vrFlete'];
                $manejoTotal += $arNotasCreditoDetalle['vrManejo'];
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 50);
            }
            $numeroPaginas = ceil(self::$numeroRegistros / 43);
            $arNotaCredito = self::$em->getRepository('App:Transporte\TteFactura')->find(self::$codigoNotaCredito);
            if ($pdf->PageNo() == $numeroPaginas) {
                $pdf->SetTextColor(0);
                $pdf->Ln();
                $pdf->Cell(140, 4, "", 0, 0, 'R');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetFillColor(236, 236, 236);
                $pdf->Cell(25, 4, "FLETE", 1, 0, 'L', true);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(25, 4, number_format($fleteTotal, 2, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetFillColor(236, 236, 236);
                $pdf->Cell(140, 4, "", 0, 0, 'R');
                $pdf->Cell(25, 4, "MANEJO", 1, 0, 'L', true);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 4, number_format($manejoTotal, 2, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetFillColor(236, 236, 236);
                $pdf->Cell(140, 4, "", 0, 0, 'R');
                $pdf->Cell(25, 4, "SUBTOTAL", 1, 0, 'L', true);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 4, number_format($arNotaCredito->getVrSubTotal(), 2, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetFillColor(236, 236, 236);
                $pdf->Cell(140, 4, "", 0, 0, 'R');
                $pdf->Cell(25, 4, "TOTAL", 1, 0, 'L', true);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Cell(25, 4, number_format($arNotaCredito->getVrTotal(), 2, '.', ','), 1, 0, 'R');
            }
        }
    }

    public function Footer() {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>