<?php


namespace App\Formato\RecursoHumano;


use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Utilidades\Estandares;

class Examen extends \FPDF
{
    public static $em;
    public static $codigoExamen;

    public function Generar($em, $codigoExamen)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoExamen = $codigoExamen;
        $pdf = new Examen();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Examen$codigoExamen.pdf", 'D');
    }

    public function Header()
    {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
//        $arContenido = self::$em->getRepository(GenContenido::class)->find(6);
        $arExamen = self::$em->getRepository(RhuExamen::class)->find(self::$codigoExamen);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        Estandares::generarEncabezado($this, 'ORDEN DE EXAMEN MEDICO', self::$em);

        $this->SetXY(165, 30);
        $this->Cell(35, 4, utf8_decode("CONSECUTIVO: ".$arExamen->getCodigoExamenPk()), 1, 0, 'L', 1);
        //
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        $intY = 40;
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(10, $intY);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "DOCUMENTO:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arExamen->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "NOMBRE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(100, 6, utf8_decode($arExamen->getNombreCorto()), 1, 0, 'L', 1);
        $this->SetXY(10, $intY + 5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "FECHA:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arExamen->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "CENTRO COSTOS:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(100, 6, "", 1, 0, 'L', 1);
        $this->SetXY(10, $intY + 10);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "TIPO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arExamen->getExamenTipoRel()->getNombre(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "ENTIDAD EXAMEN:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
        $this->Cell(100, 6, $arExamen->getExamenEntidadRel() != null ? $arExamen->getExamenEntidadRel()->getNombre():"", 1, 0, 'L', 1);
        $this->SetXY(10, $intY + 15);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "CARGO", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 6, $arExamen->getCargoRel() != null ? $arExamen->getCargoRel()->getNombre(): "", 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 6, "CLIENTE:", 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 7);
//        if ($arExamen->getCodigoClienteFk() != null) {
//            $cliente = $arExamen->getClienteRel()->getNombreCorto();
//        } else {
//            $cliente = 'SIN CONTRATO';
//        }
        $cliente = "";
        $this->Cell(100, 6, $cliente, 1, 0, 'L', 1);
        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("DIRECCIÓN:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(100, 5, $arExamen->getExamenEntidadRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("TELÉFONO:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 5, $arExamen->getExamenEntidadRel()->getTelefono(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 25);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 5, utf8_decode("COMENTARIOS:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(160, 5, $arExamen->getComentario(), 1, 0, 'L', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(14);
        $header = array('ID', 'TIPO EXAMEN', 'VR.EXAMEN');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);

        //creamos la cabecera de la tabla.
        $w = array(10, 160, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(4);

    }

    public function Body($pdf)
    {
        $arExamenDetalles = self::$em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => self::$codigoExamen));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        foreach ($arExamenDetalles as $arExamenDetalle) {
            $pdf->Cell(10, 4, $arExamenDetalle->getCodigoExamenDetallePk(), 1, 0, 'L');
            $pdf->Cell(160, 4, utf8_decode($arExamenDetalle->getExamenTipoRel()->getNombre()), 1, 0, 'L');
            $pdf->Cell(20, 4, number_format($arExamenDetalle->getVrPrecio(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            $w = array(10, 160, 20);
        }
        $w = $pdf->getY();
        $pdf->SetXY(160, $w + 4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, "TOTAL", 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(20, 4, number_format($arExamenDetalle->getExamenRel()->getVrTotal(), 0, '.', ','), 1, 0, 'R');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, $w + 15);
        $pdf->Cell(20, 4, "Aceptada y Firmada:" . "_______________________________________________", 0, 'L');
    }

    public function Footer() {
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}