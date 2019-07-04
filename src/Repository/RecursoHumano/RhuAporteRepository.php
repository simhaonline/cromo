<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporte::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporte::class, 'a')
            ->select('a')
            ->orderBy('a.codigoAportePk', 'DESC');
        if ($session->get('filtroRhuAporteAnio') != '') {
            $queryBuilder->andWhere("a.anio LIKE '%{$session->get('filtroRhuAporteAnio')}%' ");
        }
        if ($session->get('filtroRhuAporteMes') != '') {
            $queryBuilder->andWhere("a.mes = {$session->get('filtroRhuAporteMes')} ");
        }
//        if ($session->get('filtroTurClienteCodigo') != '') {
//            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTurClienteCodigo')} ");
//        }
        return $queryBuilder;
    }
}