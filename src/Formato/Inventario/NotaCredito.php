<?php

namespace App\Formato\Inventario;

use App\Entity\General\GenConfiguracion;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
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

        $arNotaCredito = self::$em->getRepository(InvMovimiento::class)->find(self::$codigoNotaCredito);
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
        $this->Cell(65, 4, $arNotaCredito->getTerceroRel()->getNombreCorto(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, 'NIT:', 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arNotaCredito->getTerceroRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        //BLOQUE 3
        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 4, "SOPORTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(65, 4, $arNotaCredito->getSoporte(), 1, 0, 'L', 1);
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
        $this->MultiCell(160,4,utf8_decode($arNotaCredito->getComentarios()),1,'L');

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'FACTURA', 'ITEM', 'LOTE',  'CANT', 'PRECIO', 'IVA') ;
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 20, 70, 30, 10, 25, 25);
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
        $arMovimiento = self::$em->getRepository('App:Inventario\InvMovimiento')->find(self::$codigoNotaCredito);
        $arNotaCreditoDetalle = self::$em->getRepository(InvMovimientoDetalle::class)->detallesFormato(self::$codigoNotaCredito);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arNotaCreditoDetalle) {
            foreach ($arNotaCreditoDetalle as $arNotasCreditoDetalle) {
                $pdf->Cell(10, 4, $arNotasCreditoDetalle['codigoMovimientoDetallePk'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arNotasCreditoDetalle['numeroFactura'], 1, 0, 'L');
                $pdf->Cell(70, 4, utf8_decode($arNotasCreditoDetalle['nombre']), 1, 0, 'L');
                $pdf->Cell(30, 4, $arNotasCreditoDetalle['loteFk'], 1, 0, 'L');
                $pdf->Cell(10, 4, $arNotasCreditoDetalle['cantidad'], 1, 0, 'C');
                $pdf->Cell(25, 4, number_format($arNotasCreditoDetalle['vrSubtotal']), 1, 0, 'R');
                $pdf->Cell(25, 4, number_format($arNotasCreditoDetalle['vrIva']), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 50);
            }

            $pdf->SetFont('Arial', '', 7);
            //TOTALES
            $pdf->Ln(2);
            $pdf->Cell(145, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetFillColor(236, 236, 236);
            $pdf->Cell(20, 4, "SUBTOTAL:", 1, 0, 'R', true);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrSubtotal(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(145, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(20, 4, "DESCUENTO:", 1, 0, 'R', true);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrDescuento(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(145, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(20, 4, "IVA:", 1, 0, 'R', true);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrIva(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(145, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(20, 4, "RTE FUENTE:", 1, 0, 'R', true);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrRetencionFuente(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->Cell(145, 4, "", 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(20, 4, "NETO PAGAR", 1, 0, 'R', true);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(25, 4, number_format($arMovimiento->getVrNeto(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln(-8);
        }
    }

    public function Footer() {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>