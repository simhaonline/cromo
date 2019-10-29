<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class RhuConfiguracionProvision extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arrConfiguracionProvisiones = array(
            ['codigo' => 1, 'tipo' => 'CES', 'nombre' => 'CESANTIA', 'clase' => 'ADM'],
            ['codigo' => 2, 'tipo' => 'INT', 'nombre' => 'INTERES', 'clase' => 'ADM'],
            ['codigo' => 3, 'tipo' => 'PRI', 'nombre' => 'PRIMA', 'clase' => 'ADM'],
            ['codigo' => 4, 'tipo' => 'VAC', 'nombre' => 'VACACION', 'clase' => 'ADM'],
            ['codigo' => 5, 'tipo' => 'IND', 'nombre' => 'INDEMNIZACION', 'clase' => 'ADM'],
            ['codigo' => 6, 'tipo' => 'CES', 'nombre' => 'CESANTIA', 'clase' => 'OPE'],
            ['codigo' => 7, 'tipo' => 'INT', 'nombre' => 'INTERES', 'clase' => 'OPE'],
            ['codigo' => 8, 'tipo' => 'PRI', 'nombre' => 'PRIMA', 'clase' => 'OPE'],
            ['codigo' => 9, 'tipo' => 'VAC', 'nombre' => 'VACACION', 'clase' => 'OPE'],
            ['codigo' => 10, 'tipo' => 'IND', 'nombre' => 'INDEMNIZACION', 'clase' => 'OPE'],
            ['codigo' => 11, 'tipo' => 'CES', 'nombre' => 'CESANTIA', 'clase' => 'COM'],
            ['codigo' => 12, 'tipo' => 'INT', 'nombre' => 'INTERES', 'clase' => 'COM'],
            ['codigo' => 13, 'tipo' => 'PRI', 'nombre' => 'PRIMA', 'clase' => 'COM'],
            ['codigo' => 14, 'tipo' => 'VAC', 'nombre' => 'INTERES', 'clase' => 'COM'],
            ['codigo' => 15, 'tipo' => 'IND', 'nombre' => 'INDEMNIZACION', 'clase' => 'COM'],
            ['codigo' => 16, 'tipo' => 'SAL', 'nombre' => 'SALUD', 'clase' => 'ADM'],
            ['codigo' => 17, 'tipo' => 'PEN', 'nombre' => 'PENSION', 'clase' => 'ADM'],
            ['codigo' => 18, 'tipo' => 'CAJ', 'nombre' => 'CAJA', 'clase' => 'ADM'],
            ['codigo' => 19, 'tipo' => 'RIE', 'nombre' => 'RIESGOS', 'clase' => 'ADM'],
            ['codigo' => 20, 'tipo' => 'SEN', 'nombre' => 'SENA', 'clase' => 'ADM'],
            ['codigo' => 21, 'tipo' => 'ICB', 'nombre' => 'ICBF', 'clase' => 'ADM'],
            ['codigo' => 22, 'tipo' => 'SAL', 'nombre' => 'SALUD', 'clase' => 'OPE'],
            ['codigo' => 23, 'tipo' => 'PEN', 'nombre' => 'PENSION', 'clase' => 'OPE'],
            ['codigo' => 24, 'tipo' => 'CA', 'nombre' => 'CAJA', 'clase' => 'OPE'],
            ['codigo' => 25, 'tipo' => 'RIE', 'nombre' => 'RIESGOS', 'clase' => 'OPE'],
            ['codigo' => 26, 'tipo' => 'SEN', 'nombre' => 'SENA', 'clase' => 'OPE'],
            ['codigo' => 27, 'tipo' => 'ICB', 'nombre' => 'ICBF', 'clase' => 'OPE'],
            ['codigo' => 28, 'tipo' => 'SAL', 'nombre' => 'SALUD', 'clase' => 'COM'],
            ['codigo' => 29, 'tipo' => 'PEN', 'nombre' => 'PENSION', 'clase' => 'COM'],
            ['codigo' => 30, 'tipo' => 'CAJ', 'nombre' => 'CAJA', 'clase' => 'COM'],
            ['codigo' => 31, 'tipo' => 'RIE', 'nombre' => 'RIESGOS', 'clase' => 'COM'],
            ['codigo' => 32, 'tipo' => 'SEN', 'nombre' => 'SENA', 'clase' => 'COM'],
            ['codigo' => 33, 'tipo' => 'ICB', 'nombre' => 'ICBF', 'clase' => 'COM'],
        );
        foreach ($arrConfiguracionProvisiones as $arrConfiguracionProvision) {
            $arConfiguracionProvision = $manager->getRepository(\App\Entity\RecursoHumano\RhuConfiguracionProvision::class)->find($arrConfiguracionProvision['codigo']);
            if (!$arConfiguracionProvision) {
                $arConfiguracionProvision = new \App\Entity\RecursoHumano\RhuConfiguracionProvision();
                $arConfiguracionProvision->setCodigoConfiguracionProvisionPk($arrConfiguracionProvision['codigo']);
                $arConfiguracionProvision->setTipo($arrConfiguracionProvision['tipo']);
                $arConfiguracionProvision->setNombre($arrConfiguracionProvision['nombre']);
                $arConfiguracionProvision->setCodigoCostoClaseFk($arrConfiguracionProvision['clase']);
                $manager->persist($arConfiguracionProvision);
            }
        }
        $manager->flush();
    }
}
