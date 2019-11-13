<?php


namespace App\Repository\Turno;


use App\Entity\Turno\TurContratoDetalleCompuesto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TurContratoDetalleCompuestoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurContratoDetalleCompuesto::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalleCompuesto::class, 'cdc')
            ->select('cdc')
            ->where("cdc.codigoContratoDetalleFk = {$id}");
        return $queryBuilder->getQuery()->getResult();
    }
}