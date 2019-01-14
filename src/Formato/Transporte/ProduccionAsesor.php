<?php

namespace App\Formato\Transporte;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Estandares;

class ProduccionAsesor extends \FPDF {

    public static $em;
    public static $fechaDesde;
    public static $fechaHasta;

    public function Generar($em, $fechaDesde, $fechaHasta) {
        ob_clean();
        self::$fechaDesde = $fechaDesde;
        self::$fechaHasta = $fechaHasta;
        self::$em = $em;
        $pdf = new ProduccionCliente();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ProduccionAsesor.pdf", 'D');
    }

    public function Header() {
        Estandares::generarEncabezado($this,'PRODUCCION ASESOR', self::$em);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'ASESOR', 'UNIDADES','FLETE', 'MANEJO', 'TOTAL');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 60, 20, 20, 20,20 );
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
        $em = self::$em;
        $arProducciones = $em->getRepository(TteGuia::class)->informeProduccionAsesor(self::$fechaDesde, self::$fechaHasta)->getQuery()->getResult();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $unidades = 0;
        $flete = 0;
        $manejo = 0;
        $total = 0;
        foreach ($arProducciones as $arProduccion) {
            $pdf->Cell(15, 4, $arProduccion['codigoAsesorFk'], 'LRB', 0, 'L');
            $pdf->Cell(60, 4, utf8_decode($arProduccion['asesorNombre']), 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $arProduccion['unidades'], 'LRB', 0, 'R');
            $pdf->Cell(20, 4, number_format($arProduccion['vrFlete']), 'LRB', 0, 'R');
            $pdf->Cell(20, 4, number_format($arProduccion['vrManejo']), 'LRB', 0, 'R');
            $pdf->Cell(20, 4, number_format($arProduccion['total']), 'LRB', 0, 'R');
            $unidades += $arProduccion['unidades'];
            $flete += $arProduccion['vrFlete'];
            $manejo += $arProduccion['vrManejo'];
            $total += $arProduccion['total'];
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->SetX(10);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(75, 4, 'TOTALES', 1, 0, 'L');
        $pdf->Cell(20, 4,  number_format($unidades), 1, 0, 'R');
        $pdf->Cell(20, 4,  number_format($flete), 1, 0, 'R');
        $pdf->Cell(20, 4,  number_format($manejo), 1, 0, 'R');
        $pdf->Cell(20, 4,  number_format($total), 1, 0, 'R');
        $pdf->SetX(5);
        $pdf->Ln();
        $pdf->SetAutoPageBreak(true, 15);
    }
}
