<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRedespacho;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use SoapClient;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRedespachoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRedespacho::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRedespacho::class, 'rd')
            ->select('rd.codigoRedespachoPk')
            ->addSelect('rd.fecha')
            ->addSelect('rd.codigoGuiaFk')
            ->addSelect('g.numero AS numeroGuia')
            ->addSelect('rd.codigoDespachoFk')
            ->addSelect('d.numero AS numeroDespacho')
            ->leftJoin('rd.redespachoGuiaRel', 'g')
            ->leftJoin('rd.redespachoDespachoRel', 'd')
            ->orderBy('rd.codigoRedespachoPk', 'DESC');
        $fecha =  new \DateTime('now');
        if ($session->get('filtroTteFechaDesde') != null) {
            $queryBuilder->andWhere("rd.fecha >= '{$session->get('filtroTteFechaDesde')} 00:00:00'");
        } else {
            $queryBuilder->andWhere("rd.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
        }
        if ($session->get('filtroTteFechaHasta') != null) {
            $queryBuilder->andWhere("rd.fecha <= '{$session->get('filtroTteFechaHasta')} 23:59:59'");
        } else {
            $queryBuilder->andWhere("rd.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
        }

        return $queryBuilder;

    }

}

