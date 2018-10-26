<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuConcepto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuConceptoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConcepto::class);
    }

    public function camposPredeterminados()
    {
        return $this->_em->createQueryBuilder()->from(RhuConcepto::class, 'c')
            ->select('c.codigoConceptoPk AS ID')
            ->addSelect('c.nombre')
            ->addSelect('c.adicional AS ADI')
            ->addSelect('c.auxilioTransporte AS AUXT')
            ->addSelect('c.cesantia AS CES')
            ->addSelect('c.comision AS COM')
            ->addSelect('c.fondoSolidaridadPensional AS FSP')
            ->addSelect('c.vacacion AS VAC')
            ->addSelect('c.salud AS SAL')
            ->addSelect('c.recargoNocturno AS REN')
            ->addSelect('c.pension AS PEN')
            ->addSelect('c.incapacidadEntidad AS IE')
            ->addSelect('c.generaIngresoBasePrestacion AS IBP')
            ->addSelect('c.generaIngresoBaseCotizacion AS IBC')
            ->where('c.codigoConceptoPk IS NOT NULL')->getQuery()->execute();
    }
}