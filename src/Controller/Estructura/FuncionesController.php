<?php

namespace App\Controller\Estructura;


use App\Entity\General\GenNotificacion;
use App\Utilidades\BaseDatos;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

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
    public static function RellenarNr($Nro, $Str, $NroCr, $pos = 'L')
    {
        $Longitud = strlen($Nro); //5

        $Nc = $NroCr - $Longitud; //5
        for ($i = 0; $i < $Nc; $i++) {
            if($pos == 'L'){
                $Nro = $Str . $Nro;
            } else {
                $Nro =  $Nro . $Str;
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

    public static function crearNotificacion($id)
    {
        try {
            $em = BaseDatos::getEm();
            $arNotificacionTipoPrueba = $em->getRepository('App:General\GenNotificacionTipo')->find($id);
            if ($arNotificacionTipoPrueba->getEstadoActivo()) {
                $usuarios = json_decode($arNotificacionTipoPrueba->getUsuarios(), true);

                if ($usuarios) {
                    foreach ($usuarios as $user) {
                        $arUsuario = $em->getRepository('App:Seguridad\Usuario')->findOneBy(['username' => $user]);
                        if ($arUsuario) {
                            $arNotificacion = (new GenNotificacion())
                                ->setFecha(new \DateTime('now'))
                                ->setNotificacionTipoRel($arNotificacionTipoPrueba)
                                ->setCodigoUsuarioReceptorFk($arUsuario->getUsername())
                                ->setCodigoUsuarioEmisorFk(null);
                            $arUsuario->setNotificacionesPendientes($arUsuario->getNotificacionesPendientes() + 1);
                            $em->persist($arUsuario);
                            $em->persist($arNotificacion);
                        }
                    }
                    $em->flush();
                }
            } else {
                Mensajes::error("No se puede crear la notificacion, se encuentra desactivada");
            }
        } catch (\Exception $exception) {
            //Error
        }
    }

    public static function generarSession( $modulo, $nombre, $claseNombre, $formFiltro){
        $namespaceType = "\\App\\Form\\Type\\{$modulo}\\{$nombre}Type";
        $campos = json_decode($namespaceType::getEstructuraPropiedadesFiltro(), true);
        $session=new Session();
        foreach ($campos as $campo){
            if(isset($campo['relacion'])){
                $relacion=explode('.', $campo['child']);
                $campo['child']=$relacion[0].$relacion[1];
            }

            if(substr($campo['child'], -2)=="Fk" && $campo['tipo']=="EntityType"){
                $funcion=isset($campo['pk'])?$campo['pk']:substr($campo['child'], 0,-2).'Pk';
                $session->set($claseNombre . '_'.$campo['child'], $formFiltro->get($campo['child'])->getData() != "" ? call_user_func(array($formFiltro->get($campo['child'])->getData(), 'get'.$funcion)): "");
            }
            else if(strlen($campo['child']) >= 5 && substr($campo['child'], 0, 5) == "fecha"){
                $session->set($claseNombre . '_'.$campo['child'], $formFiltro->get($campo['child'])->getData()!=null?$formFiltro->get($campo['child'])->getData()->format('Y-m-d'):null);
            }
            else{
                $session->set($claseNombre . '_'.$campo['child'], $formFiltro->get($campo['child'])->getData());
            }
        }
    }
}