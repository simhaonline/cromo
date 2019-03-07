<?php

namespace App\Formato\Inventario;

use App\Entity\General\GenConfiguracion;
use App\Entity\Inventario\InvLote;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class ExistenciaLote extends \FPDF {

    public static $em;

    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new ExistenciaLote();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ExistenciaLote.pdf", 'D');
    }

    public function Header() {

        Estandares::generarEncabezado($this, 'LOTE EXISTENCIA', self::$em);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        try {
            $this->Image('../public/assets/img/empresa/logo.jpg', 12, 13, 40, 25);
        } catch (\Exception $exception) {
        }
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(34);
        $header = array('ITEM', 'NOMBRE','MARCA', 'BODEGA', 'LOTE', 'EXI', 'REM', 'DIS');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(12, 48, 30, 20, 30, 15, 15, 15);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);
    }

    public function Body($pdf)
    {
        $em = BaseDatos::getEm();
        $arExistenciaLote = $em->getRepository(InvLote::class)->existencia()->getQuery()->execute();
        $pdf->SetFont('Arial', '', 7);
        foreach ($arExistenciaLote as $arExistenciaLote) {
            $pdf->Cell(12, 4, $arExistenciaLote['codigoItemFk'], 1, 0, 'L');
            $pdf->Cell(48, 4, utf8_decode($arExistenciaLote['itemNombre']), 1, 0, 'L');
            $pdf->Cell(30, 4, $arExistenciaLote['marca'], 1, 0, 'L');
            $pdf->Cell(20, 4, $arExistenciaLote['codigoBodegaFk'], 1, 0, 'L');
            $pdf->Cell(30, 4, $arExistenciaLote['loteFk'], 1, 0, 'L');
            $pdf->Cell(15, 4, number_format($arExistenciaLote['cantidadExistencia']), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arExistenciaLote['cantidadRemisionada']), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($arExistenciaLote['cantidadDisponible']), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 25);
        }
    }
}
?>