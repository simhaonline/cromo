<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenPais extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arPais = $manager->getRepository(\App\Entity\General\GenPais::class)->find(1);
        if(!$arPais){
            $arPais = new \App\Entity\General\GenPais();
            $arPais->setCodigoPaisPk(1);
            $arPais->setNombre('COLOMBIA');
            $manager->persist($arPais);
        }

        $manager->flush();
    }
}
