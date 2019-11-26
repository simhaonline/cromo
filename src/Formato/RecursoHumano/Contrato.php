<?php


namespace App\Formato\RecursoHumano;


use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenFormato;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoTipo;

class Contrato extends \FPDF
{

    public static $em;
    public static $codigoFormato;
    public static $codigoContrato;
    public static $parametros;

    public function Generar($em,  $codigoFormato, $parametros, $codigoContrato)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoFormato = $codigoFormato;
        self::$parametros = $parametros;
        self::$codigoContrato = $codigoContrato;
        $pdf = new Contrato();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);


        $this->Body($pdf);
        $pdf->Output("Contrato$codigoContrato.pdf", 'D');

    }

    public function Header()
    {
        $this->SetFillColor(236, 236, 236);
        $this->SetFont('Arial', 'B', 10);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        /**
         * @var $arContrato RhuContrato
         */
        $arContrato = self::$em->getRepository(RhuContrato::class)->find(self::$codigoContrato);
        $arFormato = self::$em->getRepository('App:General\GenFormato')->find(self::$codigoFormato);
//        $this->Cell(13, 13, $this->Image('imagenes/logos/logo.jpg', $this->GetX(), $this->GetY(),25));
        $this->Ln(23);
    }

    public function Body($pdf)
    {
        /**
         * @var $arContrato RhuContrato
         */
        $arContrato = self::$em->getRepository(RhuContrato::class)->find(self::$codigoContrato);
        $arFormato = self::$em->getRepository(GenFormato::class)->find(self::$codigoFormato);
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);

        $codigoContratoTipo = $arContrato->getCodigoContratoTipoFk();
        $arContratoTipo = self::$em->getRepository(RhuContratoTipo::class)->find($codigoContratoTipo);
        if ($codigoContratoTipo == null) {
            $cadena = "El contrato no tiene asociado un formato tipo contrato";
        } else {
            if ( $arFormato->getContenido() == null) {
                $cadena = "El contrato no tiene un formato creado en el sistema";
            } else {
                $cadena = $arFormato->getContenido();
            }
        }
        $pdf->SetXY(50, 36);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(110, 6, utf8_decode($arFormato->getTitulo()), 0, 0, 'C');
        $pdf->SetXY(50,44);
        $pdf->Cell(110, 6, utf8_decode($arConfiguracion->getNombre()), 0, 0, 'C');
        $pdf->SetY(50,56);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 6, utf8_decode("N° " . $arContrato->getCodigoContratoPk()), 0, 0, 'C');
        $pdf->SetY(72);
        $pdf->SetFont('Arial', '', 10);


        $patron1 = '/#1/';
        $patron2 = '/#2/';
        $patron3 = '/#3/';
        $patron4 = '/#4/';
        $patron5 = '/#5/';
        $patron6 = '/#6/';
        $patron7 = '/#7/';
        $patron8 = '/#8/';
        $patron9 = '/#9/';
        $patron10 = '/#a/';
        $patron11 = '/#b/';
        $patron12 = '/#c/';
        $patron13 = '/#d/';
        $patron14 = '/#e/';
        $patron15 = '/#f/';
        $patron16 = '/#g/';
        $patron17 = '/#h/';
        $patron18 = '/#i/';
        $patron19 = '/#j/';
        $patron20 = '/#k/';
        $patron21 = '/#l/';
        $patron22 = '/#m/';
        $patron23 = '/#n/';
        $patron24 = '/#o/';
        $patron25 = '/#p/';
        $patron26 = '/#q/';
        $patron27 = '/#r/';
        $patron28 = '/#s/';
        $patron29 = '/#t/';
        $patron30 = '/#u/';

        //reemplazar en la cadena
        $cadenaCambiada = preg_replace($patron1,    self::$parametros['#1'], $cadena);
        $cadenaCambiada = preg_replace($patron2,    self::$parametros['#2'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron3,    self::$parametros['#3'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron4,    self::$parametros['#4'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron5,    self::$parametros['#5'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron6,    self::$parametros['#6'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron7,    self::$parametros['#7'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron8,    self::$parametros['#8'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron9,    self::$parametros['#9'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron10,   self::$parametros['#a'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron11,   self::$parametros['#b'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron12,   self::$parametros['#c'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron13,   self::$parametros['#d'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron14,   self::$parametros['#e'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron15,   self::$parametros['#f'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron16,   self::$parametros['#g'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron17,   self::$parametros['#h'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron18,   self::$parametros['#i'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron19,   self::$parametros['#j'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron20,   self::$parametros['#k'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron21,   self::$parametros['#l'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron22,   self::$parametros['#m'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron23,   self::$parametros['#n'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron24,   self::$parametros['#o'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron25,   self::$parametros['#p'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron26,   self::$parametros['#q'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron27,   self::$parametros['#r'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron28,   self::$parametros['#s'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron29,   self::$parametros['#t'], $cadenaCambiada);
        $cadenaCambiada = preg_replace($patron30,   self::$parametros['#u'], $cadenaCambiada);

        $pdf->MultiCell(0, 5, $cadenaCambiada);
    }

    public function Footer()
    {
        //$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

    public static function MesesEspañol($mes)
    {

        if ($mes == '01') {
            $mesEspañol = "Enero";
        }
        if ($mes == '02') {
            $mesEspañol = "Febrero";
        }
        if ($mes == '03') {
            $mesEspañol = "Marzo";
        }
        if ($mes == '04') {
            $mesEspañol = "Abril";
        }
        if ($mes == '05') {
            $mesEspañol = "Mayo";
        }
        if ($mes == '06') {
            $mesEspañol = "Junio";
        }
        if ($mes == '07') {
            $mesEspañol = "Julio";
        }
        if ($mes == '08') {
            $mesEspañol = "Agosto";
        }
        if ($mes == '09') {
            $mesEspañol = "Septiembre";
        }
        if ($mes == '10') {
            $mesEspañol = "Octubre";
        }
        if ($mes == '11') {
            $mesEspañol = "Noviembre";
        }
        if ($mes == '12') {
            $mesEspañol = "Diciembre";
        }

        return $mesEspañol;
    }
}