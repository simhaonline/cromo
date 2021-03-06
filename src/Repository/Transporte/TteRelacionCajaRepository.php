<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRelacionCaja;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRelacionCajaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteRelacionCaja::class);
    }

    /**
     * @return \rctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAnulado = null;
        $estadoAprobado = null;
        $estadoAutorizado = null;
        if ($filtros){
            $fechaDesde = $filtros['fechaDesde']??null;
            $fechaHasta = $filtros['fechaHasta']??null;
            $estadoAnulado = $filtros['estadoAnulado']??null;
            $estadoAprobado = $filtros['estadoAprobado']??null;
            $estadoAutorizado = $filtros['estadoAutorizado']??null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRelacionCaja::class, 'rc');
        $queryBuilder
            ->select('rc.codigoRelacionCajaPk')
            ->addSelect('rc.fecha')
            ->addSelect('rc.cantidad')
            ->addSelect('rc.vrFlete')
            ->addSelect('rc.vrManejo')
            ->addSelect('rc.vrTotal')
            ->addSelect('rc.estadoAutorizado')
            ->addSelect('rc.estadoAprobado')
            ->addSelect('rc.estadoAnulado')
            ->where('rc.codigoRelacionCajaPk <> 0');

        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("rc.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("rc.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("rc.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("rc.estadoAprobado = 1");
                break;
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("rc.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("rc.estadoAnulado = 1");
                break;
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("rc.fecha >= '{$fechaDesde} 00:00:00'");
        }

        if ($fechaHasta) {
            $queryBuilder->andWhere("rc.fecha <= '{$fechaHasta} 23:59:59'");
        }

        $queryBuilder->orderBy('rc.fecha', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteRelacionCaja::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteRecibo::class)->findBy(['codigoRelacionCajaFk' => $arRegistro->getCodigoRelacionCajaPk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
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
            Mensajes::error('El rcumento debe estar autorizado y no puede estar previamente aprobado');
        }
    }
}