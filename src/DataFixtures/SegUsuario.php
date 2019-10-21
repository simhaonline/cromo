<?php

namespace App\DataFixtures;

use App\Entity\Seguridad\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class SegUsuario extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arUsuario = $manager->getRepository(Usuario::class)->findOneBy(array('username' => 'semantica'));
        if(!$arUsuario) {
            $arUsuario = new Usuario();
            $arUsuario->setNombreCorto('SEMANTICA');
            $arUsuario->setUsername('semantica');
            $arUsuario->setIsActive(1);
            $arUsuario->setClaveEscritorio('smt48903');
            $arUsuario->setPassword('$2y$10$6kDEFebYApgUPxwLTj4kfe1ZsBxok2iM/pKafXNuW2pyh4BYRPi8a');
            $arUsuario->setEmail('investigacion@semantica.com.co');
            $arUsuario->setRol('ROLE_ADMIN');
            $manager->persist($arUsuario);
        }
        $manager->flush();
    }
}
