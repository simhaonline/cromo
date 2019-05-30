<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class TurFestivo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arrDias = [
            ["codigo" => '20190101', 'fecha' => '2019-01-01'],
            ["codigo" => '20190107', 'fecha' => '2019-01-07'],
            ["codigo" => '20190325', 'fecha' => '2019-03-25'],
            ["codigo" => '20190418', 'fecha' => '2019-04-18'],
            ["codigo" => '20190419', 'fecha' => '2019-04-19'],
            ["codigo" => '20190501', 'fecha' => '2019-05-01'],
            ["codigo" => '20190603', 'fecha' => '2019-06-03'],
            ["codigo" => '20190624', 'fecha' => '2019-06-24'],
            ["codigo" => '20190701', 'fecha' => '2019-07-01'],
            ["codigo" => '20190720', 'fecha' => '2019-07-20'],
            ["codigo" => '20190807', 'fecha' => '2019-08-07'],
            ["codigo" => '20190819', 'fecha' => '2019-08-19'],
            ["codigo" => '20191014', 'fecha' => '2019-10-14'],
            ["codigo" => '20191104', 'fecha' => '2019-11-04'],
            ["codigo" => '20191111', 'fecha' => '2019-11-11'],
            ["codigo" => '20191208', 'fecha' => '2019-12-08'],
            ["codigo" => '20191225', 'fecha' => '2019-12-25']
        ];

        foreach ($arrDias as $arrDia) {
            $arFestivo = $manager->getRepository(\App\Entity\Turno\TurFestivo::class)->find($arrDia['codigo']);
            if(!$arFestivo){
                $arFestivo = new \App\Entity\Turno\TurFestivo();
                $arFestivo->setCodigoFestivoPk($arrDia['codigo']);
                $arFestivo->setFecha(date_create($arrDia['fecha']));
                $manager->persist($arFestivo);
            }
        }
        $manager->flush();
    }
}
