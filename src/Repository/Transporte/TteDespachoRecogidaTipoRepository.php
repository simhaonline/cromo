<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteDespachoRecogidaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDespachoRecogidaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDespachoRecogidaTipo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Transporte\TteDespachoRecogidaTipo', 'drt')
            ->select('drt.codigoDespachoRecogidaTipoPk AS ID')
            ->addSelect('drt.nombre AS NOMBRE')
            ->addSelect('drt.consecutivo AS CONSECUTIVO')
            ->addSelect('drt.generaMonitoreo AS GENERA_MONITOREO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}