<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteMonitoreoDetalle;
use App\Entity\Transporte\TteVehiculo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteMonitoreoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteMonitoreo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteMonitoreo::class, 'm')
            ->select('m.codigoMonitoreoPk')
            ->addSelect('m.fechaInicio')
            ->addSelect('m.fechaFin')
            ->addSelect('m.codigoVehiculoFk')
            ->addSelect('m.soporte')
            ->addSelect('m.codigoDespachoFk')
            ->addSelect('c.nombreCorto')
            ->addSelect('cd.nombre')
            ->addSelect('m.estadoAutorizado')
            ->addSelect('m.estadoAprobado')
            ->addSelect('m.estadoAnulado')
            ->addSelect('m.estadoCerrado')
            ->leftJoin('m.vehiculoRel', 'v')
            ->leftJoin('m.despachoRel', 'd')
            ->leftJoin('d.conductorRel', 'c')
            ->leftJoin('d.ciudadDestinoRel', 'cd')
            ->where('m.codigoMonitoreoPk <> 0')
        ->orderBy('m.fechaRegistro', 'DESC');
        $fecha =  new \DateTime('now');
        if($session->get('filtroTteMovMonitoreoFiltroFecha') == true){
            if ($session->get('filtroTteMovMonitoreoFechaDesde') != null) {
                $queryBuilder->andWhere("m.fechaInicio >= '{$session->get('filtroTteMovMonitoreoFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("m.fechaInicio >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroTteMovMonitoreoFechaHasta') != null) {
                $queryBuilder->andWhere("m.fechaFin <= '{$session->get('filtroTteMovMonitoreoFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("m.fechaFin <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }

        return $queryBuilder;
    }

    public function generar($codigoVehiculo): bool
    {
        $em = $this->getEntityManager();
        $arVehiculo = $em->getRepository(TteVehiculo::class)->find($codigoVehiculo);
        $arMonitoreo = new TteMonitoreo();
        $arMonitoreo->setVehiculoRel($arVehiculo);
        $em->persist($arMonitoreo);
        $em->flush();

        return true;
    }

    /**
     * @param $arMonitoreo TteMonitoreo
     */
    public function autorizar($arMonitoreo)
    {
        if (count($this->_em->getRepository(TteMonitoreoDetalle::class)->findBy(['codigoMonitoreoFk' => $arMonitoreo->getCodigoMonitoreoPk()])) > 0) {
            $arMonitoreo->setEstadoAutorizado(1);
            $this->_em->persist($arMonitoreo);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arMonitoreo TteMonitoreo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arMonitoreo)
    {
        if ($arMonitoreo->getEstadoAutorizado()) {
            $arMonitoreo->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arMonitoreo);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');

        }
    }

    /**
     * @param $arMonitoreo TteMonitoreo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arMonitoreo)
    {
        if($arMonitoreo->getEstadoAutorizado() == 1 && $arMonitoreo->getEstadoAprobado() == 0) {
            $arMonitoreo->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arMonitoreo);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }
}