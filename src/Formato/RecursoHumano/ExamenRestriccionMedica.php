<?php


namespace App\Formato\RecursoHumano;


use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuExamenRestriccionMedica;
use App\Entity\RecursoHumano\RhuExamenRestriccionMedicaDetalle;
use App\Utilidades\Estandares;
use Brasa\RecursoHumanoBundle\Formatos\FormatoExamenRestriccionMedica;

class ExamenRestriccionMedica extends \FPDF
{
    public static $em;
    public static $codigoRestriccionMedica;
    public static $arExamenRestriccionMedica;
    public static $arExamenRestriccionMedicaDetalle;

    public function Generar($em, $codigoRestriccionMedica, $arExamenRestriccionMedica,$arExamenRestriccionMedicaDetalle ) {
        ob_clean();
        //$em = $miThis->getDoctrine()->getManager();
        self::$em = $em;
        self::$codigoRestriccionMedica = $codigoRestriccionMedica;
        self::$arExamenRestriccionMedica = $arExamenRestriccionMedica;
        self::$arExamenRestriccionMedicaDetalle = $arExamenRestriccionMedicaDetalle;
        $pdf = new ExamenRestriccionMedica();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("ExamenRestriccionMedica$codigoRestriccionMedica.pdf", 'D');
    }

    public function Header()
    {
        Estandares::generarEncabezado($this, "CORRESPONDENCIA ENVIADA", self::$em);
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles() {
        $arConfiguracionNomina = self::$em->getRepository(RhuConfiguracion::class)->find(1);
        $this->SetXY(10, 10);
        $this->Ln(10);
//        if($arConfiguracionNomina->getImprimirFirmaDigital() == 1){
//            $this->Image('imagenes/logos/firmaRuben.jpg' , 10 ,202, 50 , 30,'JPG');
//        }
    }


    public function Body($pdf) {
//        $pdf->SetXY(10, 80);
//        $pdf->SetFont('Arial', '', 10);
//
//        $arContenidoFormato = self::$em->getRepository(GenContenidoFormato)->find(21);
//        $arConfiguracion = self::$em->getRepository(GenConfiguracion)->find(1);
//        $arConfiguracionNomina = self::$em->getRepository(RhuConfiguracion)->find(1);
//        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
//        $pdf->Text(10, 60, utf8_decode($arConfiguracion->getCiudadRel()->getNombre()). ", ". strftime("%d de %B de %Y", strtotime(date('Y-m-d'))));
//        $arExamenRestriccionMedicaDetalle = self::$arExamenRestriccionMedicaDetalle;
//        $y = 140;
//        foreach ($arExamenRestriccionMedicaDetalle as $codigoExamenRestriccionMedica) {
//            $arExamenRestriccionDetalle = self::$em->getRepository(RhuExamenRestriccionMedicaDetalle::class)->find($codigoExamenRestriccionMedica);
//            $pdf->Text(10, $y, utf8_decode("- ".$arExamenRestriccionDetalle->getExamenRestriccionMedicaTipoRel()->getNombre()), 0, 0, 'L');
//            $y = $y + 5;
//        }
//
//        $arExamenRestriccion = self::$arExamenRestriccionMedica;
//        $arExamenRestriccion = self::$em->getRepository(RhuExamenRestriccionMedica::class)->find(self::$codigoRestriccionMedica);
//        //se reemplaza el contenido de la tabla contenido formato
//        $sustitucion1 = $arExamenRestriccion->getExamenRel()->getNombreCorto();
//        $sustitucion2 = $arExamenRestriccion->getExamenRel()->getIdentificacion();
//        $sustitucion3 = $arExamenRestriccion->getExamenRevisionMedicaTipoRel()->getNombre();
//        $sustitucion4 = $arExamenRestriccion->getExamenRel()->getFecha()->format('Y-m-d');
//        $feci = $arExamenRestriccion->getExamenRel()->getFecha();
//        $sustitucion4 = strftime("%d de ". $this->MesesEspañol($feci->format('m')) ." de %Y", strtotime($sustitucion4));
//        $sustitucion5 = $arConfiguracion->getNombreEmpresa();
//        $sustitucion6 = $arExamenRestriccion->getDias();
//        $cadena = $arContenidoFormato->getContenido();
//        $patron1 = '/#1/';
//        $patron2 = '/#2/';
//        $patron3 = '/#3/';
//        $patron4 = '/#4/';
//        $patron5 = '/#5/';
//        $patron6 = '/#6/';
//
//        $cadenaCambiada = preg_replace($patron1, $sustitucion1, $cadena);
//        $cadenaCambiada = preg_replace($patron2, $sustitucion2, $cadenaCambiada);
//        $cadenaCambiada = preg_replace($patron3, $sustitucion3, $cadenaCambiada);
//        $cadenaCambiada = preg_replace($patron4, $sustitucion4, $cadenaCambiada);
//        $cadenaCambiada = preg_replace($patron5, $sustitucion5, $cadenaCambiada);
//        $cadenaCambiada = preg_replace($patron6, $sustitucion6, $cadenaCambiada);
//
//        $pdf->MultiCell(0,5, $cadenaCambiada);
    }

    public function Footer() {
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

    public static function MesesEspañol($mes) {

        if ($mes == '01'){
            $mesEspañol = "Enero";
        }
        if ($mes == '02'){
            $mesEspañol = "Febrero";
        }
        if ($mes == '03'){
            $mesEspañol = "Marzo";
        }
        if ($mes == '04'){
            $mesEspañol = "Abril";
        }
        if ($mes == '05'){
            $mesEspañol = "Mayo";
        }
        if ($mes == '06'){
            $mesEspañol = "Junio";
        }
        if ($mes == '07'){
            $mesEspañol = "Julio";
        }
        if ($mes == '08'){
            $mesEspañol = "Agosto";
        }
        if ($mes == '09'){
            $mesEspañol = "Septiembre";
        }
        if ($mes == '10'){
            $mesEspañol = "Octubre";
        }
        if ($mes == '11'){
            $mesEspañol = "Noviembre";
        }
        if ($mes == '12'){
            $mesEspañol = "Diciembre";
        }

        return $mesEspañol;
    }

}