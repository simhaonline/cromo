<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCompraDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComCompraDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComCompraDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComCompraDetalle[]    findAll()
 * @method ComCompraDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComCompraDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComCompraDetalle::class);
    }


    public function lista($codigoCompra)
    {
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('cd')
            ->from('App:Compra\ComCompraDetalle', 'cd')
            ->where("cd.codigoCompraFk = '{$codigoCompra}'");

        return $query->getQuery();
    }
}
