<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCompraTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ComCompraTipoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComCompraTipo::class);
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComCompraTipo::class, 'ct')
            ->select('ct.codigoCompraTipoPk as ID')
            ->addSelect('ct.nombre')
            ->where('ct.codigoCompraTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}