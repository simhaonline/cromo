<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaPlanilla;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteFacturaPlanillaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaPlanilla::class);
    }

    public function listaFacturaDetalle($codigoFactura)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaPlanilla::class, 'fp');
        $queryBuilder
            ->select('fp.codigoFacturaPlanillaPk')
            ->addSelect('fp.numero')
            ->addSelect('fp.guias')
            ->addSelect('fp.vrFlete')
            ->addSelect('fp.vrManejo')
            ->where('fp.codigoFacturaFk = ' . $codigoFactura);
        return $queryBuilder;
    }

}