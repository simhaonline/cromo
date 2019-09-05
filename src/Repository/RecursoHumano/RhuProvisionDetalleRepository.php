<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuProvisionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuProvisionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuProvisionDetalle::class);
    }

    public function lista($id)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuProvisionDetalle::class, 'pd')
            ->select('pd.codigoProvisionDetallePk')
            ->addSelect('pd.numero')
        ->where("pd.codigoProvisionPeriodoFk = {$id}");
        return $queryBuilder;
    }
}