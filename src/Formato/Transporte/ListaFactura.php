<?php

namespace App\Formato\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class ListaFactura extends \FPDF {
    public static $em;


    public function Generar($em) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        $pdf = new ListaFactura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ListaFactura.pdf", 'D');
    }

    public function Header() {
        Estandares::generarEncabezado($this,'RELACION FACTURAS', self::$em);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $header = array('ID', 'TIPO', 'NUMERO', 'FECHA','CLIENTE');
        $this->SetFillColor(170, 170, 170);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 22, 15, 15, 65 );
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
        $em = BaseDatos::getEm();
        $arFacturas = $em->getRepository(TteFactura::class)->lista();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arFacturas as $arFactura) {

            $pdf->Cell(15, 4, $arFactura['codigoFacturaPk'], 'LRB', 0, 'L');
            $pdf->Cell(22, 4, $arFactura['facturaTipo'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['numero'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['fecha']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(65, 4, utf8_decode($arFactura['clienteNombre']), 'LRB', 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

}
?>