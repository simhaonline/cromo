<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvLinea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class InvLineaRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvLinea::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m.codigoLineaPk AS ID')
            ->addSelect("m.nombre AS NOMBRE")
            ->from("App:Inventario\InvLinea", "m")
            ->where('m.codigoLineaPk IS NOT NULL');
        $qb->orderBy('m.codigoLineaPk', 'ASC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}