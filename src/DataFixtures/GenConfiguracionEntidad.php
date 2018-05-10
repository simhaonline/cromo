<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenConfiguracionEntidad extends Fixture
{
    public function load(ObjectManager $em)
    {
        $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find("InvSolicitud");
        if (!$arConfiguracionEntidad) {
            $arConfiguracionEntidad = new \App\Entity\General\GenConfiguracionEntidad();
            $arConfiguracionEntidad->setCodigoConfiguracionEntidadPk("InvSolicitud");
            $arrParametros = $this->obtenerParametrosEntidad($arConfiguracionEntidad->getCodigoConfiguracionEntidadPk());
            $arConfiguracionEntidad->setBase($arrParametros['rutaBase']);
            $arConfiguracionEntidad->setModulo($arrParametros['modulo']);
            $arConfiguracionEntidad->setActivo(true);
            $arConfiguracionEntidad->setRutaEntidad($arrParametros['rutaEntidad']);
            $arConfiguracionEntidad->setRutaFormulario($arrParametros['rutaFormulario']);
            $arConfiguracionEntidad->setJsonLista($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonExcel($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonFiltro($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));

            $em->persist($arConfiguracionEntidad);
        }
        $em->flush();

        $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find("InvItem");
        if (!$arConfiguracionEntidad) {
            $arConfiguracionEntidad = new \App\Entity\General\GenConfiguracionEntidad();
            $arConfiguracionEntidad->setCodigoConfiguracionEntidadPk("InvItem");
            $arrParametros = $this->obtenerParametrosEntidad($arConfiguracionEntidad->getCodigoConfiguracionEntidadPk());
            $arConfiguracionEntidad->setBase($arrParametros['rutaBase']);
            $arConfiguracionEntidad->setModulo($arrParametros['modulo']);
            $arConfiguracionEntidad->setActivo(true);
            $arConfiguracionEntidad->setRutaEntidad($arrParametros['rutaEntidad']);
            $arConfiguracionEntidad->setRutaFormulario($arrParametros['rutaFormulario']);
            $arConfiguracionEntidad->setJsonLista($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonExcel($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonFiltro($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $em->persist($arConfiguracionEntidad);
        }
        $em->flush();

        $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find("CarCliente");
        if (!$arConfiguracionEntidad) {
            $arConfiguracionEntidad = new \App\Entity\General\GenConfiguracionEntidad();
            $arConfiguracionEntidad->setCodigoConfiguracionEntidadPk("CarCliente");
            $arrParametros = $this->obtenerParametrosEntidad($arConfiguracionEntidad->getCodigoConfiguracionEntidadPk());
            $arConfiguracionEntidad->setBase($arrParametros['rutaBase']);
            $arConfiguracionEntidad->setModulo($arrParametros['modulo']);
            $arConfiguracionEntidad->setActivo(true);
            $arConfiguracionEntidad->setRutaEntidad($arrParametros['rutaEntidad']);
            $arConfiguracionEntidad->setRutaFormulario($arrParametros['rutaFormulario']);
            $arConfiguracionEntidad->setJsonLista($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonExcel($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonFiltro($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $em->persist($arConfiguracionEntidad);
        }
        $em->flush();
    }

    /**
     * @param $ruta
     * @param $em
     * @return string
     */
    public function generarConfiguracionEntidad($ruta,$em){
        $metadata = $em->getClassMetadata($ruta);
        $arrCampos = $metadata->getFieldNames();
        $i = 0;
        foreach($arrCampos as $campo){
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
    public function obtenerParametrosEntidad($entidad){
        $modulo = substr($entidad, 0,3);
        $entidadSinModulo = substr($entidad, 3);
        switch ($modulo){
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
        $rutaEntidad = "App:".$arrParametros['modulo']."\\".$entidad;
        $rutaFormulario = "App\Form\Type\\".$arrParametros['modulo']."\\".$entidadSinModulo."Type";
        $rutaBase = 'base_'.strtolower($arrParametros['modulo']).".html.twig";
        $arrParametros['rutaBase'] = $rutaBase;
        $arrParametros['rutaFormulario'] = $rutaFormulario;
        $arrParametros['rutaEntidad'] = $rutaEntidad;
        return $arrParametros;
    }
}
