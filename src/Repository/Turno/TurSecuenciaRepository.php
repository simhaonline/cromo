<?php

namespace App\Repository\Turno;

use App\Entity\Turno\TurSecuencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSecuenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurSecuencia::class);
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSecuencia::class, 's')
            ->select('s');

        if ($session->get('filtroTurSecuenciaCodigoSecuencia') != '') {
            $queryBuilder->andWhere("s.codigoSecuenciaPk LIKE '%{$session->get('filtroTurSecuenciaCodigoSecuencia')}%'");

        }
        if ($session->get('filtroTurSecuenciaNombre') != '') {
            $queryBuilder->andWhere("s.nombre LIKE '%{$session->get('filtroTurSecuenciaNombre')}%'");
        }
        return $queryBuilder;
    }

}
