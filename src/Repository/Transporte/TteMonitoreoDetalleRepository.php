<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteMonitoreoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteMonitoreoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteMonitoreoDetalle::class);
    }

    public function monitoreo($codigoMonitoreo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteMonitoreoDetalle::class, 'md');
        $queryBuilder
            ->select('md.codigoMonitoreoDetallePk')
            ->addSelect('md.fechaRegistro')
            ->addSelect('md.fechaReporte')
            ->addSelect('md.comentario')
            ->where('md.codigoMonitoreoFk = ' . $codigoMonitoreo);
        $queryBuilder->orderBy('md.codigoMonitoreoDetallePk', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $arMonitoreoDetalle = $this->getEntityManager()->getRepository(TteMonitoreoDetalle::class)->find($arrSeleccionado);
            $arMonitoreo = $this->getEntityManager()->getRepository(TteMonitoreo::class)->find($arMonitoreoDetalle->getCodigoMonitoreoFk());
            if ($arMonitoreo->getEstadoCerrado() == 0) {
                $this->getEntityManager()->remove($arMonitoreoDetalle);
            }else {
                Mensajes::error("No se puede eliminar el registro ya se encuentra cerrado");
            }
        }
        $this->getEntityManager()->flush();
    }



}