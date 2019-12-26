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
            ['codigo' => 6, 'tipo' => 'SAL', 'nombre' => 'SALUD', 'clase' => 'ADM'],
            ['codigo' => 7, 'tipo' => 'PEN', 'nombre' => 'PENSION', 'clase' => 'ADM'],
            ['codigo' => 8, 'tipo' => 'CAJ', 'nombre' => 'CAJA', 'clase' => 'ADM'],
            ['codigo' => 9, 'tipo' => 'RIE', 'nombre' => 'RIESGOS', 'clase' => 'ADM'],
            ['codigo' => 10, 'tipo' => 'SEN', 'nombre' => 'SENA', 'clase' => 'ADM'],
            ['codigo' => 11, 'tipo' => 'ICB', 'nombre' => 'ICBF', 'clase' => 'ADM'],

            ['codigo' => 12, 'tipo' => 'CES', 'nombre' => 'CESANTIA', 'clase' => 'OPE'],
            ['codigo' => 13, 'tipo' => 'INT', 'nombre' => 'INTERES', 'clase' => 'OPE'],
            ['codigo' => 14, 'tipo' => 'PRI', 'nombre' => 'PRIMA', 'clase' => 'OPE'],
            ['codigo' => 15, 'tipo' => 'VAC', 'nombre' => 'VACACION', 'clase' => 'OPE'],
            ['codigo' => 16, 'tipo' => 'IND', 'nombre' => 'INDEMNIZACION', 'clase' => 'OPE'],
            ['codigo' => 17, 'tipo' => 'SAL', 'nombre' => 'SALUD', 'clase' => 'OPE'],
            ['codigo' => 18, 'tipo' => 'PEN', 'nombre' => 'PENSION', 'clase' => 'OPE'],
            ['codigo' => 19, 'tipo' => 'CAJ', 'nombre' => 'CAJA', 'clase' => 'OPE'],
            ['codigo' => 20, 'tipo' => 'RIE', 'nombre' => 'RIESGOS', 'clase' => 'OPE'],
            ['codigo' => 21, 'tipo' => 'SEN', 'nombre' => 'SENA', 'clase' => 'OPE'],
            ['codigo' => 22, 'tipo' => 'ICB', 'nombre' => 'ICBF', 'clase' => 'OPE'],

            ['codigo' => 23, 'tipo' => 'CES', 'nombre' => 'CESANTIA', 'clase' => 'ADO'],
            ['codigo' => 24, 'tipo' => 'INT', 'nombre' => 'INTERES', 'clase' => 'ADO'],
            ['codigo' => 25, 'tipo' => 'PRI', 'nombre' => 'PRIMA', 'clase' => 'ADO'],
            ['codigo' => 26, 'tipo' => 'VAC', 'nombre' => 'VACACION', 'clase' => 'ADO'],
            ['codigo' => 27, 'tipo' => 'IND', 'nombre' => 'INDEMNIZACION', 'clase' => 'ADO'],
            ['codigo' => 28, 'tipo' => 'SAL', 'nombre' => 'SALUD', 'clase' => 'ADO'],
            ['codigo' => 29, 'tipo' => 'PEN', 'nombre' => 'PENSION', 'clase' => 'ADO'],
            ['codigo' => 30, 'tipo' => 'CAJ', 'nombre' => 'CAJA', 'clase' => 'ADO'],
            ['codigo' => 31, 'tipo' => 'RIE', 'nombre' => 'RIESGOS', 'clase' => 'ADO'],
            ['codigo' => 32, 'tipo' => 'SEN', 'nombre' => 'SENA', 'clase' => 'ADO'],
            ['codigo' => 33, 'tipo' => 'ICB', 'nombre' => 'ICBF', 'clase' => 'ADO'],

            ['codigo' => 34, 'tipo' => 'CES', 'nombre' => 'CESANTIA', 'clase' => 'COM'],
            ['codigo' => 35, 'tipo' => 'INT', 'nombre' => 'INTERES', 'clase' => 'COM'],
            ['codigo' => 36, 'tipo' => 'PRI', 'nombre' => 'PRIMA', 'clase' => 'COM'],
            ['codigo' => 37, 'tipo' => 'VAC', 'nombre' => 'INTERES', 'clase' => 'COM'],
            ['codigo' => 38, 'tipo' => 'IND', 'nombre' => 'INDEMNIZACION', 'clase' => 'COM'],
            ['codigo' => 39, 'tipo' => 'SAL', 'nombre' => 'SALUD', 'clase' => 'COM'],
            ['codigo' => 40, 'tipo' => 'PEN', 'nombre' => 'PENSION', 'clase' => 'COM'],
            ['codigo' => 41, 'tipo' => 'CAJ', 'nombre' => 'CAJA', 'clase' => 'COM'],
            ['codigo' => 42, 'tipo' => 'RIE', 'nombre' => 'RIESGOS', 'clase' => 'COM'],
            ['codigo' => 43, 'tipo' => 'SEN', 'nombre' => 'SENA', 'clase' => 'COM'],
            ['codigo' => 44, 'tipo' => 'ICB', 'nombre' => 'ICBF', 'clase' => 'COM'],
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
