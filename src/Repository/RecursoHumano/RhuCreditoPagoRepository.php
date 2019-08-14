<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuCreditoPagoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCreditoPago::class);
    }



    public function listaPorCredito($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCreditoPago::class, 'cp')
            ->select('cp')
            ->where("cp.codigoCreditoFk = {$id}")
            ->orderBy('cp.codigoCreditoPagoPk', 'DESC');

        return $queryBuilder;
    }
}