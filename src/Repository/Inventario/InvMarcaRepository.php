<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvMarca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class InvMarcaRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvMarca::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m.codigoMarcaPk AS ID')
            ->addSelect("m.nombre AS NOMBRE")
            ->from("App:Inventario\InvMarca", "m")
            ->where('m.codigoMarcaPk IS NOT NULL');
        $qb->orderBy('m.codigoMarcaPk', 'ASC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}