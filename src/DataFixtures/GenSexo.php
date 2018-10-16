<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenSexo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arSexo = $manager->getRepository(GenSexo::class)->find('M');
        if(!$arSexo){
            $arSexo = new \App\Entity\General\GenSexo();
            $arSexo->setCodigoSexoPk('M');
            $arSexo->setNombre('MASCULINO');
            $manager->persist($arSexo);
        }
        $arSexo = $manager->getRepository(GenSexo::class)->find('F');
        if(!$arSexo){
            $arSexo = new \App\Entity\General\GenSexo();
            $arSexo->setCodigoSexoPk('F');
            $arSexo->setNombre('FEMENINO');
            $manager->persist($arSexo);
        }

        $manager->flush();
    }
}
