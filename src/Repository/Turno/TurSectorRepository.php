<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurSector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSectorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSector::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Turno\TurSector', 's')
            ->select('s.codigoSectorPk AS ID')
            ->addSelect('s.nombre AS NOMBRE')
            ->addSelect('s.porcentaje AS PORCENTAJE')
            ->addSelect('s.comentarios AS COMENTARIOS');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}
