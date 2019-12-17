<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenDimension extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arDimension = $manager->getRepository(\App\Entity\General\GenDimension::class)->find(1);
        if (!$arDimension) {
            $arDimension = new \App\Entity\General\GenDimension();
            $arDimension->setCodigoDimensionPk(1);
            $arDimension->setNombre("MEDIANA");
            $manager->persist($arDimension);
        }
        $arDimension = $manager->getRepository(\App\Entity\General\GenDimension::class)->find(2);
        if (!$arDimension) {
            $arDimension = new \App\Entity\General\GenDimension();
            $arDimension->setCodigoDimensionPk(2);
            $arDimension->setNombre("PEQUEÑA");
            $manager->persist($arDimension);
        }
        $arDimension = $manager->getRepository(\App\Entity\General\GenDimension::class)->find(3);
        if (!$arDimension) {
            $arDimension = new \App\Entity\General\GenDimension();
            $arDimension->setCodigoDimensionPk(3);
            $arDimension->setNombre("GRANDE");
            $manager->persist($arDimension);
        }
        $manager->flush();
    }

}