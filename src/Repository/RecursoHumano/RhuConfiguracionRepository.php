<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConfiguracion::class);
    }

    public function autorizarProgramacion(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuConfiguracion::class, 'c')
            ->select('c.vrSalarioMinimo')
            ->addSelect('c.vrAuxilioTransporte')
            ->addSelect('c.codigoConceptoAuxilioTransporteFk')
            ->addSelect('c.codigoConceptoFondoPensionFk')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

    public function cargarContratos(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuConfiguracion::class, 'c')
            ->select('c.codigoConceptoFondoPensionFk')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

    public function porcentajeFondo($salarioMinimo, $ibc)
    {
        $salariosMinimos = $ibc / $salarioMinimo;
        $porcentaje = 0;
        if ($salariosMinimos >= 4 && $salariosMinimos < 16) {
            $porcentaje = 1;
        }
        if ($salariosMinimos >= 16 && $salariosMinimos < 17) {
            $porcentaje = 1.2;
        }
        if ($salariosMinimos >= 17 && $salariosMinimos < 18) {
            $porcentaje = 1.4;
        }
        if ($salariosMinimos >= 18 && $salariosMinimos < 19) {
            $porcentaje = 1.6;
        }
        if ($salariosMinimos >= 19 && $salariosMinimos < 20) {
            $porcentaje = 1.8;
        }
        if ($salariosMinimos >= 20) {
            $porcentaje = 2;
        }
        return $porcentaje;
    }

}