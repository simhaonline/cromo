<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSalud;
use Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo;
use Brasa\RecursoHumanoBundle\Entity\RhuTiempo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuSaludRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSalud::class);
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()
            ->select('s.codigoSaludPk AS ID')
            ->addSelect('s.nombre')
            ->addSelect('s.porcentajeEmpleado')
            ->addSelect('s.porcentajeEmpleador')
            ->addSelect('s.codigoConceptoFk')
            ->addSelect('s.orden')
            ->from(RhuSalud::class,'s')
            ->where('s.codigoSaludPk IS NOT NULL')->getQuery()->execute();
    }
}