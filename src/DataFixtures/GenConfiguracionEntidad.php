<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenConfiguracionEntidad extends Fixture
{
    public function load(ObjectManager $em)
    {
        /////////////////              INICIO ESTRACTURA MODULO DE INVENTARIO                          ////////////////
        $arrModuloInventario = ['InvSolicitud', 'InvItem'];
        $this->setConfiguracionEntidades($arrModuloInventario, $em);
        /////////////////              FIN ESTRACTURA MODULO DE INVENTARIO                             ////////////////

        /////////////////              INICIO ESTRACTURA MODULO DE CARTERA                             ////////////////
        $arrModuloCartera = ['CarCliente'];
        $this->setConfiguracionEntidades($arrModuloCartera, $em);
        /////////////////                 FIN ESTRACTURA MODULO DE CARTERA                             ////////////////

        /////////////////              INICIO ESTRACTURA MODULO DE CONTABILIDAD                        ////////////////
        $arrModuloContabilidad = ['CtbRegistro', 'CtbCuenta', 'CtbComprobante', 'CtbCentroCosto','CtbTercero'];
        $this->setConfiguracionEntidades($arrModuloContabilidad, $em);
        /////////////////                 FIN ESTRACTURA MODULO DE CONTABILIDAD                        ////////////////
        //Guardar los registros
        ///
        $em->flush();
    }


    /**
     * @author Juan Felipe Mesa Ocampo
     * @param $arrModulos
     * @param $em
     * Funcion que recorre el array de los modulos a insertar
     */
    public function setConfiguracionEntidades($arrModulos, ObjectManager $em)
    {
        foreach ($arrModulos as $codigoEntidad) {
            $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find($codigoEntidad);
            if (!$arConfiguracionEntidad) {
                $arConfiguracionEntidad = new \App\Entity\General\GenConfiguracionEntidad();
                $arConfiguracionEntidad->setCodigoConfiguracionEntidadPk($codigoEntidad);
                $arrParametros = $this->obtenerParametrosEntidad($arConfiguracionEntidad->getCodigoConfiguracionEntidadPk());
                $arConfiguracionEntidad->setBase($arrParametros['rutaBase']);
                $arConfiguracionEntidad->setModulo($arrParametros['modulo']);
                $arConfiguracionEntidad->setActivo(true);
                $arConfiguracionEntidad->setRutaRepositorio($arrParametros['rutaRepositorio']);
                $arConfiguracionEntidad->setRutaEntidad($arrParametros['rutaEntidad']);
                $arConfiguracionEntidad->setRutaFormulario($arrParametros['rutaFormulario']);
                $arConfiguracionEntidad->setJsonLista($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaRepositorio(), $em));
                $arConfiguracionEntidad->setJsonExcel($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaRepositorio(), $em));
                $arConfiguracionEntidad->setJsonFiltro($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaRepositorio(), $em));
                $em->persist($arConfiguracionEntidad);
            }
        }

    }

    /**
     * @param $ruta
     * @param $em
     * @return string
     */
    public function generarConfiguracionEntidad($ruta, ObjectManager $em)
    {
        $metadata = $em->getClassMetadata($ruta);
        $arrCampos = $metadata->getFieldNames();
        $i = 0;
        foreach ($arrCampos as $campo) {
            $arInfo = $metadata->fieldMappings[$campo];
            $arrInfoCampos[$i]['campo'] = $arInfo['fieldName'];
            $arrInfoCampos[$i]['tipo'] = $arInfo['type'];
            $arrInfoCampos[$i]['mostrar'] = true;
            $arrInfoCampos[$i]['alias'] = $arInfo['fieldName'];
            $arrInfoCampos[$i]['orden'] = $i;
            $i++;
        }
        $jsonLista = json_encode($arrInfoCampos);
        return $jsonLista;
    }

    /**
     * @param $entidad
     * @return mixed
     */
    public function obtenerParametrosEntidad($entidad)
    {
        $modulo = substr($entidad, 0, 3);
        $entidadSinModulo = substr($entidad, 3);
        switch ($modulo) {
            case 'Inv':
                $arrParametros['modulo'] = 'Inventario';
                break;
            case 'Car':
                $arrParametros['modulo'] = 'Cartera';
                break;
            case 'Tte':
                $arrParametros['modulo'] = 'Transporte';
                break;
            case 'Ctb':
                $arrParametros['modulo'] = 'Contabilidad';
                break;
        }
        $rutaRepository = "App:" . $arrParametros['modulo'] . "\\" . $entidad;
        $rutaFormulario = "App\Form\Type\\" . $arrParametros['modulo'] . "\\" . $entidadSinModulo . "Type";
        $rutaBase = 'base_' . strtolower($arrParametros['modulo']) . ".html.twig";
        $rutaEntidad = '\App\Entity\\' . $arrParametros['modulo'] . "\\" . $entidad;
        $arrParametros['rutaBase'] = $rutaBase;
        $arrParametros['rutaEntidad'] = $rutaEntidad;
        $arrParametros['rutaFormulario'] = $rutaFormulario;
        $arrParametros['rutaRepositorio'] = $rutaRepository;
        return $arrParametros;
    }
}
