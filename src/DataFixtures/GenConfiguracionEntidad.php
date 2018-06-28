<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenConfiguracionEntidad extends Fixture
{
    /* ESTRUCTURA ARRAY [ ENTIDAD, FUNCION, GRUPO, NUEVOINTERNO, DETALLEINTERNO] */
    public function load(ObjectManager $em)
    {
        /////////////////              INICIO ESTRACTURA MODULO DE INVENTARIO                          ////////////////
        $arrModuloInventario = $arrModuloInventario = [
            'solicitud,movimiento,inventario,1,1',
            'item,admin,general,0,0',
            'grupo,admin,general,0,0',
            'linea,admin,general,0,0',
            'subgrupo,admin,general,0,0',
            'grupo,admin,general,0,0',
            'bodega,admin,general,0,0',
            'configuracion,admin,general,0,0',
            'documento,admin,general,0,0',
            'solicitudTipo,admin,general,0,0',
            'facturaTipo,admin,general,0,0',
            'ordenCompraTipo,admin,general,0,0',
            'documentoTipo,admin,general,0,0',
            'tercero,admin,general,0,0',
            'ordenCompra,movimiento,inventario,0,1',
            'movimiento,movimiento,inventario,0,1',
            'marca,admin,general,0,0',
            'pedido,movimiento,comercial,0,1',
            'pedidoTipo,admin,comercial,0,0',
            'precio,admin,general,0,0'];
        $this->setConfiguracionEntidades($arrModuloInventario, $em, 'inventario');
        /////////////////              FIN ESTRACTURA MODULO DE INVENTARIO                             ////////////////
//
//        /////////////////              INICIO ESTRACTURA MODULO DE CARTERA                             ////////////////
//        $arrModuloCartera = ['cliente', 'reciboTipo', 'cuentaCobrarTipo', 'notaCreditoConcepto', 'notaDebitoConcepto'];
//        $this->setConfiguracionEntidades($arrModuloCartera, $em, 'cartera');
//        /////////////////                 FIN ESTRACTURA MODULO DE CARTERA                             ////////////////
//
        /////////////////              INICIO ESTRACTURA MODULO DE RECURSO HUMANO                            ////////////////
        $arrModuloRecursoHumano = ['aspirante,movimiento,seleccion,1,1',
            'solicitud,movimiento,seleccion,1,1',
            'seleccion,movimiento,seleccion,1,1',
            'solicitudMotivo,admin,solicitud,0,0',
            'solicitudExperiencia,admin,solicitud,0,0',
            'clasificacionRiesgo,admin,seguridadSocial,0,0',
            'grupoPago,admin,nomina,0,0',
            'cargo,admin,contratacion,0,0',
            'seleccionTipo,admin,seleccion,0,0',
            'empleado,admin,empleado,0,0',];
        $this->setConfiguracionEntidades($arrModuloRecursoHumano, $em, 'recursoHumano');
        /////////////////                 FIN ESTRACTURA MODULO DE RECURSO HUMANO
//
//        /////////////////              INICIO ESTRACTURA MODULO DE CONTABILIDAD                        ////////////////
//        $arrModuloContabilidad = ['registro', 'cuenta', 'comprobante', 'centroCosto', 'tercero'];
//        $this->setConfiguracionEntidades($arrModuloContabilidad, $em, 'contabilidad');
//        /////////////////                 FIN ESTRACTURA MODULO DE CONTABILIDAD                        ////////////////
//        ///
//        /////////////////              INICIO ESTRACTURA MODULO DE GENERAL SISTEMA                     ////////////////
        $arrModuloGeneral = ['sexo,admin,general,0,0',
            'religion,admin,general,0,0',
            'ciudad,admin,general,0,0',
            'estudioTipo,admin,general,0,0',
            'estadoCivil,admin,general,0,0',
            'departamento,admin,general,0,0',
            'pais,admin,general,0,0'];
        $this->setConfiguracionEntidades($arrModuloGeneral, $em, 'general');
        /////////////                 FIN ESTRACTURA MODULO DE GENERAL SISTEMA                     ////////////////


        $arrModuloTransporte = $arrModuloTransporte = [
            'relacionCaja,movimiento,control,1,1',
            'novedadTipo,administracion,transporte,0,0',
            'cliente,administracion,transporte,0,0',
            'aseguradora,administracion,transporte,0,0',
            'ciudad,administracion,general,0,0',
            'departamento,administracion,general,0,0',
            'ruta,administracion,transporte,0,0',
            'precio,administracion,comercial,0,0',
            'conductor,administracion,transporte,0,0',
            'condicion,administracion,comercial,0,0',
            'color,administracion,transporte,0,0',
            'vehiculo,administracion,transporte,0,0',
            'marca,administracion,transporte,0,0',
            'tipoCombustible,administracion,general,0,0',
            'tipoCarroceria,administracion,transporte,0,0',
            'poseedor,administracion,transporte,0,0',
            'auxiliar,administracion,transporte,0,0',
            'consecutivo,administracion,general,0,0',
            'facturaTipo,administracion,comercial,0,0',
            'guiaTipo,administracion,transporte,0,0',
            'operacion,administracion,general,0,0',
            'rutaRecogida,administracion,transporte,0,0'];
        $this->setConfiguracionEntidades($arrModuloTransporte, $em, 'transporte');

        $em->flush();
    }


    /**
     * @author Juan Felipe Mesa Ocampo
     * @param $arrModulos
     * @param $em
     * Función que recorre el array de los modulos a insertar
     */
    public function setConfiguracionEntidades($arrModulos, $em, $modulo)
    {
        switch ($modulo) {
            case 'inventario':
                $prefijo = 'inv';
                break;
            case 'recursoHumano':
                $prefijo = 'rhu';
                break;
            case 'cartera':
                $prefijo = 'car';
                break;
            case 'contabilidad':
                $prefijo = 'ctb';
                break;
            case 'transporte':
                $prefijo = 'tte';
                break;
            case 'general':
                $prefijo = 'gen';
                break;
        }
        foreach ($arrModulos as $arrEntidad) {
            $arrEntidad = explode(',', $arrEntidad);
            $codigo = $modulo . "_" . trim($arrEntidad[0]);
            $arEntidad = $em->getRepository("App:General\GenEntidad")->find($codigo);
            if (!$arEntidad) {
                $arEntidad = new \App\Entity\General\GenEntidad();
                $arEntidad->setCodigoEntidadPk($codigo);
                $arEntidad->setModulo($modulo);
                $arEntidad->setEntidad(trim($arrEntidad[0]));
                $arEntidad->setFuncion(trim($arrEntidad[1]));
                $arEntidad->setGrupo(trim($arrEntidad[2]));
                $arEntidad->setNuevoInterno(trim($arrEntidad[3]));
                $arEntidad->setDetalleInterno(trim($arrEntidad[4]));
                $arEntidad->setPrefijo($prefijo);
                $arEntidad->setActivo(true);
                $arEntidad->setJsonLista($this->generarConfiguracionEntidad('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()), $em));
                $arEntidad->setDqlLista($this->generarDql($arEntidad, $em));
                $arEntidad->setJsonExcel($this->generarConfiguracionEntidad('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()), $em));
                $arEntidad->setJsonFiltro($this->generarConfiguracionEntidad('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()), $em, true));
                $em->persist($arEntidad);
            }
        }
    }

    /**
     * @author Andres Acevedo
     * @param $ruta
     * @param $em
     * @return string
     */
    public function generarConfiguracionEntidad($ruta, ObjectManager $em, $filtro = false)
    {
        $metadata = $em->getClassMetadata($ruta);
        $arrCampos = $metadata->getFieldNames();
        $i = 0;
        foreach ($arrCampos as $campo) {
            $arInfo = $metadata->fieldMappings[$campo];
            $arrInfoCampos[$i]['campo'] = $arInfo['fieldName'];
            $arrInfoCampos[$i]['tipo'] = $arInfo['type'];
            if ($filtro) {
                $arrInfoCampos[$i]['mostrar'] = false;
            } else {
                $arrInfoCampos[$i]['mostrar'] = true;
            }
            $arrInfoCampos[$i]['alias'] = $arInfo['fieldName'];
            $arrInfoCampos[$i]['orden'] = $i;
            $i++;
        }
        $jsonLista = json_encode($arrInfoCampos);
        return $jsonLista;
    }

    /**
     * @author Andres Acevedo
     * @param $arEntidad
     * @param $em EntityManager
     * @return mixed
     */
    public function generarDql($arEntidad, $em)
    {
        return $em->getRepository('App:General\GenEntidad')->generarDqlFixtures($arEntidad);
    }
}
