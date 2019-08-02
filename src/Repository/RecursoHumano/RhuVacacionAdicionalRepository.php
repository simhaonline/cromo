<?php

namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuVacacionAdicional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuVacacionAdicionalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuVacacionAdicional::class);
    }

    public function eliminarEmbargosVacacion($codigoVacacion)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuVacacionAdicional::class, "va")
            ->select("va")
            ->where("va.codigoVacacionFk = {$codigoVacacion}")
            ->andWhere("va.codigoEmbargoFk IS NOT NULL");
        $arVacacionAdicionales = $query->getQuery()->getResult();

        if ($arVacacionAdicionales) {
            try {
                foreach ($arVacacionAdicionales as $arVacacionAdicional) {
                    $em->remove($arVacacionAdicional);
                }
            } catch (\Exception $exception) {

            }
        }
    }
}