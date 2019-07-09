<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionAporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuConfiguracionAporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuConfiguracionAporte::class);
    }

    public function autorizarProgramacion(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuConfiguracion::class, 'c')
            ->select('c.vrSalarioMinimo')
            ->addSelect('c.vrAuxilioTransporte')
            ->addSelect('c.codigoConceptoAuxilioTransporteFk')
            ->addSelect('c.codigoConceptoFondoSolidaridadPensionFk')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();

    }

}