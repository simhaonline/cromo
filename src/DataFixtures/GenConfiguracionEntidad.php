<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenConfiguracionEntidad extends Fixture
{
    public function load(ObjectManager $em)
    {
        /////////////////              INICIO ESTRACTURA MODULO DE INVENTARIO                          ////////////////
        $arrModuloInventario = ['solicitud, movimiento, inventario',
            'item, admin, general', 'itemGrupo,admin,general', 'bodega,admin,general', 'configuracion,admin,general', 'documento,admin,general', 'solicitudTipo,admin,general', 'facturaTipo,admin,general', 'ordenCompraTipo,admin,general',
            'documentoTipo,admin,general'];
        $this->setConfiguracionEntidades($arrModuloInventario, $em, 'inventario');
        /////////////////              FIN ESTRACTURA MODULO DE INVENTARIO                             ////////////////
//
//        /////////////////              INICIO ESTRACTURA MODULO DE CARTERA                             ////////////////
//        $arrModuloCartera = ['cliente', 'reciboTipo', 'cuentaCobrarTipo', 'notaCreditoConcepto', 'notaDebitoConcepto'];
//        $this->setConfiguracionEntidades($arrModuloCartera, $em, 'cartera');
//        /////////////////                 FIN ESTRACTURA MODULO DE CARTERA                             ////////////////
//
//        /////////////////              INICIO ESTRACTURA MODULO DE RECURSO HUMANO                            ////////////////
//        $arrModuloRecursoHumano = ['aspirante', 'solicitud', 'seleccion'];
//        $this->setConfiguracionEntidades($arrModuloRecursoHumano, $em, 'recursoHumano');
//        /////////////////                 FIN ESTRACTURA MODULO DE RECURSO HUMANO
//
//        /////////////////              INICIO ESTRACTURA MODULO DE CONTABILIDAD                        ////////////////
//        $arrModuloContabilidad = ['registro', 'cuenta', 'comprobante', 'centroCosto', 'tercero'];
//        $this->setConfiguracionEntidades($arrModuloContabilidad, $em, 'contabilidad');
//        /////////////////                 FIN ESTRACTURA MODULO DE CONTABILIDAD                        ////////////////
//        ///
//        /////////////////              INICIO ESTRACTURA MODULO DE GENERAL SISTEMA                     ////////////////
//        $arrModuloGeneral = ['cubo'];
//        $this->setConfiguracionEntidades($arrModuloGeneral, $em, 'general');
//        /////////////////                 FIN ESTRACTURA MODULO DE GENERAL SISTEMA                     ////////////////
        //Guardar los registros
        ///
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
                $prefijo = 'ttr';
                break;
            case 'general':
                $prefijo = 'gen';
                break;
        }
        foreach ($arrModulos as $arrEntidad) {
            $arrEntidad = explode(',', $arrEntidad);
//            dump($arrEntidad);die();
            $codigo = $modulo . "_" . trim($arrEntidad[0]);
            $arEntidad = $em->getRepository("App:General\GenEntidad")->find($codigo);
            if (!$arEntidad) {
                $arEntidad = new \App\Entity\General\GenEntidad();
                $arEntidad->setCodigoEntidadPk($codigo);
                $arEntidad->setModulo($modulo);
                $arEntidad->setEntidad(trim($arrEntidad[0]));
                $arEntidad->setFuncion(trim($arrEntidad[1]));
                $arEntidad->setGrupo(trim($arrEntidad[2]));
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
