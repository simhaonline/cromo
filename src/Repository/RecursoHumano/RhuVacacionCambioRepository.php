<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuVacacionCambio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuVacacionCambioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuVacacionCambio::class);
    }

    public function validarUltimaVacacion($codigoVacacion){
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuVacacionCambio::class, "vc")
            ->select("vc.codigoVacacionCambioPk")
            ->where("vc.codigoVacacionFk = {$codigoVacacion}")
            ->orderBy("vc.codigoVacacionCambioPk","desc")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $query;

    }
}
