<?php

namespace App\Formato\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;

class ControlFactura extends \FPDF
{
    public static $em;
    public static $fecha;

    public function Generar($em, $fecha)
    {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$fecha = $fecha;
        $pdf = new ControlFactura();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, $fecha);
        $pdf->Output("Comprobante informe diario.pdf", 'I');
    }

    public function Header()
    {
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo


        Estandares::generarEncabezado($this, 'COMPROBANTE INFORME DIARIO');

        $this->SetXY(53, 34);
        $this->Cell(20, 4, 'FECHA:', 0, 0, 'L', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(10, 40);
        $this->Cell(185, 4, 'CIU 4423 transporte de carga por carretera', 1, 0, 'L', 1);
        $this->SetXY(10, 44);
        $this->Cell(185, 4, 'Numero de identificacion de las maquinas registradoras o computadores que emiten documento equivalente o factura IP 190.85.62.78', 1, 0, 'L', 1);

        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {

        $this->Ln(8);
    }

    public function Body($pdf, $fecha)
    {
        $em = BaseDatos::getEm();
        $pdf->SetXY(74, 34);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Cell(20, 4, $fecha, 0, 0, 'L', 1);


        $pdf->SetXY(10, 50);
        $header = array('ID', 'TIPO', 'DESDE', 'HASTA');
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 40, 20, 20);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln();

        $arFacturasTipo = $em->getRepository(TteFacturaTipo::class)->controlFactura($fecha);
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $vrTotal = 0;
        foreach ($arFacturasTipo as $arFacturaTipo) {
            $pdf->Cell(15, 4, $arFacturaTipo['codigoFacturaTipoPk'], 'LRB', 0, 'L');
            $pdf->Cell(40, 4, utf8_decode($arFacturaTipo['nombre']), 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $arFacturaTipo['desde'], 'LRB', 0, 'L');
            $pdf->Cell(20, 4, $arFacturaTipo['hasta'], 'LRB', 0, 'L');
            $vrTotal += $arFacturaTipo['vrTotal'];
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
        }

        $arFacturas = $em->getRepository(TteFactura::class)->controlFactura();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Ln();

        $header = array('REGISTRO', 'ID', 'FACTURA', 'TIPO', 'FECHA', 'CLIENTE', 'FLETE', 'MANEJO', 'TOTAL');
        $pdf->SetFillColor(170, 170, 170);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.2);
        $pdf->SetFont('arial', 'B', 7);
        //creamos la cabecera de la tabla.
        $w = array(15, 15, 15, 20, 15, 60, 15, 15, 15);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'L', 1);
            else
                $pdf->Cell($w[$i], 4, $header[$i], 1, 0, 'C', 1);
        //Restauración de colores y fuentes
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $pdf->Ln();
        $registro = 1;
        foreach ($arFacturas as $arFactura) {
            $pdf->Cell(15, 4, $registro, 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['codigoFacturaPk'], 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['numero'], 'LRB', 0, 'L');
            $pdf->Cell(20, 4, utf8_decode($arFactura['nombre']), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, $arFactura['fecha']->format('Y-m-d'), 'LRB', 0, 'L');
            $pdf->Cell(60, 4, utf8_decode($arFactura['nombreCorto']), 'LRB', 0, 'L');
            $pdf->Cell(15, 4, number_format($arFactura['vrFlete']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrManejo']), 'LRB', 0, 'R');
            $pdf->Cell(15, 4, number_format($arFactura['vrTotal']), 'LRB', 0, 'R');
            $pdf->Ln();
            $pdf->SetAutoPageBreak(true, 15);
            $registro++;
        }
        //Bloque 1
        $pdf->Ln(4);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 8);
        $pdf->Cell(20, 4, 'VENTAS EXCLUIDAS:', 0, 0, 'L', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(20, 4, 'VENTAS GRAVAS EXENTAS Y DESCUENTOS:', 0, 0, 'L', 1);
        $pdf->Ln(6);
        //Bloque 2
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'EN EFECTIVO', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'EN EFECTIVO', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->Ln(4);
        //Bloque 3
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[0]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[0]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[0]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[0]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TOTAL', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[0]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[0]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(6);
        //Bloque 4
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'EN CHEQUES', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'EN CHEQUES', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->Ln(4);
        //Bloque 5
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TOTAL', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(6);
        //Bloque 4
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TARJETAS CREDITO O DEBITO', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TARJETAS CREDITO O DEBITO', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->Ln(4);
        //Bloque 5
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TOTAL', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(6);
        //Bloque 4
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'CREDITO', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'CREDITO', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->Ln(4);
        //Bloque 5
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[1]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[1]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[1]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[1]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TOTAL', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[1]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[1]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(6);
        //Bloque 4
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'DESTINO (CONTRAENTREGA)', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'DESTINO (CONTRAENTREGA)', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'REGISTROS', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(19, 4, 'VALOR', 1, 0, 'C', 1);
        $pdf->Ln(4);
        //Bloque 5
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[2]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[2]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'COMPUTADORA O TERMINALES', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[2]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[2]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
        //
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TOTAL', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $arFacturasTipo[2]['numeroFacturas'], 1, 0, 'C', 1);
        $pdf->SetX(73);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, number_format($arFacturasTipo[2]['vrTotal']), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(44, 4, 'IP 190.85.62.78', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, '0', 1, 0, 'C', 1);
        $pdf->SetX(176);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(19, 4, $vrTotal, 1, 0, 'R', 1);
        $pdf->Ln(6);
        //Bloque 4
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'TOTAL VENTAS EXCLUIDAS', 1, 0, 'L', 1);
        $pdf->SetX(54);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(38, 4, number_format($vrTotal), 1, 0, 'R', 1);
        $pdf->SetX(113);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(44, 4, 'VENTAS GRAVAS EXENTAS', 1, 0, 'L', 1);
        $pdf->SetX(157);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', 'b', 7);
        $pdf->Cell(38, 4, '0', 1, 0, 'R', 1);
        $pdf->Ln(4);
    }

}

?>