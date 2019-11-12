<?php


namespace App\Formato\General;


use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenFormato;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;
use function Complex\ln;

class GenerarFormatoCarta extends \FPDF
{
    public static $em;
    public static $codigoFormato;
    public static $parametros;

    /**
     * @param $em
     * @param $codigoContrato
     */
    public function Generar($em, $codigoFormato, $parametros)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoFormato = $codigoFormato;
        self::$parametros = $parametros;
        $pdf = new GenerarFormatoCarta();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf, $codigoFormato, $parametros);
        $pdf->Output("Carta.pdf", 'D');
    }

    public function Header()
    {
        $em = self::$em;
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('Arial', 'B', 10);
        //Logo
        $this->SetXY(53, 10);
        try {
            $logo = $em->getRepository('App\Entity\General\GenImagen')->find('LOGO');
            if ($logo) {
                $this->Image("data:image/'{$logo->getExtension()}';base64," . base64_encode(stream_get_contents($logo->getImagen())), 15, 15, 50, 25, $logo->getExtension());
            }
        } catch (\Exception $exception) {
        }
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $this->Ln(8);
    }

    public function Body($pdf, $codigoFormato, $parametros)
    {
        /**
         * @var $arFormato GenFormato
         */
        $em = BaseDatos::getEm();
        $arFormato = self::$em->getRepository('App:General\GenFormato')->find(self::$codigoFormato);
        $pdf->SetFont('Arial', '', 10);
        $cadena = $arFormato->getContenido();

        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        $patron5 = '/#5/';
        $patron6 = '/#6/';
        $patron7 = '/#7/';
        $patron8 = '/#8/';
        $patron9 = '/#9/';

        $cadenaCambiada = preg_replace($patron1, $parametros['#1'], $cadena);
        $cadenaCambiada = preg_replace($patron2, $parametros['#2'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3, $parametros['#3'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4, $parametros['#4'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5, $parametros['#5'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron6, $parametros['#6'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron7, $parametros['#7'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron8, $parametros['#8'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron9, $parametros['#9'], $cadenaCambiada);
        $pdf->MultiCell(0, 5, utf8_decode($cadenaCambiada));


    }

    public function Footer()
    {
        /**
         * @var $arFormato GenFormato
         * @var $arConfiguracion GenConfiguracion
         */
        $arConfiguracion = self::$em->getRepository('App:General\GenConfiguracion')->find(1);
        $arFormato = self::$em->getRepository('App:General\GenFormato')->find(self::$codigoFormato);
        $this->SetFont('Arial', '', 10);
        $this->Text(10, 240, 'Atentamente');
        $this->Text(10, 245, $arFormato->getNombreFirma());
        $this->Text(10, 250, $arFormato->getCargoFirma());
        $this->Text(10, 255, $arConfiguracion->getNombre());
        $this->SetXY(160, 264);
        $this->SetTextColor(64, 64, 64);
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 4, $arConfiguracion->getDireccion(), 0, 0, 'R');
        $this->SetXY(160, 268);
        $this->Cell(30, 4, 'Telefonos:' . $arConfiguracion->getTelefono(), 0, 0, 'R');
        $this->SetXY(160, 272);
        $this->Cell(30, 4, 'Email:' . $arConfiguracion->getCorreo(), 0, 0, 'R');
        $this->SetXY(160, 276);
        $this->Cell(30, 4, $arConfiguracion->getCiudadRel()->getNombre(), 0, 0, 'R');
        $this->SetFont('Arial', '', 8);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }
}