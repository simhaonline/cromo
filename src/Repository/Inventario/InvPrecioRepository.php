<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvPrecio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvPrecioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPrecio::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPrecio::class, 'p')
            ->select('p.codigoPrecioPk')
            ->addSelect('p.nombre')
            ->addSelect('p.fechaVence')
            ->addSelect('p.venta')
            ->addSelect('p.compra')
            ->where('p.codigoPrecioPk <> 0')
            ->orderBy('p.codigoPrecioPk', 'DESC');
        if ($session->get('filtroInvNombrePrecio') != '') {
            $queryBuilder->andWhere("p.nombre LIKE '%{$session->get('filtroInvNombrePrecio')}%' ");
        }
        switch ($session->get('filtroInvTipoPrecio')) {
            case '0':
                $queryBuilder->andWhere("p.compra = 1");
                break;
            case '1':
                $queryBuilder->andWhere("p.venta = 1");
                break;
        }
        return $queryBuilder;

    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Inventario\InvPrecio', 'p')
            ->select('p.codigoPrecioPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}