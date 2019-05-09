<?php

namespace App\Formato\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteMonitoreoDetalle;
use App\Entity\Transporte\TteRelacionCaja;
use App\Entity\Transporte\TteRecibo;
use App\Utilidades\Estandares;

class Monitoreo extends \FPDF {

    public static $em;
    public static $codigoMonitoreo;

    public function Generar($em, $codigoMonitoreo) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoMonitoreo = $codigoMonitoreo;
        $pdf = new Monitoreo();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Monitoreo$codigoMonitoreo.pdf", 'D');
    }

    public function Header() {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);

        Estandares::generarEncabezado($this,'MONITOREO', self::$em);
        $arMonitoreo = self::$em->getRepository(TteMonitoreo::class)->find(self::$codigoMonitoreo);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(10, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode("CODIGO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, $arMonitoreo->getCodigoMonitoreoPk(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 6, "FECHA INICIO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 6, $arMonitoreo->getFechaRegistro()->format('d-m-Y'), 1, 0, 'L', 1);
        $this->Ln();
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode("VEHICULO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, $arMonitoreo->getVehiculoRel()->getPlaca(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 6, "FECHA FIN:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 6, $arMonitoreo->getFechaRegistro()->format('d-m-Y'), 1, 0, 'L', 1);
        $this->Ln();
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode("CONDUCTOR:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, utf8_decode($arMonitoreo->getCodigoDespachoFk() ?  $arMonitoreo->getDespachoRel()->getConductorRel()->getNombreCorto() : $arMonitoreo->getDespachoRecogidaRel()->getConductorRel()->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 6, "DESTINO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 6, utf8_decode($arMonitoreo->getCodigoDespachoFk() ? $arMonitoreo->getDespachoRel()->getCiudadDestinoRel()->getNombre() : $arMonitoreo->getDespachoRecogidaRel()->getCiudadRel()->getNombre()), 1, 0, 'L', 1);
        $this->Ln();
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40, 6, utf8_decode("TIPO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(70, 6, utf8_decode($arMonitoreo->getCodigoDespachoFk() ?  'DESPACHO' : 'DESPACHO TIPO'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(40, 6, "", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(40, 6, '', 1, 0, 'L', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'REGISTRO', 'REPORTE', 'COMENTARIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(10, 30, 30, 120);
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
        $arMonitoreoDetalle = self::$em->getRepository(TteMonitoreoDetalle::class)->monitoreo(self::$codigoMonitoreo);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        if($arMonitoreoDetalle) {
            foreach ($arMonitoreoDetalle as $arMonitoreo) {
                $pdf->Cell(10, 4, $arMonitoreo['codigoMonitoreoDetallePk'], 1, 0, 'L');
                $pdf->Cell(30, 4, $arMonitoreo['fechaRegistro']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(30, 4, $arMonitoreo['fechaReporte']->format('Y-m-d'), 1, 0, 'L');
                $pdf->Cell(120, 4, utf8_decode($arMonitoreo['comentario']), 1, 0, 'L');
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