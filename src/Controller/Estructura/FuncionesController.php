<?php

namespace App\Controller\Estructura;


use App\Entity\General\GenNotificacion;
use App\Entity\General\GenNotificacionTipo;
use App\Utilidades\BaseDatos;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

include_once(realpath(__DIR__ . "/../../../public/plugins/phpqrcode/phpqrcode/qrlib.php"));

/**
 * Class Mensajes
 * @package App\Util
 */
final class FuncionesController
{


    private static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new FuncionesController();
        }
        return $instance;
    }

    /**
     * @param $dateFecha \DateTime
     * @param string $intDias
     * @param string $intMeses
     * @param string $intAnios
     * @return \DateTime|false
     */
    public function sumarDiasFecha($dateFecha, $intDias = '', $intMeses = '', $intAnios = '')
    {
        if ($dateFecha instanceof \DateTime) {
            $fecha = $dateFecha->format('Y-m-j');
        } else {
            $fecha = $dateFecha;
        }
        if ($intDias != '') {
            $nuevafecha = strtotime('+' . $intDias . ' day', strtotime($fecha));
        }
        if ($intMeses != '') {
            $nuevafecha = strtotime('+' . $intMeses . ' month', strtotime($fecha));
        }
        if ($intAnios != '') {
            $nuevafecha = strtotime('+' . $intAnios . ' year', strtotime($fecha));
        }
        $nuevafecha = date('Y-m-j', $nuevafecha);
        $dateNuevaFecha = date_create($nuevafecha);
        return $dateNuevaFecha;
    }

    public function sumarDiasFechaNumero($intDias, $dateFecha)
    {
        $fecha = $dateFecha->format('Y-m-j');
        $nuevafecha = strtotime('+' . $intDias . ' day', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);
        $dateNuevaFecha = date_create($nuevafecha);
        return $dateNuevaFecha;
    }

    /**
     * @param $index
     * @return string
     */
    public static function indexAColumna($index)
    {
        $letra = '';
        switch ($index) {
            case 1:
                $letra = 'A';
                break;
            case 2:
                $letra = 'B';
                break;
            case 3:
                $letra = 'C';
                break;
            case 4:
                $letra = 'D';
                break;
            case 5:
                $letra = 'E';
                break;
            case 6:
                $letra = 'F';
                break;
            case 7:
                $letra = 'G';
                break;
            case 8:
                $letra = 'H';
                break;
            case 9:
                $letra = 'I';
                break;
            case 10:
                $letra = 'J';
                break;
            case 11:
                $letra = 'K';
                break;
        }
        return $letra;
    }

    //5,0,10
    public static function RellenarNr($Nro, $Str, $NroCr, $pos = 'D')
    {
        $Nro = utf8_decode($Nro);
        $Longitud = strlen($Nro); //5
        $Nc = $NroCr - $Longitud; //5
        for ($i = 0; $i < $Nc; $i++) {
            if ($pos == 'I') {
                $Nro = $Str . $Nro;
            } else {
                $Nro = $Nro . $Str;
            }
        }
        return (string)$Nro;
    }

    public static function ultimoDia($fecha)
    {
        $fechaDesde = $fecha->format('Y-m') . '-01';
        $aux = date('Y-m-d', strtotime("{$fechaDesde} + 1 month"));
        $fechaHasta = date('Y-m-d', strtotime("{$aux} - 1 day"));
        return $fechaHasta;
    }

    public static function primerDia($fecha)
    {
        $fechaDesde = $fecha->format('Y-m') . '-01';
        $fechaDesde = date_create($fechaDesde);
        return $fechaDesde;
    }

    public static function desdeHastaAnioMes($anio, $mes)
    {
        $fecha = date_create($anio . '-' . $mes . '-01');
        $fechaDesde = $fecha->format('Y-m') . '-01';
        $aux = date('Y-m-d', strtotime("{$fechaDesde} + 1 month"));
        $fechaHasta = date('Y-m-d', strtotime("{$aux} - 1 day"));
        return ['fechaDesde' => date_create($fechaDesde), 'fechaHasta' => date_create($fechaHasta)];
    }

    public static function crearNotificacion($id, $descripcion = '', $arrUsuarios = array())
    {
        try {
            $em = BaseDatos::getEm();
            $arNotificacionTipo = $em->getRepository(GenNotificacionTipo::class)->find($id);
            if ($arNotificacionTipo) {
                if ($arNotificacionTipo->getEstadoActivo()) {
                    $usuarios = json_decode($arNotificacionTipo->getUsuarios(), true);
                    if ($arrUsuarios) {
                        if ($usuarios) {
                            $usuarios = array_merge($usuarios, $arrUsuarios);
                        } else {
                            $usuarios = $arrUsuarios;
                        }
                        $usuarios = array_unique($usuarios);
                    }
                    if ($usuarios) {
                        foreach ($usuarios as $user) {
                            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->findOneBy(['username' => $user]);
                            if ($arUsuario) {
                                $arNotificacion = new GenNotificacion();
                                $arNotificacion->setFecha(new \DateTime('now'));
                                $arNotificacion->setNotificacionTipoRel($arNotificacionTipo);
                                $arNotificacion->setCodigoUsuarioReceptorFk($arUsuario->getUsername());
                                $arNotificacion->setDescripcion($descripcion);
                                $arUsuario->setNotificacionesPendientes($arUsuario->getNotificacionesPendientes() + 1);
                                $em->persist($arUsuario);
                                $em->persist($arNotificacion);
                            }
                        }
                        $em->flush();
                    }
                }
            }

        } catch (\Exception $exception) {
            //Error
        }
    }

    public static function generarSession($modulo, $nombre, $claseNombre, $formFiltro)
    {
        $namespaceType = "\\App\\Form\\Type\\{$modulo}\\{$nombre}Type";
        $campos = json_decode($namespaceType::getEstructuraPropiedadesFiltro(), true);
        $session = new Session();
        foreach ($campos as $campo) {
            if (isset($campo['relacion'])) {
                $relacion = explode('.', $campo['child']);
                $campo['child'] = $relacion[0] . $relacion[1];
            }

            if (substr($campo['child'], -2) == "Fk" && $campo['tipo'] == "EntityType") {
                $funcion = isset($campo['pk']) ? $campo['pk'] : substr($campo['child'], 0, -2) . 'Pk';
                $session->set($claseNombre . '_' . $campo['child'], $formFiltro->get($campo['child'])->getData() != "" ? call_user_func(array($formFiltro->get($campo['child'])->getData(), 'get' . $funcion)) : "");
            } else if (strlen($campo['child']) >= 5 && substr($campo['child'], 0, 5) == "fecha") {
                $session->set($claseNombre . '_' . $campo['child'], $formFiltro->get($campo['child'])->getData() != null ? $formFiltro->get($campo['child'])->getData()->format('Y-m-d') : null);
            } else {
                $session->set($claseNombre . '_' . $campo['child'], $formFiltro->get($campo['child'])->getData());
            }
        }
    }

    public static function solicitudesGet($ruta)
    {
        try {

            $em = BaseDatos::getEm();
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $em->getRepository('App:General\GenConfiguracion')->find(1)->getWebServiceCesioUrl() . $ruta);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $respuesta = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $respuesta;
        } catch (\Exception $exception) {
            return [
                'estado' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }

    public static function solicitudesPost($datos = [], $ruta)
    {
        try {
            $em = BaseDatos::getEm();
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $datos,
                //CURLOPT_URL => 'http://localhost/cromo/public/index.php/documental/api/masivo/masivo/1',
                CURLOPT_URL => $em->getRepository('App:General\GenConfiguracion')->find(1)->getWebServiceCesioUrl() . $ruta,
            ));
            $respuesta = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return $respuesta;
        } catch (\Exception $exception) {
            return [
                'estado' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }


    /**
     * @param
     * @return string
     */
    public static function codigoQr($contenido)
    {
        $em = BaseDatos::getEm();

        //Declaramos una carpeta temporal para guardar la imagenes generadas
        $dir = $em->getRepository('App\Entity\General\GenConfiguracion')->find(1)->getRutaTemporal();
//        dump($dir);
        //Si no existe la carpeta la creamos
        if (!file_exists($dir))
            mkdir($dir, 0777);


        //Declaramos la ruta y nombre del archivo a generar
        $filename = $dir . 'qrTest.png';


        //Parametros de Configuración

        $tamano = 10; //Tamaño de Pixel
        $level = 'L'; //Precisión Baja
        $framSize = 3; //Tamaño en blanco

        //Enviamos los parametros a la Función para generar código QR
        \QRcode::png($contenido, $filename, $level, $tamano, $framSize);
//        dump($dir.basename($filename));
        return $dir . basename($filename);
    }

    public static function mesEspanol($mesNumero)
    {
        $mesTexto = '';
        switch ($mesNumero) {
            case 1:
                $mesTexto = "Enero";
                break;
            case 2:
                $mesTexto = "Febrero";
                break;
            case 3:
                $mesTexto = "Marzo";
                break;
            case 4:
                $mesTexto = "Abril";
                break;
            case 5:
                $mesTexto = "Mayo";
                break;
            case 6:
                $mesTexto = "Junio";
                break;
            case 7:
                $mesTexto = "Julio";
                break;
            case 8:
                $mesTexto = "Agosto";
                break;
            case 9:
                $mesTexto = "Septiembre";
                break;
            case 10:
                $mesTexto = "Octubre";
                break;
            case 11:
                $mesTexto = "Novimienbre";
                break;
            case 12:
                $mesTexto = "Diciembre";
                break;
        }
        return $mesTexto;
    }

    public static function diasPrestaciones($dateFechaDesde, $dateFechaHasta)
    {
        $objFunciones = new FuncionesController();
        $intDias = 0;
        $intAnioInicio = $dateFechaDesde->format('Y');
        $intAnioFin = $dateFechaHasta->format('Y');
        $intAnios = 0;
        $intMeses = 0;
        if ($dateFechaHasta >= $dateFechaDesde) {
            if ($dateFechaHasta->format('d') == '31' && ($dateFechaHasta == $dateFechaDesde)) {
                $intDias = 0;
            } else {
                if ($intAnioInicio != $intAnioFin) {
                    $intDiferenciaAnio = $intAnioFin - $intAnioInicio;
                    if (($intDiferenciaAnio) > 1) {
                        $intAnios = $intDiferenciaAnio - 1;
                        $intAnios = $intAnios * 12 * 30;
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioInicio . '-12-30' . "00:00");
                    if ($dateFechaDesde->format('n') != $dateFechaTemporal->format('n')) {
                        $intMeses = $dateFechaTemporal->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $objFunciones->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $objFunciones->diasPrestacionesMes($dateFechaTemporal->format('j'), 0);
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if ($dateFechaTemporal->format('j') == $dateFechaDesde->format('j')) {
                            $intDias = 0;
                        } else {
                            $intDias = 1 + ($dateFechaTemporal->format('j') - $dateFechaDesde->format('j'));
                        }
                    }

                    $dateFechaTemporal = date_create_from_format('Y-m-d H:i', $intAnioFin . '-01-01' . "00:00");
                    if ($dateFechaTemporal->format('n') != $dateFechaHasta->format('n')) {
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaTemporal->format('n') - 1;
                        $intDiasInicio = $objFunciones->diasPrestacionesMes($dateFechaTemporal->format('j'), 1);
                        $intDiasFinal = $objFunciones->diasPrestacionesMes($dateFechaHasta->format('j'), 0);
                        $intDias += $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        $intDias += 1 + ($dateFechaHasta->format('j') - $dateFechaTemporal->format('j'));
                    }
                    $intDias += $intAnios;

                    //Se adiciona esta parte para cuando la fecha finaliza el 28/29 febrero sume 1 o 2 dias (09/03/2018-mario)
                    $diaFinMes = cal_days_in_month(CAL_GREGORIAN, $dateFechaHasta->format("m"), $dateFechaHasta->format("Y"));

                    $fecha_inicio = strtotime($dateFechaDesde->format('Y-m-d'));
                    $fecha_fin = strtotime($dateFechaHasta->format('Y-m-d'));
                    $fecha = strtotime($dateFechaHasta->format("Y") . "-02-{$diaFinMes}");
                    if ($dateFechaHasta->format("m") == "02") {
                        if (($fecha >= $fecha_inicio) && ($fecha < $fecha_fin) || $fecha == $fecha_fin) {
                            if ($diaFinMes == 28) {
                                $intDias += 2;
                            } else {
                                $intDias++;
                            }
                        }
                    }
                } else {
                    if ($dateFechaDesde->format('n') != $dateFechaHasta->format('n')) {
                        $intMeses = $dateFechaHasta->format('n') - $dateFechaDesde->format('n') - 1;
                        $intDiasInicio = $objFunciones->diasPrestacionesMes($dateFechaDesde->format('j'), 1);
                        $intDiasFinal = $objFunciones->diasPrestacionesMes($dateFechaHasta->format('j'), 0);
                        $intDias = $intDiasInicio + $intDiasFinal + ($intMeses * 30);
                    } else {
                        if ($dateFechaHasta->format('j') == 31) {
                            $intDias = ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));
                        } else {
                            $intDias = 1 + ($dateFechaHasta->format('j') - $dateFechaDesde->format('j'));
                        }

                    }
                }
            }
        } else {
            $intDias = 0;
        }
        return $intDias;
    }

    public static function diasPrestacionesMes($intDia, $intDesde)
    {
        $intDiasDevolver = 0;
        if ($intDesde == 1) {
            $intDiasDevolver = 31 - $intDia;
        } else {
            $intDiasDevolver = $intDia;
            if ($intDia == 31) {
                $intDiasDevolver = 30;
            }
        }
        return $intDiasDevolver;
    }

    public static function devuelvePosicionInicialSecuencia($posicionInicial, $intervalo, $strFechaDesde, $strFechaHasta)
    {
        if ($intervalo == 0) {
            $intervalo = 1;
        }
        $posicion = $posicionInicial;

        $dateFechaHasta = date_create($strFechaHasta);
        $dateFechaDesde = date_create($strFechaDesde);
        $strFecha = $dateFechaDesde->format('Y-m-d');
        if ($dateFechaDesde < $dateFechaHasta) {
            while ($strFecha != $strFechaHasta) {
                $nuevafecha = strtotime('+1 day', strtotime($strFecha));
                $strFecha = date('Y-m-d', $nuevafecha);
                $posicion++;
                if ($posicion > $intervalo) {
                    $posicion = 1;
                }
            }
            if ($posicion > $intervalo) {
                $posicion = 1;
            }
        }
        return $posicion;

    }

    public static function turnosSecuencia($arSecuencia)
    {
        if ($arSecuencia == null) {
            return [];
        }
        $arrSecuencias = array();
        $dias = $arSecuencia->getDias();
        for ($i = 1; $i <= $dias; $i++) {
            $dia = call_user_func_array([$arSecuencia, "getDia{$i}"], []);
            $arrSecuencias[$i] = $dia;
        }
        $total = count($arrSecuencias);
        if ($dias - $total > 0) {
            for ($i = $total; $i < $dias; $i++) {
                $arrSecuencias[$total] = null;
            }
        }
        return $arrSecuencias;
    }

    public static function validacionDiaSemama($dia, $arSecuenciaTurno)
    {
        $diasSemana = [
            'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo', 'festivo', 'domingoFestivo',
        ];
        $nombreDia = ucfirst($diasSemana[intval($dia) - 1]);
        $turno = call_user_func_array([$arSecuenciaTurno, "get{$nombreDia}"], []);
        return $turno != null ? $turno : false;
    }

    /**
     * Esta función válida que un día sea festivo.
     * @param string $fecha
     * @param array $arrFestivos
     * @param \Brasa\TurnoBundle\Entity\TurSecuencia $arSecuencia
     * @return boolean
     */
    public static function validacionFestivo($fecha, $arrFestivos, $arSecuencia)
    {
        # Si no es festivo.
        if (!in_array($fecha, $arrFestivos) || $arSecuencia->getFestivo() == null) {
            return false;
        }
        return $arSecuencia->getFestivo();
    }

    public static function validacionDomingoFestivo($fecha, $arrFestivos, $arSecuencia)
    {
        $nroDia = intval($fecha->format('N'));
        $diaActual = $fecha->format("Y-m-d");
        $diaSiguiente = date("Y-m-d", strtotime($diaActual . " + 1 days"));
        if ($nroDia != 7 && !in_array($diaSiguiente, $arrFestivos)) {
            return false;
        }
        return $arSecuencia->getDomingoFestivo();
    }

    public static function diasMes($fecha, $arFestivos)
    {
        $strAnioMes = $fecha->format('Y/m');
        $arrDiaSemana = array();
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = "";
            switch ($dateFecha->format('N')) {
                case 1:
                    $diaSemana = "l";
                    break;
                case 2:
                    $diaSemana = "m";
                    break;
                case 3:
                    $diaSemana = "i";
                    break;
                case 4:
                    $diaSemana = "j";
                    break;
                case 5:
                    $diaSemana = "v";
                    break;
                case 6:
                    $diaSemana = "s";
                    break;
                case 7:
                    $diaSemana = "d";
                    break;
            }
            $arrDiasFestivos = array_map(function ($arFestivo) {
                return $arFestivo['fecha']->format('Y-m-d');
            }, $arFestivos);
            $fechaActual = $fecha->format("Y-m-" . ($i < 10 ? '0' . $i : $i));
            $boolFestivo = ($diaSemana == 'd' || in_array($fechaActual, $arrDiasFestivos));
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana, 'festivo' => $boolFestivo);
        }
        return $arrDiaSemana;
    }

    public static function verificarFestivoArray($arrFestivos, $fecha)
    {
        $festivo = false;
        foreach ($arrFestivos as $arrFestivo) {
            if ($arrFestivo['fecha'] == $fecha) {
                $festivo = true;
            }
        }
        return $festivo;
    }

    public static function boolTexto($dato) {
        $resultado = "";
        if($dato == 1) {
            $resultado = "SI";
        }
        if($dato == 0) {
            $resultado = "NO";
        }
        return $resultado;
    }

    public static function devolverNumeroLetras($num, $fem = true, $dec = true)
    {

        //if (strlen($num) > 14) die("El n?mero introducido es demasiado grande");

        $matuni[2] = "dos";

        $matuni[3] = "tres";

        $matuni[4] = "cuatro";

        $matuni[5] = "cinco";

        $matuni[6] = "seis";

        $matuni[7] = "siete";

        $matuni[8] = "ocho";

        $matuni[9] = "nueve";

        $matuni[10] = "diez";

        $matuni[11] = "once";

        $matuni[12] = "doce";

        $matuni[13] = "trece";

        $matuni[14] = "catorce";

        $matuni[15] = "quince";

        $matuni[16] = "dieciseis";

        $matuni[17] = "diecisiete";

        $matuni[18] = "dieciocho";

        $matuni[19] = "diecinueve";

        $matuni[20] = "veinte";

        $matunisub[2] = "dos";

        $matunisub[3] = "tres";

        $matunisub[4] = "cuatro";

        $matunisub[5] = "quin";

        $matunisub[6] = "seis";

        $matunisub[7] = "sete";

        $matunisub[8] = "ocho";

        $matunisub[9] = "nove";


        $matdec[2] = "veint";

        $matdec[3] = "treinta";

        $matdec[4] = "cuarenta";

        $matdec[5] = "cincuenta";

        $matdec[6] = "sesenta";

        $matdec[7] = "setenta";

        $matdec[8] = "ochenta";

        $matdec[9] = "noventa";

        $matsub[3] = 'mill';

        $matsub[5] = 'bill';

        $matsub[7] = 'mill';

        $matsub[9] = 'trill';

        $matsub[11] = 'mill';

        $matsub[13] = 'bill';

        $matsub[15] = 'mill';

        $matmil[4] = 'millones';

        $matmil[6] = 'billones';

        $matmil[7] = 'de billones';

        $matmil[8] = 'millones de billones';

        $matmil[10] = 'trillones';

        $matmil[11] = 'de trillones';

        $matmil[12] = 'millones de trillones';

        $matmil[13] = 'de trillones';

        $matmil[14] = 'billones de trillones';

        $matmil[15] = 'de billones de trillones';

        $matmil[16] = 'millones de billones de trillones';


        if ($num == '')
            $num = 0;

        $num = trim((string)@$num);

        if ($num[0] == '-') {

            $neg = 'menos ';

            $num = substr($num, 1);

        } else

            $neg = '';

        while ($num[0] == '0') $num = substr($num, 1);

        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;

        $zeros = true;

        $punt = false;

        $ent = '';

        $fra = '';

        for ($c = 0; $c < strlen($num); $c++) {

            $n = $num[$c];

            if (!(strpos(".,'''", $n) === false)) {

                if ($punt) break;

                else {

                    $punt = true;

                    continue;

                }


            } elseif (!(strpos('0123456789', $n) === false)) {

                if ($punt) {

                    if ($n != '0') $zeros = false;

                    $fra .= $n;

                } else



                    $ent .= $n;

            } else



                break;


        }

        $ent = '     ' . $ent;

        if ($dec and $fra and !$zeros) {

            $fin = ' coma';

            for ($n = 0; $n < strlen($fra); $n++) {

                if (($s = $fra[$n]) == '0')

                    $fin .= ' cero';

                elseif ($s == '1')

                    $fin .= $fem ? ' uno' : ' un';

                else

                    $fin .= ' ' . $matuni[$s];

            }

        } else

            $fin = '';

        if ((int)$ent === 0) return 'Cero ' . $fin;

        $tex = '';

        $sub = 0;

        $mils = 0;

        $neutro = false;

        while (($num = substr($ent, -3)) != '   ') {

            $ent = substr($ent, 0, -3);

            if (++$sub < 3 and $fem) {

//          $matuni[1] = 'uno';
                $matuni[1] = 'un';

                $subcent = 'os';

            } else {

                $matuni[1] = $neutro ? 'un' : 'uno';

                $subcent = 'os';

            }

            $t = '';

            $n2 = substr($num, 1);

            if ($n2 == '00') {

            } elseif ($n2 < 21)

                $t = ' ' . $matuni[(int)$n2];

            elseif ($n2 < 30) {

                $n3 = $num[2];

                if ($n3 != 0) $t = 'i' . $matuni[$n3];

                $n2 = $num[1];

                $t = ' ' . $matdec[$n2] . $t;

            } else {

                $n3 = $num[2];

                if ($n3 != 0) $t = ' y ' . $matuni[$n3];

                $n2 = $num[1];

                $t = ' ' . $matdec[$n2] . $t;

            }

            $n = $num[0];

            if ($n == 1) {

                $t = ' ciento' . $t;

            } elseif ($n == 5) {

                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;

            } elseif ($n != 0) {

                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;

            }

            if ($sub == 1) {

            } elseif (!isset($matsub[$sub])) {

                if ($num == 1) {

                    $t = ' mil';

                } elseif ($num > 1) {

                    $t .= ' mil';

                }

            } elseif ($num == 1) {

                $t .= ' ' . $matsub[$sub] . 'on';

            } elseif ($num > 1) {

                $t .= ' ' . $matsub[$sub] . 'ones';

            }

            if ($num == '000') $mils++;

            elseif ($mils != 0) {

                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];

                $mils = 0;

            }

            $neutro = true;

            $tex = $t . $tex;

        }

        $tex = $neg . substr($tex, 1) . $fin;

        return ucfirst($tex);

    }

    public static function devuelveMes($intMes)
    {
        $strMes = "";
        switch ($intMes) {
            case 1:
                $strMes = "ENERO";
                break;
            case 2:
                $strMes = "FEBRERO";
                break;
            case 3:
                $strMes = "MARZO";
                break;
            case 4:
                $strMes = "ABRIL";
                break;
            case 5:
                $strMes = "MAYO";
                break;
            case 6:
                $strMes = "JUNIO";
                break;
            case 7:
                $strMes = "JULIO";
                break;
            case 8:
                $strMes = "AGOSTO";
                break;
            case 9:
                $strMes = "SEPTIEMBRE";
                break;
            case 10:
                $strMes = "OCTUBRE";
                break;
            case 11:
                $strMes = "NOVIEMBRE";
                break;
            case 12:
                $strMes = "DICIEMBRE";
                break;
        }
        return $strMes;
    }

    public static function horaServicio($horaInicio, $horaFinal)
    {
        $arrHoras = array(
            'horas' => 24,
            'horasDiurnas' => 15,
            'horasNocturnas' => 9);
        $intMinutoInicio = (($horaInicio->format('i') * 100) / 60) / 100;
        $intHoraInicio = $horaInicio->format('G');
        $intHoraInicio += $intMinutoInicio;
        $intMinutoFinal = (($horaFinal->format('i') * 100) / 60) / 100;
        $intHoraFinal = $horaFinal->format('G');
        $intHoraFinal += $intMinutoFinal;
        if($intHoraInicio != 0 || $intHoraFinal !=0) {
            $horasNocturnasDia = FuncionesController::calcularTiempo($intHoraInicio, $intHoraFinal, 0, 6);
            $horasDiurnas = FuncionesController::calcularTiempo($intHoraInicio, $intHoraFinal, 6, 21);
            $horasNocturnasNoche = FuncionesController::calcularTiempo($intHoraInicio, $intHoraFinal, 21, 24);
            $horasNocturnas =  $horasNocturnasDia + $horasNocturnasNoche;
            $horas = $horasDiurnas + $horasNocturnas;
            $arrHoras['horas'] = $horas;
            $arrHoras['horasDiurnas'] = $horasDiurnas;
            $arrHoras['horasNocturnas'] = $horasNocturnas;
        }
        return $arrHoras;
    }

    private static function calcularTiempo($intInicial, $intFinal, $intParametroInicio, $intParametroFinal)
    {
        if ($intInicial < $intParametroInicio) {
            $intHoraIniciaTemporal = $intParametroInicio;
        } else {
            $intHoraIniciaTemporal = $intInicial;
        }
        if ($intFinal > $intParametroFinal) {
            if ($intInicial > $intParametroFinal) {
                $intHoraTerminaTemporal = $intInicial;
            } else {
                $intHoraTerminaTemporal = $intParametroFinal;
            }
        } else {
            if ($intFinal > $intParametroInicio) {
                $intHoraTerminaTemporal = $intFinal;
            } else {
                $intHoraTerminaTemporal = $intParametroInicio;
            }
        }
        $intHoras = $intHoraTerminaTemporal - $intHoraIniciaTemporal;
        return $intHoras;
    }

}