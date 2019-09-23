<?php


namespace App\Formato\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Transporte\TteCondicionManejo;
use App\Entity\Transporte\TtePrecio;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;

class Cliente extends \FPDF
{
    public static $em;
    public static $codigoCliente;
    public static $fecha;

    public function Generar($em, $codigoCliente)
    {
        ob_clean();
        self::$em = $em;
        self::$fecha = date_format(new \DateTime('now'), "Y/m/d H:i:s");

        $pdf = new Cliente();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, self::$fecha, $codigoCliente);
        $pdf->Output("Cliente.pdf", 'D');
    }

    public function Header()
    {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo

        $this->Image('../public/img/recursos/transporte/supertransporte.png', 13, 3, 30, 20);
        Estandares::generarEncabezado($this, 'Cliente', self::$em);

        $this->SetXY(53, 34);
        $this->Cell(20, 4, 'FECHA:', 0, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
        $this->Ln(4);
    }

    public function Body($pdf, $fecha, $codigoCliente)
    {
        $em = BaseDatos::getEm();
        $pdf->SetXY(74, 34);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(20, 4, $fecha, 0, 0, 'L', 1);

        $pdf->SetXY(10, 50);
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('arial', 'B', 7);
        // tabla cliente.
        $pdf->Ln();
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(195, 5, "CLIENTE", 1, 0, 'C', 1);
        $pdf->Ln(5);
        $arCliente = $em->getRepository(TteCliente::class)->find($codigoCliente);
        $arAsesor = $arCliente->getAsesorRel();

        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(97.5, 5, "NOMBRE CLIENTE", 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, utf8_decode($arCliente->getNombreCorto()), 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(97.5, 5, utf8_decode("NÚMERO DE IDENTIFICACIÓN"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, utf8_decode($arCliente->getNumeroIdentificacion()), 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(97.5, 5, utf8_decode("ASESOR"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, utf8_decode($arAsesor->getNombre()), 1, 0, 'L', 1);
        $pdf->Ln(8);
        // tabla flete.
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(195, 5, "FLETE", 1, 0, 'C', 1);
        $pdf->Ln(5);
        $headerFlete = array('ID', 'ORIGEN', 'DESTINO', 'ZONA', 'D_P', 'D_U', 'P_M', 'M_GUIA', 'F_MIN', 'M_GUIA');
        $weightFlete = array(10, 25, 45, 22, 9, 9, 10, 10, 10, 10);
        $arCondicionesFletes = $em->getRepository(TteCondicionFlete::class)->cliente($codigoCliente);
        for ($i = 0; $i < count($headerFlete); $i++) {
            $pdf->SetFillColor(170, 170, 170);
            $pdf->SetTextColor(0);
            $pdf->SetFont('arial', 'B', 7);
            $pdf->Cell($weightFlete[$i], 4, $headerFlete[$i], 1, 0, 'L', 1);
        }
        $pdf->Ln();
        foreach ($arCondicionesFletes as $arCondicionesFlete => $fleteItem) {
            $pdf->Cell(10, 4, $fleteItem['codigoCondicionFletePk'], 'LRB', 0, 'L');
            $pdf->Cell(25, 4, utf8_decode($fleteItem['ciudadOrigenNombre']), 'LRB', 0, 'L');
            $pdf->Cell(45, 4, utf8_decode($fleteItem['ciudadDestinoNombre']), 'LRB', 0, 'L');
            $pdf->Cell(22, 4, $fleteItem['zonaNombre'], 'LRB', 0, 'L');
            $pdf->Cell(9, 4, $fleteItem['descuentoPeso'], 'LRB', 0, 'L');
            $pdf->Cell(9, 4, $fleteItem['descuentoUnidad'], 'LRB', 0, 'L');
            $pdf->Cell(10, 4, $fleteItem['pesoMinimo'], 'LRB', 0, 'L');
            $pdf->Cell(10, 4, $fleteItem['pesoMinimoGuia'], 'LRB', 0, 'L');
            $pdf->Cell(10, 4, $fleteItem['fleteMinimo'], 'LRB', 0, 'L');
            $pdf->Cell(10, 4, $fleteItem['fleteMinimoGuia'], 'LRB', 0, 'L');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        // tabla manejo
        $pdf->Ln();
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(195, 5, "MANEJO", 1, 0, 'C', 1);
        $pdf->Ln(5);
        $headerManejo = array('ID', 'ORIGEN', 'DESTINO', 'ZONA', 'POR', 'MIN(UND)', 'MIN(DES)');
        $weightManejo = array(10, 25, 45, 30, 20, 15, 15);
        $arCondicionesManejos = $em->getRepository(TteCondicionManejo::class)->cliente($codigoCliente);
        for ($i = 0; $i < count($headerManejo); $i++) {
            $pdf->SetFillColor(170, 170, 170);
            $pdf->SetTextColor(0);
            $pdf->SetFont('arial', 'B', 7);
            $pdf->Cell($weightManejo[$i], 4, $headerManejo[$i], 1, 0, 'L', 1);
        }
        $pdf->Ln();
        foreach ($arCondicionesManejos as $arCondicionesManejo => $manejoItem) {
            $pdf->Cell(10, 4, $manejoItem['codigoCondicionManejoPk'], 'LRB', 0, 'L');
            $pdf->Cell(25, 4, utf8_decode($manejoItem['ciudadOrigenNombre']), 'LRB', 0, 'L');
            $pdf->Cell(45, 4, utf8_decode($manejoItem['ciudadDestinoNombre']), 'LRB', 0, 'L');
            $pdf->Cell(30, 4, $manejoItem['zonaNombre'], 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $manejoItem['porcentaje'], 'LRB', 0, 'R');
            $pdf->Cell(15, 4, $manejoItem['minimoUnidad'], 'LRB', 0, 'R');
            $pdf->Cell(15, 4, $manejoItem['minimoDespacho'], 'LRB', 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
        $pdf->AddPage();
        //información precio
        $arCondicion = $arCliente->getCondicionRel();
        $arPrecio = $em->getRepository(TtePrecio::class)->find($arCondicion->getCodigoPrecioFk());
        $arPrecios = $em->getRepository(TtePrecioDetalle::class)->lista($arCondicion->getCodigoPrecioFk());
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(97.5, 5, utf8_decode("CÓDIGO"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, utf8_decode($arPrecio->getCodigoPrecioPk()), 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(97.5, 5, utf8_decode("NOMBRE"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, utf8_decode($arPrecio->getNombre()), 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(97.5, 5, utf8_decode("FECHA VENCE"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, date_format($arPrecio->getFechaVence(), "Y/m/d"), 1, 0, 'L', 1);
        $pdf->Ln();
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetFont('arial', 'B', 7);
        $pdf->Cell(97.5, 5, utf8_decode("COMENTARIOS"), 1, 0, 'L', 1);
        $pdf->SetFillColor(253, 254, 254);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Cell(97.5, 5, utf8_decode(substr($arPrecio->getComentario(), 0, 100)), 1, 0, 'L', 1);
        $pdf->Ln(5);
        //tabla de precios
        $pdf->Ln();
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(190, 5, "PRECIO", 1, 0, 'C', 1);
        $pdf->Ln(5);
        $headerPrecio = array('ID', 'ORIGEN', 'DESTINO', 'ZONA', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA', 'DO', 'PRODUCTO', 'VR KILO', 'VR UNIDAD');
        $weightPrecio = array(15, 20, 37, 35, 5, 5, 5, 5, 5, 5, 5, 18, 15, 15);
        for ($i = 0; $i < count($headerPrecio); $i++) {
            $pdf->SetFillColor(170, 170, 170);
            $pdf->SetTextColor(0);
            $pdf->SetFont('arial', 'B', 7);
            $pdf->Cell($weightPrecio[$i], 6, $headerPrecio[$i], 1, 0, 'L', 1);
        }
        $pdf->Ln();
        foreach ($arPrecios as $arPrecio => $precioItem) {
            $pdf->Cell(15, 4, utf8_decode(substr($precioItem['codigoPrecioDetallePk'], 0, 5)), 'LRB', 0, 'L');
            $pdf->Cell(20, 4, utf8_decode(substr($precioItem['ciudadOrigen'], 0, 20)), 'LRB', 0, 'L');
            $pdf->Cell(37, 4, utf8_decode(substr($precioItem['ciudadDestino'], 0, 20)), 'LRB', 0, 'L');
            if ($precioItem['zonaNombre']) {
                $pdf->Cell(35, 4, substr($precioItem['zonaNombre'], 0, 20), 'LRB', 0, 'L');
            } else {
                $pdf->Cell(35, 4, substr($precioItem['zonaCiudadDestino'], 0, 20), 'LRB', 0, 'L');
            }
            $pdf->Cell(5, 4, $precioItem['ciudadDestinoLunes']?'SI':'NO', 'LRB', 0, 'L');
            $pdf->Cell(5, 4, $precioItem['ciudadDestinoMartes']?'SI':'NO', 'LRB', 0, 'L');
            $pdf->Cell(5, 4, $precioItem['ciudadDestinoMiercoles']?'SI':'NO', 'LRB', 0, 'L');
            $pdf->Cell(5, 4, $precioItem['ciudadDestinoJueves']?'SI':'NO', 'LRB', 0, 'L');
            $pdf->Cell(5, 4, $precioItem['ciudadDestinoViernes']?'SI':'NO', 'LRB', 0, 'L');
            $pdf->Cell(5, 4, $precioItem['ciudadDestinoSabado']?'SI':'NO', 'LRB', 0, 'L');
            $pdf->Cell(5, 4, $precioItem['ciudadDestinoDomingo']?'SI':'NO', 'LRB', 0, 'L');
            $pdf->Cell(18, 4, $precioItem['producto'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, number_format($precioItem['vrPeso'], 0, '.', ','), 1, 0, 'R');
            $pdf->Cell(15, 4, number_format($precioItem['vrUnidad'], 0, '.', ','), 1, 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }
    }
}