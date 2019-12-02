<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaPlanilla;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteFacturaPlanillaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    public function formatoFactura($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fp.codigoFacturaPlanillaPk, 
        fp.numero,
        fp.guias,
        fp.vrFlete, 
        fp.vrManejo,
        fp.vrFlete + fp.vrManejo AS vrTotal        
        FROM App\Entity\Transporte\TteFacturaPlanilla fp 
        WHERE fp.codigoFacturaFk = :codigoFactura'
        )->setParameter('codigoFactura', $codigoFactura);

        return $query->execute();

    }

}