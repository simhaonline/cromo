<?php


namespace App\Formato\Turno;


use App\Entity\Turno\TurCotizacion;
use App\Entity\Turno\TurCotizacionDetalle;
use App\Utilidades\Estandares;

class Cotizacion extends \FPDF
{
    public static $em;
    public static $codigoCotizacion;
    public static $arUsuario;

    /**
     * @param $em ObjectManager
     * @param $codigoMovimiento integer
     */
    public function Generar($em, $codigoCotizacion)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoCotizacion = $codigoCotizacion;
        $pdf = new Cotizacion();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Cotizacion{$codigoCotizacion}.pdf", 'D');
    }

    public function Header() {
        $arCotizacion = self::$em->getRepository(TurCotizacion::class)->find(self::$codigoCotizacion);
        Estandares::generarEncabezado($this, 'COTIZACIÓN', self::$em);

        $intY = 40;
        $this->SetFillColor(272, 272, 272);
        $this->SetXY(10, $intY);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NUMERO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arCotizacion->getNumero(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "FECHA:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arCotizacion->getFecha()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 4);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "NIT:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4,  $arCotizacion->getClienteRel()->getNumeroIdentificacion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "VENCE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arCotizacion->getFechaVence()->format('Y/m/d'), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 8);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "CLIENTE:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arCotizacion->getClienteRel()->getNombreCorto(), 1, 0, 'L', 1);

        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CONTACTO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4,  $arCotizacion->getClienteRel()->getTelefono()." - ".$arCotizacion->getClienteRel()->getMovil(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 12);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "DIRECCION:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, $arCotizacion->getClienteRel() ? $arCotizacion->getClienteRel()->getDireccion() : $arCotizacion->getProspectoRel()->getDireccion(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CARGO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 16);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "CIUDAD:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4, "", 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'DEPARTAMENTO:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, '', 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 20);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "TELEFONO:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(65, 4,  $arCotizacion->getClienteRel()->getTelefono(), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, 'CELULAR:' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(65, 4, $arCotizacion->getClienteRel()->getMovil(), 1, 0, 'L', 1);

        $this->SetXY(10, $intY + 24);
        $this->SetFont('Arial','B',8);
        $this->Cell(30, 4, "EMAIL:" , 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        $this->Cell(160, 4, utf8_decode($arCotizacion->getClienteRel()->getCorreo()), 1, 0, 'L', 1);
        $this->SetFont('Arial','B',8);
        $this->ln();
        $this->Cell(30, 4, 'Comentario' , 1, 0, 'L', 1);
        $this->SetFont('Arial','',7);
        $this->Cell(160, 4, utf8_decode($arCotizacion->getComentario()), 1, 0, 'L', 1);
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles() {
        $this->Ln(14);
        $header = array('SERVICIO', 'MODALIDAD', 'PER', 'DESDE', 'HASTA', 'CANT', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA', 'DO', 'FE', 'H', 'H.D', 'H.N', 'VALOR');
        $this->SetFillColor(236, 236, 236);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 7);
//
        //creamos la cabecera de la tabla.
        $w = array(30, 20, 10, 15, 15, 10, 5, 5, 5, 5, 5, 5, 5, 5, 8, 8, 8, 15);
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
        $arCotizacionDetalles = self::$em->getRepository(TurCotizacionDetalle::class)->findBy(array('codigoCotizacionFk' => self::$codigoCotizacion));
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $vrTotal= 0;
        foreach ($arCotizacionDetalles as $arCotizacionDetalle) {
            $pdf->Cell(30, 4, substr($arCotizacionDetalle->getConceptoRel()->getNombre(), 0,15), 1, 0, 'L');
            $pdf->Cell(20, 4, substr($arCotizacionDetalle->getModalidadRel()->getNombre(),0, 10), 1, 0, 'L');
            $pdf->Cell(10, 4, $arCotizacionDetalle->getPeriodo(), 1, 0, 'L');
            $pdf->Cell(15, 4, $arCotizacionDetalle->getFechaDesde()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(15, 4, $arCotizacionDetalle->getFechaHasta()->format('Y/m/d'), 1, 0, 'L');
            $pdf->Cell(10, 4, number_format($arCotizacionDetalle->getCantidad(), 0, '.', ','), 1, 0, 'R');
            if($arCotizacionDetalle->getLunes() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            if($arCotizacionDetalle->getMartes() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            if($arCotizacionDetalle->getMiercoles() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            if($arCotizacionDetalle->getJueves() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            if($arCotizacionDetalle->getViernes() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            if($arCotizacionDetalle->getSabado() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            if($arCotizacionDetalle->getDomingo() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            if($arCotizacionDetalle->getFestivo() == 1) {
                $pdf->Cell(5, 4, 'SI', 1, 0, 'L');
            } else {
                $pdf->Cell(5, 4, 'NO', 1, 0, 'L');
            }
            $pdf->Cell(8, 4, $arCotizacionDetalle->getHoras(), 1, 0, 'R');
            $pdf->Cell(8, 4, $arCotizacionDetalle->getHorasDiurnas(), 1, 0, 'R');
            $pdf->Cell(8, 4, $arCotizacionDetalle->getHorasNocturnas(), 1, 0, 'R');
            $vrTotal += $arCotizacionDetalle->getVrTotalDetalle();
            $pdf->Cell(15, 4, number_format($arCotizacionDetalle->getVrTotalDetalle(), 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        //TOTALES
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Ln(5);
        $pdf->SetX(129);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(35, 4, "TOTAL:", 1, 0, 'R', true);
        $pdf->Cell(25, 4, number_format($vrTotal, 0, '.', ','), 1, 0, 'R');
        $pdf->Ln(8);
    }

    public function Footer() {

        $this->SetFont('Arial','', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}