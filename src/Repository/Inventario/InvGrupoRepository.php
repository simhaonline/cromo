<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvGrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class InvGrupoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvGrupo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m.codigoGrupoPk AS ID')
            ->addSelect("m.nombre AS NOMBRE")
            ->from("App:Inventario\InvGrupo", "m")
            ->where('m.codigoGrupoPk IS NOT NULL');
        $qb->orderBy('m.codigoGrupoPk', 'ASC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}