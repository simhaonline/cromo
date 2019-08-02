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
            $arUsuario->setClaveEscritorio('smt489');
            $arUsuario->setPassword('$2a$10$dw7SHL/z3.rH/Mr6Pqa9ZOQ7NG6Phb/7EzzHYhlNZ9YQsGJq3yw0K');
            $arUsuario->setEmail('investigacion@semantica.com.co');
            $arUsuario->setRol('ROLE_ADMIN');
            $manager->persist($arUsuario);
        }
        $manager->flush();
    }
}
