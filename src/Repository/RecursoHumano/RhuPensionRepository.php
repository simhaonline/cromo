<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuPensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuPension::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()
            ->select('p.codigoPensionPk AS ID')
            ->addSelect('p.nombre')
            ->addSelect('p.porcentajeEmpleado')
            ->addSelect('p.porcentajeEmpleador')
            ->addSelect('p.codigoConceptoFk')
            ->addSelect('p.orden')
            ->from(RhuPension::class,'p')
            ->where('p.codigoPensionPk IS NOT NULL')->getQuery()->execute();
    }
}