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

    public function resumenCredito($codigoVacacion)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT va.codigoCreditoFk, SUM(va.vrDeduccion) as total FROM App\Entity\RecursoHumano\RhuVacacionAdicional va "
            . "WHERE va.codigoVacacionFk = " . $codigoVacacion . " "
            . "AND va.codigoCreditoFk IS NOT NULL GROUP BY va.codigoCreditoFk";
        $query = $em->createQuery($dql);
        $arrCredito = $query->getResult();
        return $arrCredito;
    }
}