<?php

namespace App\Formato;

class Despacho extends \FPDF {
    public static $em;
    public static $codigoDespacho;

    public function Generar($em, $codigoDespacho) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoDespacho = $codigoDespacho;
        $pdf = new Despacho('L');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Despacho$codigoDespacho.pdf", 'D');
    }

    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial','B',10);


        $this->SetXY(50, 10);
        $this->Cell(238, 7, utf8_decode("Despacho"), 0, 0, 'C', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {

    }

    public function Body($pdf) {


    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}

?>