<?php


namespace App\Formato\RecursoHumano;


use App\Entity\General\GenCiudad;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenIdentificacion;
use App\Entity\RecursoHumano\RhuEmpleado;

class CertificadoIngreso extends \FPDF
{
    public static $em;
    public static $codigoEmpleado;
    public static $strFechaCertificado;
    public static $strFechaExpedicion;
    public static $strLugarExpedicion;
    public static $strAfc;
    public static $strCertifico1;
    public static $strCertifico2;
    public static $strCertifico3;
    public static $strCertifico4;
    public static $strCertifico5;
    public static $strCertifico6;
    public static $totalPrestacional;
    public static $floPension;
    public static $floSalud;
    public static $datFechaInicio;
    public static $datFechaFin;
    public static $totalCesantiaseIntereses;
    public static $douRetencion;
    public static $duoGestosRepresentacion;
    public static $douOtrosIngresos;
    public static $duoTotalIngresos;
    public static $totalPrestacionSocial;
    public static $floComisiones;
    public static $pagosPorHonorarios;
    public static $pagosPorServicios;
    public static $pagosPorViaticos;
    public static $floPensionJubilacion;
    public static $arrValoresRecibidos;
    public static $arrValoresRetenidos;
    public static $duoTotalValorRecibido;
    public static $duoTotalValorRetenido;
    public static $duoTotalRetencionGravable;

    public function Generar($em, $informacionFormato)
    {
        ob_clean();
        self::$em = $em;
        self::$codigoEmpleado = $informacionFormato['codigoEmpleado'];
        self::$strFechaCertificado = $informacionFormato['strFechaCertificado'];
        self::$strFechaExpedicion = $informacionFormato['strFechaExpedicion'];
        self::$strLugarExpedicion = $informacionFormato['strLugarExpedicion'];
        self::$strAfc = $informacionFormato['strAfc'];
        self::$strCertifico1 = $informacionFormato['strCertifico1'];
        self::$strCertifico2 = $informacionFormato['strCertifico2'];
        self::$strCertifico3 = $informacionFormato['strCertifico3'];
        self::$strCertifico4 = $informacionFormato['strCertifico4'];
        self::$strCertifico5 = $informacionFormato['strCertifico5'];
        self::$strCertifico6 = $informacionFormato['strCertifico6'];
        self::$totalPrestacional = $informacionFormato['totalPrestacional'];
        self::$floPension = $informacionFormato['floPension'];
        self::$floSalud = $informacionFormato['floSalud'];
        self::$datFechaInicio = $informacionFormato['datFechaInicio'];
        self::$datFechaFin = $informacionFormato['datFechaFin'];
        self::$totalCesantiaseIntereses = $informacionFormato['totalCesantiaseIntereses'];
        self::$douRetencion = $informacionFormato['douRetencion'];
        self::$duoGestosRepresentacion = $informacionFormato['duoGestosRepresentacion'];
        self::$douOtrosIngresos = $informacionFormato['douOtrosIngresos'];
        self::$duoTotalIngresos = $informacionFormato['duoTotalIngresos'];
        self::$totalPrestacionSocial = $informacionFormato['totalPrestacionSocial'];
        self::$floComisiones = $informacionFormato['floComisiones'];
        self::$pagosPorHonorarios = $informacionFormato['pagosPorHonorarios'];
        self::$pagosPorServicios = $informacionFormato['pagosPorServicios'];
        self::$pagosPorViaticos = $informacionFormato['pagosPorViaticos'];
        self::$floPensionJubilacion = $informacionFormato['floPensionJubilacion'];
        self::$arrValoresRecibidos = $informacionFormato['arrValoresRecibidos'];
        self::$arrValoresRetenidos = $informacionFormato['arrValoresRetenidos'];
        self::$duoTotalValorRetenido = $informacionFormato['duoTotalValorRecibido'];
        self::$duoTotalRetencionGravable = $informacionFormato['duoTotalRetencionGravable'];

        $pdf = new CertificadoIngreso();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $this->Body($pdf);
        $pdf->Output("Certificado{$informacionFormato['codigoContrato']}.pdf", "D");
    }

    public function Header()
    {
        $this->EncabezadoDetalles();
    }

    public function EncabezadoDetalles()
    {
        $arEmpleado = self::$em->getRepository(RhuEmpleado::class)->find(self::$codigoEmpleado);
        $arCiudad = self::$em->getRepository(GenCiudad::class)->find(self::$strLugarExpedicion);
        $arConfiguracion = self::$em->getRepository(GenConfiguracion::class)->find(1);
//        $arAcumulado = self::$em->getRepository(RhuCertificadoIngresoAcumulado::class)->findOneBy(array('codigoEmpleadoFk' => self::$codigoEmpleado));
        $ibp = 0;
        $salud = 0;
        $pension = 0;
//        if ($arAcumulado) {
//            if ($arAcumulado->getPeriodo() == self::$strFechaCertificado) {
//                $ibp = $arAcumulado->getAcumuladoIbp();
//                $salud = $arAcumulado->getAcumuladoSalud();
//                $pension = $arAcumulado->getAcumuladoSalud();
//            }
//        }

        $this->SetFillColor(255, 255, 255);
        $this->SetDrawColor(00, 99, 00);
        $this->SetFont('Arial', 'B', 12);
        //logo dian
        $this->SetXY(5, 5);
        $this->Line(5, 5, 35, 5);
        $this->Line(5, 5, 5, 20);
        $this->Line(5, 20, 35, 20);
        $this->Line(35, 5, 35, 20);
//        $this->Image('imagenes/logos/dian.png', 6, 6, 28, 12);
        //titulo del certificado
        $this->SetXY(35, 5);
        $this->Line(35, 5, 145, 5);
        $this->Cell(110, 7.5, "Certificado de ingresos y Retenciones para Personas", 0, 0, 'C', 1);
        $this->SetXY(35, 13);
        $this->Line(35, 20, 145, 20);
        $this->Cell(110, 6.5, utf8_decode("Naturales Empleados Año Gravable " . self::$strFechaCertificado), 0, 0, 'C', 1);
        //logo muisca
        $this->Line(145, 5, 175, 5);
        $this->Line(145, 5, 145, 20);
        $this->Line(145, 20, 175, 20);
        $this->Line(175, 5, 175, 20);
//        $this->Image('imagenes/logos/muisca.png', 146, 6, 28, 15);
        //logo 220
        $this->Line(175, 5, 205, 5);
        $this->Line(175, 5, 175, 20);
        $this->Line(175, 20, 205, 20);
        $this->Line(205, 5, 205, 20);
//        $this->Image('imagenes/logos/220.png', 175, 5, 30, 15);
        $this->SetXY(5, 20);
        $this->SetFont('Arial', '', 8);
        $this->Cell(100, 10, "Antes de diligenciar este formulario lea cuidadosamente las instrucciones", 1, 0, 'C', 1);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(100, 10, utf8_decode("4. Número de formulario"), 1, 0, 'L', 1);
        $this->SetXY(12, 30);
        //Retenedor
//        $this->Image('imagenes/logos/retenedor.jpg', 5, 31, 5, 20);
        $this->Line(5, 30, 5, 54);//linea derecha de la imagen retenedor
        $this->Cell(60, 6, utf8_decode("5. Número de identificación Tributaria (NIT)"), 1, 0, 'L', 1);
        $this->Cell(13, 6, utf8_decode("6. DV"), 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("7. Primer Apellido"), 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("8. Segundo Apellido"), 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("9. Primer Nombre"), 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("10. Otros Nombres"), 1, 0, 'L', 1);
        $this->Line(5, 54, 12, 54);//linea abajo de la imagen retenedor
        $this->SetXY(12, 36);
        $this->SetFont('Arial', '', 8);
        $this->Cell(55, 6, $arConfiguracion->getNit(), 1, 0, 'R', 1);
        $this->Cell(5, 6, " - ", 1, 0, 'C', 1);
        $this->Cell(13, 6, $arConfiguracion->getDigitoVerificacion(), 1, 0, 'C', 1);
        $this->Cell(30, 6, "", 1, 0, 'C', 1);
        $this->Cell(30, 6, "", 1, 0, 'C', 1);
        $this->Cell(30, 6, "", 1, 0, 'C', 1);
        $this->Cell(30, 6, "", 1, 0, 'C', 1);
        $this->SetXY(12, 42);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(193, 6, utf8_decode("11. Razón Social"), 1, 0, 'L', 1);
        $this->SetXY(12, 48);
        $this->SetFont('Arial', '', 8);
        $this->Cell(193, 6, utf8_decode($arConfiguracion->getNombre()), 1, 0, 'L', 1);
        //Asalariado
//        $this->Image('imagenes/logos/asociado.jpg', 4, 55, 7, 16);
        $this->Line(5, 54, 5, 72);//linea derecha de la imagen retenedor
        $this->SetXY(12, 54);
        $this->SetFont('Arial', 'b', 7);
        $this->Cell(31, 6, utf8_decode("24. Cod Tipo documento"), 1, 0, 'L', 1);
        $this->Cell(47, 6, utf8_decode("25. N° de documento de identificación"), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(115, 6, utf8_decode("Apellidos y Nombres"), 1, 0, 'L', 1);
        $this->SetXY(12, 60);
        $this->SetFont('Arial', '', 7);
        $arIdentificacion = self::$em->getRepository(GenIdentificacion::class)->find($arEmpleado->getCodigoIdentificacionFk());
        if ($arIdentificacion->getCodigoInterface() == 13) {
            $this->Cell(31, 12, $arIdentificacion->getCodigoInterface(), 1, 0, 'C', 1);
        }

        $this->Cell(47, 12, $arEmpleado->getNumeroIdentificacion(), 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(28, 6, utf8_decode($arEmpleado->getApellido1()), 1, 0, 'C', 1);
        $this->Cell(30, 6, utf8_decode($arEmpleado->getApellido2()), 1, 0, 'C', 1);
        $this->Cell(29, 6, utf8_decode($arEmpleado->getNombre1()), 1, 0, 'C', 1);
        $this->Cell(28, 6, utf8_decode($arEmpleado->getNombre2()), 1, 0, 'C', 1);
        $this->SetXY(90, 66);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(28, 6, utf8_decode("26. Primer Apellido"), 1, 0, 'L', 1);
        $this->Cell(30, 6, utf8_decode("27. Segundo Apellido"), 1, 0, 'L', 1);
        $this->Cell(29, 6, utf8_decode("28. Primer Nombre"), 1, 0, 'L', 1);
        $this->Cell(28, 6, utf8_decode("29. Otros Nombres"), 1, 0, 'L', 1);
        $this->Line(5, 72, 12, 72);//linea abajo de la imagen retenedor
        //periodo de certificación
        $this->SetXY(5, 72);
        $this->Cell(65, 6, utf8_decode("Periodo de la Certificación"), 1, 0, 'C', 1);
        $this->Cell(33, 6, utf8_decode("32. Fecha Expedición"), 1, 0, 'L', 1);
        $this->Cell(60, 6, utf8_decode("33. Lugar donde se practicó la retención"), 1, 0, 'L', 1);
        $this->Cell(20, 6, utf8_decode("34. Cod Dpto"), 1, 0, 'L', 1);
        $this->Cell(22, 6, utf8_decode("35. Cod Ciudad"), 1, 0, 'L', 1);
        $this->SetXY(5, 78);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 6, utf8_decode("30. DE: " . self::$datFechaInicio . "  31. A: " . self::$datFechaFin . ""), 1, 0, 'C', 1);
        $this->Cell(33, 6, self::$strFechaExpedicion->format('Y/m/d'), 1, 0, 'C', 1);
        $this->Cell(60, 6, utf8_decode($arCiudad->getNombre()), 1, 0, 'C', 1);
        $this->Cell(20, 6, substr($arCiudad->getCodigoDane(), 0, 2), 1, 0, 'C', 1);  // bcd
        $this->Cell(22, 6, substr($arCiudad->getCodigoDane(), 2, 8), 1, 0, 'C', 1);  // bcd
        //numero de sucursales asociadas
        $this->SetXY(5, 84);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(178, 6, utf8_decode("36. Número de agencias, sucursales, filiales o subsidiarias de la empresa retenedora cuyos montos de retención se consolidan:"), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(22, 6, utf8_decode(""), 1, 0, 'R', 1);
        //Concepto de los ingresos
        $this->SetXY(5, 90);
        $this->SetFont('Arial', 'b', 8);
        $this->SetFillColor(196, 215, 155);
        $this->Cell(158, 4, utf8_decode("Concepto de los Ingresos"), 1, 0, 'C', 1);
        $this->Cell(42, 4, utf8_decode("Valor"), 1, 0, 'C', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetXY(5, 94);
        $this->SetFont('Arial', '', 8);
        $this->Cell(158, 4, utf8_decode("Pagos por salarios o emolumentos eclesiásticos"), 1, 0, 'L', 1);// Version 2017
        $this->Cell(8, 4, utf8_decode("37."), 1, 0, 'C', 1);// Version 2017
        $this->Cell(34, 4, round(self::$totalPrestacional), 1, 0, 'R', 1);// Version 2017
        $this->SetXY(5, 98);
        $this->Cell(158, 4, utf8_decode("Pagos por honorarios"), 1, 0, 'L', 1);//Version 2017
        $this->Cell(8, 4, utf8_decode("38."), 1, 0, 'C', 1);//Version 2017
        $this->Cell(34, 4, round(self::$pagosPorHonorarios), 1, 0, 'R', 1);//Version 2017
        $this->SetXY(5, 102);
        $this->Cell(158, 4, utf8_decode("Pagos por servicios"), 1, 0, 'L', 1);//Version 2017
        $this->Cell(8, 4, utf8_decode("39."), 1, 0, 'C', 1);//Version 2017
        $this->Cell(34, 4, round(self::$pagosPorServicios), 1, 0, 'R', 1);//Version 2017
        $this->SetXY(5, 106);
        $this->Cell(158, 4, utf8_decode("Pagos por comisiones"), 1, 0, 'L', 1);//Version 2017
        $this->Cell(8, 4, utf8_decode("40."), 1, 0, 'C', 1);//Version 2017
        $this->Cell(34, 4, round(self::$floComisiones), 1, 0, 'R', 1);//Version 2017
        $this->SetXY(5, 110);
        $this->Cell(158, 4, utf8_decode("Pagos por prestaciones sociales"), 1, 0, 'L', 1);//Version 2017
        $this->Cell(8, 4, utf8_decode("41."), 1, 0, 'C', 1);//Version 2017
        $this->Cell(34, 4, round(self::$totalPrestacionSocial), 1, 0, 'R', 1);//Version 2017
        $this->SetXY(5, 114);
        $this->Cell(158, 4, utf8_decode("Pagos por viáticos"), 1, 0, 'L', 1);//Version 2017
        $this->Cell(8, 4, utf8_decode("42."), 1, 0, 'C', 1);//Version 2017
        $this->Cell(34, 4, round(self::$pagosPorViaticos), 1, 0, 'R', 1);//Version 2017
        $this->SetXY(5, 118);
        $this->Cell(158, 4, utf8_decode("Gastos de representación"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("43."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$duoGestosRepresentacion), 1, 0, 'R', 1);
        $this->SetXY(5, 122);
        $this->Cell(158, 4, utf8_decode("Pagos por compensaciones por el trabajo asociado cooperativo"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("44."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$duoGestosRepresentacion), 1, 0, 'R', 1);
        $this->SetXY(5, 126);
        $this->Cell(158, 4, utf8_decode("Otros pagos"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("45."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$douOtrosIngresos), 1, 0, 'R', 1);
        $this->SetXY(5, 130);
        $this->Cell(158, 4, utf8_decode("Cesantías e intereses de cesantías efectivamente pagadas, consignadas o reconocidas en el período"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("46."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$totalCesantiaseIntereses), 1, 0, 'R', 1);
        $this->SetXY(5, 134);
        $this->Cell(158, 4, utf8_decode("Pensiones de jubilación vejez o invalidez"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("47."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$floPensionJubilacion), 1, 0, 'R', 1);
        $this->SetXY(5, 138);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(158, 4, utf8_decode("Total de ingresos brutos (Sume casillas 37 a 47)"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("48."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$duoTotalIngresos), 1, 0, 'R', 1);
        //Concepto a los aportes
        $this->SetXY(5, 142);
        $this->SetFillColor(196, 215, 155);
        $this->Cell(158, 4, utf8_decode("Concepto de los aportes"), 1, 0, 'C', 1);
        $this->Cell(42, 4, utf8_decode("Valor"), 1, 0, 'C', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetXY(5, 146);
        $this->SetFont('Arial', '', 8);
        $this->Cell(158, 4, utf8_decode("Aportes obligatorios por salud"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("49."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$floSalud), 1, 0, 'R', 1);
        $this->SetXY(5, 150);
        $this->SetFont('Arial', '', 8);
        $this->Cell(158, 4, utf8_decode("Aportes obligatorios a fondos de pensiones y solidaridad pensional y Aportes voluntarios al - RAIS"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("50."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$floPension), 1, 0, 'R', 1);
        $this->SetXY(5, 154);
        $this->SetFont('Arial', '', 8);
        $this->Cell(158, 4, utf8_decode("Aportes obligatorios a fondos de pensiones voluntarias"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("51."), 1, 0, 'C', 1);
        $this->Cell(34, 4, 0, 1, 0, 'R', 1);//Pendiente revisar version 2017
        $this->SetXY(5, 158);
        $this->SetFont('Arial', '', 8);
        $this->Cell(158, 4, utf8_decode("Aportes a cuentas AFC"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("52."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$strAfc), 1, 0, 'R', 1);//Version 2017
        $this->SetXY(5, 162);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(158, 4, utf8_decode("Valor de la retención en la fuente por rentas de trabajos y pensiones"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("53."), 1, 0, 'C', 1);
        $this->Cell(34, 4, round(self::$douRetencion), 1, 0, 'R', 1);
        //firma del retenedor
        $this->SetXY(5, 166);
        $this->Cell(200, 4, utf8_decode("Nombre del pagador o agente retenedor "), 1, 0, 'L', 1);
        $this->SetXY(5, 170);
        $this->SetFillColor(196, 215, 155);
        $this->Cell(200, 3, utf8_decode("Datos a cargo del trabajador o pensionado "), 1, 0, 'C', 1);
        $this->SetFillColor(255, 255, 255);
        //datos a cargo del asalariado
        $this->SetXY(5, 173);
        $this->Cell(130, 4, utf8_decode("Concepto de otros ingresos"), 1, 0, 'C', 1);
        $this->Cell(35, 4, utf8_decode("Valor Recibido"), 1, 0, 'C', 1);
        $this->Cell(35, 4, utf8_decode("Valor Retenido"), 1, 0, 'C', 1);
        $this->SetXY(5, 177);
        $this->SetFont('Arial', '', 8);
        $this->Cell(130, 4, utf8_decode("Arrendamientos"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("54."), 1, 0, 'C', 1);
        $arrendamientos=self::$arrValoresRecibidos['arrendamientos'];
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRecibidos['arrendamientos']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($arrendamientos), 1, 0, 'R', 1);
        $this->Cell(8, 4, utf8_decode("61."), 1, 0, 'C', 1);
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRetenidos['arrendamientos']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($arrendamientos), 1, 0, 'R', 1);
        $this->SetXY(5, 181);
        $this->Cell(130, 4, utf8_decode("Honorarios, comisiones y servicios"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("55."), 1, 0, 'C', 1);
        $honorarios=self::$arrValoresRecibidos['honorarios'];

//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRecibidos['honorarios']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($honorarios), 1, 0, 'R', 1);
        $this->Cell(8, 4, utf8_decode("62."), 1, 0, 'C', 1);
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRetenidos['honorarios']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($honorarios), 1, 0, 'R', 1);
        $this->SetXY(5, 185);
        $this->Cell(130, 4, utf8_decode("Intereses y rendimientos financieros"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("56."), 1, 0, 'C', 1);
        $intereses=self::$arrValoresRecibidos['intereses'];
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRecibidos['intereses']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($intereses), 1, 0, 'R', 1);
        $this->Cell(8, 4, utf8_decode("63."), 1, 0, 'C', 1);
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRetenidos['intereses']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($intereses), 1, 0, 'R', 1);
        $this->SetXY(5, 189);
        $this->Cell(130, 4, utf8_decode("Enajenación de activos fijos"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("57."), 1, 0, 'C', 1);
        $enajenacion=self::$arrValoresRecibidos['enajenacion'];
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRecibidos['enajenacion']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($enajenacion), 1, 0, 'R', 1);
        $this->Cell(8, 4, utf8_decode("64."), 1, 0, 'C', 1);
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRetenidos['enajenacion']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($enajenacion), 1, 0, 'R', 1);
        $this->SetXY(5, 193);
        $this->Cell(130, 4, utf8_decode("Loterias, rifas, apuestas y similares"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("58."), 1, 0, 'C', 1);
        $loterias=self::$arrValoresRecibidos['loterias'];
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRecibidos['loterias']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($loterias), 1, 0, 'R', 1);
        $this->Cell(8, 4, utf8_decode("65."), 1, 0, 'C', 1);
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRetenidos['loterias']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($loterias), 1, 0, 'R', 1);
        $this->SetXY(5, 197);
        $otros=self::$arrValoresRecibidos['otros'];
        $this->Cell(130, 4, utf8_decode("Otros"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("59."), 1, 0, 'C', 1);
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRecibidos['otros']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($otros), 1, 0, 'R', 1);
        $this->Cell(8, 4, utf8_decode("66."), 1, 0, 'C', 1);
//        $this->Cell(27, 4, utf8_decode(self::$arrValoresRetenidos['otros']), 1, 0, 'R', 1);
        $this->Cell(27, 4, utf8_decode($otros), 1, 0, 'R', 1);
        $this->SetXY(5, 201);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(130, 4, utf8_decode("Totales (valor recibido: Suma casillas 54 a 59). (valor retenido: Suma casillas 61 a 66)"), 1, 0, 'L', 1);
        $this->Cell(8, 4, utf8_decode("60."), 1, 0, 'C', 1);
        $this->Cell(27, 4, utf8_decode(self::$duoTotalValorRecibido), 1, 0, 'R', 1);
        $this->Cell(8, 4, utf8_decode("67."), 1, 0, 'C', 1);
        $this->Cell(27, 4, utf8_decode(self::$duoTotalValorRetenido), 1, 0, 'R', 1);
        $this->SetXY(5, 205);
        $this->Cell(165, 5, utf8_decode("Total retenciones año gravable " . self::$strFechaCertificado . " (Suma casillas 53 + 67)"), 1, 0, 'L', 1);
        $this->Cell(8, 5, utf8_decode("68."), 1, 0, 'C', 1);
        $this->Cell(27, 5, utf8_decode(self::$duoTotalRetencionGravable), 1, 0, 'R', 1);
        //identificacion de bienes
        $this->SetXY(5, 210);
        $this->SetFillColor(196, 215, 155);
        $this->Cell(7, 5, utf8_decode("Item"), 1, 0, 'C', 1);
        $this->Cell(158, 5, utf8_decode("69. Identificación de los bienes poseidos"), 1, 0, 'C', 1);
        $this->Cell(35, 5, utf8_decode("70. Valor patrimonial"), 1, 0, 'C', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('Arial', '', 8);
        $this->SetXY(5, 215);
        $this->Cell(7, 4, utf8_decode("1"), 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'R', 1);
        $this->SetXY(5, 219);
        $this->Cell(7, 4, utf8_decode("2"), 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'R', 1);
        $this->SetXY(5, 223);
        $this->Cell(7, 4, utf8_decode("3"), 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'R', 1);
        $this->SetXY(5, 227);
        $this->Cell(7, 4, utf8_decode("4"), 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'R', 1);
        $this->SetXY(5, 231);
        $this->Cell(7, 4, utf8_decode("5"), 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'R', 1);
        $this->SetXY(5, 235);
        $this->Cell(7, 4, utf8_decode("6"), 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'R', 1);
        $this->SetXY(5, 239);
        $this->Cell(7, 4, utf8_decode("7"), 1, 0, 'C', 1);
        $this->Cell(158, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'R', 1);

        $this->SetXY(5, 243);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(165, 4, utf8_decode("Deudas vigentes a 31 de Diciembre de " . self::$strFechaCertificado), 1, 0, 'L', 1);
        $this->Cell(9, 4, utf8_decode("71."), 1, 0, 'C', 1);
        $this->Cell(26, 4, utf8_decode("-"), 1, 0, 'R', 1);
        //Identificación de las personas dependientes
        $this->SetXY(5, 247);
        $this->SetFillColor(196, 215, 155);
        $this->Cell(200, 4, utf8_decode("Identificación de las personas dependientes de acuerdo al páragrafo 2 del articulo 387 del E.T."), 1, 0, 'C', 1);
        $this->SetFillColor(255, 255, 255);
        $this->SetXY(5, 251);
        $this->Cell(37, 4, utf8_decode("72. C.C. o NIT"), 1, 0, 'C', 1);
        $this->Cell(128, 4, utf8_decode("73. Apellidos y Nombres"), 1, 0, 'C', 1);
        $this->Cell(35, 4, utf8_decode("74. Parentesco"), 1, 0, 'C', 1);
        $this->SetXY(5, 255);
        $this->SetFont('Arial', '', 8);
        $this->Cell(37, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(128, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->SetXY(5, 259);
        $this->Cell(37, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(128, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->SetXY(5, 263);
        $this->Cell(37, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(128, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->Cell(35, 4, utf8_decode(""), 1, 0, 'L', 1);
        $this->SetXY(5, 267);
        $this->Cell(160, 4, utf8_decode("Certifico que durante el año gravable " . self::$strFechaCertificado . ":"), 1, 0, 'L', 1);
        $this->SetFont('Arial', 'b', 8);
        $this->Cell(40, 4, utf8_decode("Firma del asalariado"), 1, 0, 'C', 1);
        $this->SetXY(5, 271);
        $this->SetFont('Arial', '', 7);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico1), 1, 0, 'L', 1);
        $this->Cell(40, 21, utf8_decode(""), 1, 0, 'L', 1);
        $this->SetXY(5, 274);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico2), 1, 0, 'L', 1);
        $this->SetXY(5, 277);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico3), 1, 0, 'L', 1);
        $this->SetXY(5, 280);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico4), 1, 0, 'L', 1);
        $this->SetXY(5, 283);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico5), 1, 0, 'L', 1);
        $this->SetXY(5, 286);
        $this->Cell(160, 3, utf8_decode(self::$strCertifico6), 1, 0, 'L', 1);
        $this->SetXY(5, 289);
        $this->SetFont('Arial', '', 8);
        $this->Cell(160, 3, utf8_decode("Por lo tanto manifiesto que no estoy obligado a presentar declaración de renta y complementarios por el año gravable " . self::$strFechaCertificado . "."), 1, 0, 'L', 1);
        $this->SetFont('Arial', '', 6);
        $this->SetXY(5, 293);
        $this->Cell(200, 2, utf8_decode("NOTA: Este certificado sustituye para todos los efectos legales la declaración de Renta y Complementario para el trabajador o pensionado que lo firme. "), 0, 0, 'C');
    }

    public function Body($pdf)
    {

    }

    public function Footer()
    {


    }

}