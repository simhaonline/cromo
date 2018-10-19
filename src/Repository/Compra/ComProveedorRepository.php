<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComProveedor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class ComProveedorRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComProveedor::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(ComProveedor::class, 't')
            ->select('t.codigoProveedorPk')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->where('t.codigoProveedorPk <> 0')
            ->orderBy('t.codigoProveedorPk', 'DESC');
        if ($session->get('filtroComProveedorNombre') != '') {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroComProveedorNombre')}%' ");
        }
        if ($session->get('filtroComProveedorIdentificacion') != '') {
            $queryBuilder->andWhere("t.numeroIdentificacion LIKE '%{$session->get('filtroComProveedorIdentificacion')}%' ");
        }
        if ($session->get('filtroComProveedorCodigo') != '') {
            $queryBuilder->andWhere("t.codigoProveedorPk LIKE '%{$session->get('filtroComProveedorCodigo')}%' ");
        }

        return $queryBuilder;
    }

}