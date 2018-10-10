<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRelacionCaja;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRelacionCajaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRelacionCaja::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRelacionCaja::class, 'rc');
        $queryBuilder
            ->select('rc.codigoRelacionCajaPk')
            ->addSelect('rc.fecha')
            ->addSelect('rc.cantidad')
            ->addSelect('rc.vrFlete')
            ->addSelect('rc.vrManejo')
            ->addSelect('rc.vrTotal')
            ->where('rc.codigoRelacionCajaPk <> 0');
        if ($session->get('filtroTteReciboCajaCodigo') != "") {
            $queryBuilder->andWhere("rc.codigoRelacionCajaPk = " . $session->get('filtroTteReciboCajaCodigo'));
        }
        $queryBuilder->orderBy('rc.fecha', 'DESC');

        return $queryBuilder;
    }


    public function liquidar($codigoRelacionCaja): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoReciboPk) as cantidad, SUM(r.vrFlete+0) as flete, SUM(r.vrManejo+0) as manejo, SUM(r.vrTotal+0) as total
        FROM App\Entity\Transporte\TteRecibo r
        WHERE r.codigoRelacionCajaFk = :codigoRelacionCaja')
            ->setParameter('codigoRelacionCaja', $codigoRelacionCaja);
        $arr = $query->getSingleResult();
        $arMovimiento = $em->getRepository(TteRelacionCaja::class)->find($codigoRelacionCaja);
        $arMovimiento->setCantidad(intval($arr['cantidad']));
        $arMovimiento->setVrFlete(intval($arr['flete']));
        $arMovimiento->setVrManejo(intval($arr['manejo']));
        $arMovimiento->setVrTotal(intval($arr['total']));
        $em->persist($arMovimiento);
        $em->flush();
        return true;
    }

    public function retirarRecibo($arrDetalles)
    {
        $em = $this->getEntityManager();
        if($arrDetalles) {
            if (count($arrDetalles) > 0) {
                foreach ($arrDetalles AS $codigo) {
                    $arDetalle = $em->getRepository(TteRecibo::class)->find($codigo);
                    $arDetalle->setRelacionCajaRel(null);
                    $arDetalle->setEstadoRelacion(0);
                    $em->persist($arDetalle);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function autorizar($arRelacionCaja){
        if($this->getEntityManager()->getRepository(TteRelacionCaja::class)->contarDetalles($arRelacionCaja->getCodigoRelacionCajaPk()) > 0){
            $arRelacionCaja->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arRelacionCaja);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro no tiene detalles');
        }
    }

    public function desautorizar($arRelacionCaja)
    {
        if ($arRelacionCaja->getEstadoAutorizado() == 1 && $arRelacionCaja->getEstadoAprobado() == 0) {
            $arRelacionCaja->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arRelacionCaja);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro esta aprobado y no se puede desautorizar');
        }
    }

    public function contarDetalles($codigoRelacionCaja)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRecibo::class, 'r')
            ->select("COUNT(r.codigoReciboPk)")
            ->where("r.codigoRelacionCajaFk= {$codigoRelacionCaja} ");
        $resultado =  $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function aprobar($arRelacionCaja)
    {
        if($arRelacionCaja->getEstadoAutorizado() == 1 && $arRelacionCaja->getEstadoAprobado() == 0) {
            $arRelacionCaja->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arRelacionCaja);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }
}