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
            $arConfiguracionEntidad->setBase("base_inventario.html.twig");
            $arConfiguracionEntidad->setModulo("Inventario");
            $arConfiguracionEntidad->setActivo(true);
            $arConfiguracionEntidad->setRutaEntidad("App:Inventario\InvSolicitud");
            $arConfiguracionEntidad->setJsonLista($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonExcel($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonFiltro($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setRutaGeneral("inv_mto_solicitud");
            $em->persist($arConfiguracionEntidad);
        }
        $em->flush();

        $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find("InvItem");
        if (!$arConfiguracionEntidad) {
            $arConfiguracionEntidad = new \App\Entity\General\GenConfiguracionEntidad();
            $arConfiguracionEntidad->setCodigoConfiguracionEntidadPk("InvItem");
            $arConfiguracionEntidad->setBase("base_inventario.html.twig");
            $arConfiguracionEntidad->setModulo("Inventario");
            $arConfiguracionEntidad->setActivo(true);
            $arConfiguracionEntidad->setRutaEntidad("App:Inventario\InvItem");
            $arConfiguracionEntidad->setJsonLista($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonExcel($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setJsonFiltro($this->generarConfiguracionEntidad($arConfiguracionEntidad->getRutaEntidad(),$em));
            $arConfiguracionEntidad->setRutaGeneral("inv_adm_item");
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
}
