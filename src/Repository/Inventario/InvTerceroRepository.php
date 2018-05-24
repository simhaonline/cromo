<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvTerceroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvTercero::class);
    }


    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s.codigoSolicitudPk AS ID')
            ->addSelect('s.numero AS NUMERO')
            ->addSelect('st.nombre AS TIPO')
            ->addSelect('s.fecha AS FECHA')
            ->from('App:Inventario\InvSolicitud', 's')
            ->join('s.solicitudTipoRel','st')
            ->where('s.codigoSolicitudPk <> 0')
            ->orderBy('s.codigoSolicitudPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }
}