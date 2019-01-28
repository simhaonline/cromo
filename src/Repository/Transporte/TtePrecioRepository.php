<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TtePrecio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TtePrecioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TtePrecio::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TtePrecio::class, 'pr')
            ->select('pr.codigoPrecioPk')
            ->addSelect('pr.nombre')
            ->addSelect('pr.fechaVence')
            ->addSelect('pr.comentario')
            ->where('pr.codigoPrecioPk IS NOT NULL')
            ->orderBy('pr.codigoPrecioPk', 'DESC');
        if ($session->get('filtroTteNombrePrecio') != '') {
            $queryBuilder->andWhere("pr.nombre LIKE '%{$session->get('filtroTteNombrePrecio')}%' ");
        }

        return $queryBuilder;
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TtePrecio','pr')
            ->select('pr.codigoPrecioPk AS ID')
            ->addSelect('pr.nombre AS NOMBRE')
            ->addSelect('pr.fechaVence AS FECHA_VENCE')
            ->addSelect('pr.comentario AS COMENTARIOS');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}