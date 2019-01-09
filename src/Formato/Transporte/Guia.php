<?php

namespace App\Formato\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;


class Guia extends \FPDF
{

    public static $em;
    public static $codigoGuia;
    public static $imagen;
    public static $extension;


    public function Generar($em, $codigoGuia)
    {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoGuia = $codigoGuia;
        $pdf = new Guia();
        $logo = self::$em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
        self::$imagen="data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen()));
        self::$extension=$logo->getExtension();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Guia.pdf", 'D');
    }

    public function Header()
    {
//        $this->SetFillColor(200, 200, 200);
//        $this->SetFont('Arial', 'B', 10);
        $this->Ln(16);
//        $this->EncabezadoDetalles();

    }

    public function EncabezadoDetalles()
    {
    }

    public function Body($pdf)
    {
        $em = BaseDatos::getEm();
        $arGuia = $em->getRepository(TteGuia::class)->imprimirGuia(self::$codigoGuia);
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $y = 20;
        for ($i = 0; $i <= 3; $i++) {
            try {
                if (self::$imagen) {
                    $pdf->Image(self::$imagen, 10,  $y-16, 40, 15,self::$extension);
                }
            } catch (\Exception $exception) {
            }
            $pdf->SetFont('Arial', 'B', 15);
            $pdf->SetXY(168, $y-8);
            $pdf->Cell(30,4, $arGuia['numero'], 0, 0, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Text(55, $y-13, utf8_decode($arConfiguracion->getNombre()));
            $pdf->Text(55, $y-9, utf8_decode($arConfiguracion->getNit(). "-" . $arConfiguracion->getDigitoVerificacion()));
            $pdf->Text(55, $y-5, utf8_decode($arConfiguracion->getDireccion()));
            $pdf->Text(55, $y-1, utf8_decode($arConfiguracion->getTelefono()));
            $pdf->SetXY(10, $y);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(49, 6, utf8_decode("Fecha:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(22, $y);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(49, 6, $arGuia['fechaIngreso']->format('d') . ',' . FuncionesController::mesEspanol($arGuia['fechaIngreso']->format('j')) . 'de' . $arGuia['fechaIngreso']->format('Y'), 'TRB', 0, 'L', 1);

            $pdf->SetXY(71, $y);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(47, 6, utf8_decode("Origen:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(84, $y);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(47, 6, utf8_decode($arGuia['ciudadOrigen']), 'TRB', 0, 'L', 1);

            $pdf->SetXY(131, $y);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(47, 6, utf8_decode("Destino:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(151, $y);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(47, 6, utf8_decode($arGuia['ciudadDestino']), 'TRB', 0, 'L', 1);

            $pdf->SetXY(10, $y + 6);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(49, 6, utf8_decode("Remite:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(22, $y + 6);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(49, 6, utf8_decode($arGuia['remitente']), 'TRB', 0, 'L', 1);

            $pdf->SetXY(71, $y + 6);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(77, 6, utf8_decode("Direccion:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(87, $y + 6);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(77, 6, utf8_decode($arGuia['direccionCliente']), 'TRB', 0, 'L', 1);

            $pdf->SetXY(161, $y + 6);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(23, 6, utf8_decode("Tel:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(175, $y + 6);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(23, 6, utf8_decode($arGuia['telefonoCliente']), 'TRB', 0, 'L', 1);

            $pdf->SetXY(10, $y + 12);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(49, 6, utf8_decode("Destinatario:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(28, $y + 12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(49, 6, utf8_decode($arGuia['nombreDestinatario']), 'TRB', 0, 'L', 1);

            $pdf->SetXY(71, $y + 12);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(77, 6, utf8_decode("Direccion:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(87, $y + 12);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(77, 6, utf8_decode(substr($arGuia['direccionDestinatario'], 0, 60)), 'TRB', 0, 'L', 1);

            $pdf->SetXY(161, $y + 12);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(23, 6, utf8_decode("Tel:"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(175, $y + 12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(23, 6, utf8_decode($arGuia['telefonoDestinatario']), 'TRB', 0, 'L', 1);

            $pdf->SetXY(10, $y + 18);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(43, 6, utf8_decode("Tipo cobro:"), 'TL', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(28, $y + 18);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(43, 6, utf8_decode($arGuia['guiaTipo']), 'TR', 0, 'L', 1);
            // segunda fila del bloque
            $pdf->SetXY(10, $y + 24);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(43, 6, utf8_decode("Documento:"), 'LB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(28, $y + 24);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(43, 6, utf8_decode($arGuia['documentoCliente']), 'RB', 0, 'L', 1);

            $pdf->SetXY(71.5, $y + 19);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 7.8);
            $pdf->Multicell(128, 3.5, utf8_decode("Observaciones:" . $arGuia['comentario']), 0, 'L');
            $pdf->SetXY(71, $y + 18);
            $pdf->Cell(127, 12, '', 1, 2, 'C');

            $pdf->SetXY(10, $y + 30);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 6, utf8_decode("Cant"), 'TLB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(25, $y + 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 6, utf8_decode("Peso"), 'TB', 0, 'L', 1);
            $pdf->SetXY(45, $y + 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 6, utf8_decode("Flete"), 'TB', 0, 'L', 1);
            $pdf->SetXY(60, $y + 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 6, utf8_decode("Manejo"), 'TB', 0, 'L', 1);
            $pdf->SetXY(80, $y + 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 6, utf8_decode("Cobro"), 'TB', 0, 'L', 1);
            $pdf->SetXY(95, $y + 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(40, 6, utf8_decode("Declarado"), 'TB', 0, 'L', 1);
            $pdf->SetXY(130, $y + 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(68, 6, utf8_decode("DEVOLVER FIRMADO Y SELLADO"), 'TBR', 0, 'L', 1);

            $pdf->SetXY(10, $y + 36);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, number_format($arGuia['unidades']), 'TL', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(25, $y + 36);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, number_format($arGuia['pesoReal']), 'T', 0, 'L', 1);
            $pdf->SetXY(45, $y + 36);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, number_format($arGuia['vrFlete']), 'T', 0, 'L', 1);
            $pdf->SetXY(60, $y + 36);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, number_format($arGuia['vrManejo']), 'T', 0, 'L', 1);
            $pdf->SetXY(80, $y + 36);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, number_format($arGuia['vrCobroEntrega']), 'T', 0, 'L', 1);
            $pdf->SetXY(95, $y + 36);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(40, 6, number_format($arGuia['vrDeclara']), 'T', 0, 'L', 1);
            $pdf->SetXY(115, $y + 36);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(83, 6, utf8_decode("NOMBRE"), 'TLR', 0, 'L', 1);

            $pdf->SetXY(10, $y + 42);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'LB', 0, 'L', 1);
            $pdf->SetFillColor(272, 272, 272);
            $pdf->SetXY(25, $y + 42);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'B', 0, 'L', 1);
            $pdf->SetXY(45, $y + 42);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'B', 0, 'L', 1);
            $pdf->SetXY(60, $y + 42);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'B', 0, 'L', 1);
            $pdf->SetXY(80, $y + 42);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 6, '', 'B', 0, 'L', 1);
            $pdf->SetXY(95, $y + 42);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(40, 6, '', 'B', 0, 'L', 1);
            $pdf->SetXY(115, $y + 42);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(83, 6, utf8_decode("CC NIT.                                                       FECHA HORA"), 'LBR', 0, 'L', 1);

            $y += 68;
        }


    }


}

?>