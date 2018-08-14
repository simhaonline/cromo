<?php

namespace App\Repository\Contabilidad;

use App\Entity\Contabilidad\CtbTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CtbTerceroRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CtbTercero::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CtbTercero::class, 't')
            ->select('t.codigoTerceroPk')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->where('t.codigoTerceroPk <> 0')
            ->orderBy('t.codigoTerceroPk', 'DESC');
        if ($session->get('filtroCtbNombreTercero') != '') {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroCtbNombreTercero')}%' ");
        }
        if ($session->get('filtroNitTercero') != '') {
            $queryBuilder->andWhere("t.numeroIdentificacion LIKE '%{$session->get('filtroNitTercero')}%' ");
        }
        if ($session->get('filtroCtbCodigoTercero') != '') {
            $queryBuilder->andWhere("t.codigoTerceroPk LIKE '%{$session->get('filtroCtbCodigoTercero')}%' ");
        }

        return $queryBuilder;
    }

}