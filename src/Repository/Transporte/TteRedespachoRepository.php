<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRedespacho;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use SoapClient;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRedespachoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
            ->addSelect('g.nombreDestinatario')
            ->addSelect('rd.codigoDespachoFk')
            ->addSelect('d.numero AS numeroDespacho')
            ->addSelect('m.nombre as motivoNombre')
            ->addSelect('cd.nombre as destinoNombre')
            ->leftJoin('rd.redespachoGuiaRel', 'g')
            ->leftJoin('rd.redespachoDespachoRel', 'd')
            ->leftJoin('rd.redespachoMotivoRel', 'm')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
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

//    public function guia($codigoGuia): array
//    {
//        $em = $this->getEntityManager();
//        $query = $em->createQuery(
//            'SELECT rd.codigoRedespachoPk,
//                  rd.codigoDespachoFk,
//                  rd.fecha
//        FROM App\Entity\Transporte\TteRedespacho rd
//        LEFT JOIN rd.despachoRel d
//        WHERE rd.codigoGuiaFk = :codigoGuia'
//        )->setParameter('codigoGuia', $codigoGuia);
//
//        return $query->execute();
//    }

//    public function guia($codigoGuia)
//    {
//        $session = new Session();
//        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRedespacho::class, 'rd')
//            ->select('rd.codigoRedespachoPk')
//            ->addSelect('rd.codigoGuiaFk')
//            ->leftJoin('rd.redespachoDespachoRel', 'd')
//            ->leftJoin('rd.redespachoGuiaRel', 'g')
//            ->where('rd.codigoGuiaFk = ' . $codigoGuia);
//        $queryBuilder->orderBy('rd.codigoGuiaFk', 'DESC');
//        return $queryBuilder->getQuery()->getResult();
//    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT
                  rd.codigoRedespachoPk,
                  rd.codigoGuiaFk,
                  rd.codigoDespachoFk,
                  rd.fecha
        FROM App\Entity\Transporte\TteRedespacho rd 
        WHERE rd.codigoGuiaFk = :codigoGuia'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();
    }
}

