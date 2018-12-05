<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenImagen extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arImagen = $manager->getRepository(\App\Entity\General\GenImagen::class)->find('LOGO');
        if(!$arImagen) {

            $arImagen = new \App\Entity\General\GenImagen();
            $arImagen->setCodigoImagenPk('LOGO');
            $manager->persist($arImagen);
        }

        $manager->flush();
    }
}
