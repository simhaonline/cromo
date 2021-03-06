<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDocumental;
use App\Entity\Transporte\TteRelacionCaja;
use App\Entity\Transporte\TteRecibo;
use App\Utilidades\Estandares;

class RelacionCaja extends \FPDF {
    public static $em;
    public static $codigoRelacionCaja;

    public function Generar($em, $codigoRelacionCaja) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRelacionCaja = $codigoRelacionCaja;
        $pdf = new RelacionCaja();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("RelacionCaja$codigoRelacionCaja.pdf", 'D');
    }

    public function Header() {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);

        Estandares::generarEncabezado($this,'RELACION CAJA', self::$em);
        $arRelacionCaja = self::$em->getRepository(TteRelacionCaja::class)->find(self::$codigoRelacionCaja);

        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(48, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(47, 6, $arRelacionCaja->getCodigoRelacionCajaPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(48, 6, "CANTIDAD:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(47, 6, $arRelacionCaja->getCantidad(), 1, 0, 'R', 1);
        $this->SetXY(10, 46);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(48, 6, utf8_decode("FECHA:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(47, 6, $arRelacionCaja->getFecha()->format('Y-m-d'), 1, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(48, 6, utf8_decode("USUARIO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(47, 6, $arRelacionCaja->getUsuario(), 1, 'R', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'FECHA', 'TIPO', 'GUIA',' NUMERO', 'NUM_FAC','F_ING', 'CLIENTE', 'FLETE', 'MANEJO', 'TOTAL');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 16, 10, 20, 15 , 15, 16, 45, 15, 15, 15);
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
        $arRelacionCaja = self::$em->getRepository(TteRelacionCaja::class)->find(self::$codigoRelacionCaja);
        $arRecibos = self::$em->getRepository(TteRecibo::class)->relacionCaja(self::$codigoRelacionCaja);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arRecibos) {
            foreach ($arRecibos as $arRecibo) {
                $pdf->Cell(10, 4, $arRecibo['codigoReciboPk'], 1, 0, 'L');
                $pdf->Cell(16, 4, $arRecibo['fecha']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(10, 4, $arRecibo['codigoGuiaTipoFk'], 1, 0, 'L');
                $pdf->Cell(20, 4, $arRecibo['codigoGuiaFk'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arRecibo['numero'], 1, 0, 'L');
                $pdf->Cell(15, 4, $arRecibo['numeroFactura'], 1, 0, 'L');
                $pdf->Cell(16, 4, $arRecibo['fechaIngreso']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(45, 4, substr(utf8_decode($arRecibo['clienteNombre']),0,33), 1, 0, 'L');
                $pdf->Cell(15, 4, number_format($arRecibo['vrFlete'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arRecibo['vrManejo'], 0, '.', ','), 1, 0, 'R');
                $pdf->Cell(15, 4, number_format($arRecibo['vrTotal'], 0, '.', ','), 1, 0, 'R');
                $pdf->Ln();
                $pdf->SetAutoPageBreak(true, 15);
            }
            $pdf->Cell(147, 4, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arRelacionCaja->getVrFlete(), 0, '.', ',') , 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arRelacionCaja->getVrManejo(), 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arRelacionCaja->getVrTotal(), 0, '.', ','), 1, 0, 'R');
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