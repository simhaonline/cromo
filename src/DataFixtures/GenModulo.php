<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenModulo extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arrayGenModulo=array('Cartera','Compra','Documental','Financiero','General','Inventario','Recurso Humano','Seguridad','Transporte','Crm', 'Turno', 'Tesoreria', );
        foreach ($arrayGenModulo as $arrGenModulo){
            $arGenModulo = $manager->getRepository('App:General\GenModulo')->find($arrGenModulo);
            if(!$arGenModulo) {
                $arGenModulo = new \App\Entity\General\GenModulo();
                $arGenModulo->setCodigoModuloPk($arrGenModulo);
                $manager->persist($arGenModulo);
                $manager->flush();
            }
        }
    }
}
