<?php

namespace App\Formato\Crm;

use App\Entity\Crm\CrmVisita;
use App\Entity\General\GenConfiguracion;
use App\Utilidades\Estandares;

class Visita extends \FPDF {
    public static $em;
    public static $codigoVisita;

    public function Generar($em, $codigoVisita) {
        ob_clean();
        self::$em = $em;
        self::$codigoVisita = $codigoVisita;
        $pdf = new Visita();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("CrmVisita$codigoVisita.pdf", 'D');
    }


    public function Header() {

        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        Estandares::generarEncabezado($this,'Visita', self::$em);
        $arVisita = new CrmVisita();
        $arVisita = self::$em->getRepository('App\Entity\Crm\CrmVisita')->find(self::$codigoVisita);
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        //linea 1
        $this->SetXY(5, 40);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, utf8_decode("NUMERO:"), 1, 0, 'L', 1);
        $this->SetFillColor(272, 272, 272);
        $this->SetFont('Arial', '', 8);
        $this->Cell(68, 6, $arVisita->getCodigoVisitaPk(), 1, 0, 'R', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(272, 272, 272);
        $this->Cell(68, 6, $arVisita->getFecha()->format('m-d-Y'), 1, 0, 'L', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(12);
        $this->SetX(5);
        $header = array('TIPO', 'FECHA','COMENTARIO');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(50, 20, 126);
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
        $arVisita = self::$em->getRepository('App\Entity\Crm\CrmVisita')->find(self::$codigoVisita);
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 7);
        if($arVisita) {

                $pdf->SetX(5);
                $pdf->Cell(50, 4, $arVisita->getCodigoVisitaTipoFk(), 1, 0, 'L');
                $pdf->Cell(20, 4, $arVisita->getFecha()->format('m-d-Y'), 1, 0, 'L');
                $pdf->Cell(126, 4, $arVisita->getComentarios(), 1, 0, 'L');
                $pdf->Ln();
                $pdf->SetX(5);
                $pdf->SetAutoPageBreak(true, 15);


//
            $pdf->SetAutoPageBreak(true, 15);
        }
    }

    public function Footer() {
        $this->SetFont('Arial','', 8);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(10, 200);

        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }


}
