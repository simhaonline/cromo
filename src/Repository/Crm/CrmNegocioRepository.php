<?php


namespace App\Repository\Crm;


use App\Entity\Crm\CrmNegocio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CrmNegocioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmNegocio::class);
    }

    public function GraficaNegociosporFace()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmNegocio::class, 'n')
            ->Select('COUNT(n.codigoFaseFk) as cuenta')
            ->addSelect('f.nombre')
            ->leftJoin('n.faseRel', 'f')
//            ->where("Date_format(n.fecha,'%Y-%m) = Date_format(now(),'Y-m')")
            ->groupby('n.codigoFaseFk');

         dd($queryBuilder->getQuery()->getArrayResult());

    }
}