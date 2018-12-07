<?php

namespace App\Repository\General;

use App\Entity\General\GenEvento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenEventoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenEvento::class);
    }

    /**
     * @param $usuario string
     * @return array
     */
    public function jsonEventos($usuario){
        $em = $this->getEntityManager();
        $arEventos = $em->getRepository(GenEvento::class)->findBy(['usuario' => $usuario]);
        $json = [];
        if($arEventos){
            /** @var  $arEvento GenEvento */
            foreach ($arEventos as $arEvento){
                $arrEvento['title'] = $arEvento->getTitulo();
                $arrEvento['start'] = $arEvento->getFechaDesde()->format('Y/m/d H:i:s');
                $arrEvento['end'] = $arEvento->getFechaHasta()->format('Y/m/d H:i:s');
                $arrEvento['description'] = $arEvento->getDescripcion();
                $arrEvento['classname'] = ["event",$arEvento->getColor()];
                $arrEvento['icon'] = $arEvento->getIcono();
                $json[] = $arrEvento;
            }
        }
        return $json;
    }

}
