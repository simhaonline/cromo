<?php

namespace App\Repository\Turno;


use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\Turno\TurCierre;
use App\Entity\Turno\TurCostoEmpleado;
use App\Entity\Turno\TurCostoEmpleadoServicio;
use App\Entity\Turno\TurCostoServicio;
use App\Entity\Turno\TurDistribucion;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurCostoServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurCostoEmpleadoServicio::class);
    }

    public function generar($arCierre)
    {
        $em = $this->getEntityManager();
            //Costos de los servicios del mes
            $arPedidosDetalles = $em->getRepository(TurPedidoDetalle::class)->findBy(['anio' => $arCierre->getAnio(), 'mes' => $arCierre->getMes()]);
            foreach ($arPedidosDetalles as $arPedidoDetalle) {
                $dql = "SELECT SUM(ces.vrCosto) as vrCosto "
                    . "FROM App\Entity\Turno\TurCostoEmpleadoServicio ces "
                    . "WHERE ces.anio =  " . $arCierre->getAnio() . " AND ces.mes =  " . $arCierre->getMes() . " AND ces.codigoPedidoDetalleFk = " . $arPedidoDetalle->getCodigoPedidoDetallePk();
                $query = $em->createQuery($dql);
                $arrayResultados = $query->getResult();
                $costo = 0;
                if ($arrayResultados[0]['vrCosto']) {
                    $costo = $arrayResultados[0]['vrCosto'];
                }
                $arCostoServicio = new TurCostoServicio();
                $arCostoServicio->setCierreRel($arCierre);
                $arCostoServicio->setAnio($arCierre->getAnio());
                $arCostoServicio->setMes($arCierre->getMes());
                $arCostoServicio->setPedidoDetalleRel($arPedidoDetalle);
                $arCostoServicio->setClienteRel($arPedidoDetalle->getPedidoRel()->getClienteRel());
                $arCostoServicio->setPuestoRel($arPedidoDetalle->getPuestoRel());
                //$arCostoServicio->setConceptoRel($arPedidoDetalle->getConceptoRel());
                $arCostoServicio->setModalidadRel($arPedidoDetalle->getModalidadRel());
                //$arCostoServicio->setPeriodoRel($arPedidoDetalle->getPeriodoRel());
                $arCostoServicio->setDiaDesde($arPedidoDetalle->getDiaDesde());
                $arCostoServicio->setDiaHasta($arPedidoDetalle->getDiaHasta());
                $arCostoServicio->setDias($arPedidoDetalle->getDias());
                $arCostoServicio->setHoras($arPedidoDetalle->getHoras());
                $arCostoServicio->setHorasDiurnas($arPedidoDetalle->getHorasDiurnas());
                $arCostoServicio->setHorasNocturnas($arPedidoDetalle->getHorasNocturnas());
                $arCostoServicio->setCantidad($arPedidoDetalle->getCantidad());
                $arCostoServicio->setVrTotal($arPedidoDetalle->getVrSubtotal());
                $arCostoServicio->setVrCosto($costo);
                $em->persist($arCostoServicio);
            }
            $em->flush();

    }

    public function informe()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCostoServicio::class, 'cs')
            ->select('cs.codigoCostoServicioPk')
            ->addSelect('cs.codigoPedidoDetalleFk')
            ->addSelect('cs.anio')
            ->addSelect('cs.mes')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('cs.codigoPuestoFk')
            ->addSelect('p.nombre as puestoNombre')
            ->addSelect('cs.vrCosto')
            ->addSelect('cs.vrTotal')
            ->addSelect('co.nombre as conceptoNombre')
            ->addSelect('m.nombre as modalidadNombre')
            ->addSelect('cs.diaDesde')
            ->addSelect('cs.diaHasta')
            ->addSelect('cs.dias')
            ->addSelect('cs.horas')
            ->addSelect('cs.cantidad')
            ->leftJoin('cs.clienteRel', 'c')
            ->leftJoin('cs.puestoRel', 'p')
            ->leftJoin('cs.conceptoRel', 'co')
            ->leftJoin('cs.modalidadRel', 'm');

        if ($session->get('filtroTurCostoServicioAnio') != null) {
            $queryBuilder->andWhere("cs.anio = '{$session->get('filtroTurCostoServicioAnio')}'");
        }
        if ($session->get('filtroTurCostoServicioMes') != null) {
            $queryBuilder->andWhere("cs.mes = '{$session->get('filtroTurCostoServicioMes')}'");
        }
        if ($session->get('filtroTurCostoServicioCodigoEmpleado') != null) {
            $queryBuilder->andWhere("cs.codigoEmpleadoFk = '{$session->get('filtroTurCostoServicioCodigoEmpleado')}'");
        }
        return $queryBuilder->setMaxResults(5000)->getQuery();
    }

    public function lista($codigoCierre)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TurCostoServicio::class, 'cs')
            ->select('cs.codigoCostoServicioPk')
            ->addSelect('cs.vrCosto')
            ->addSelect('cs.vrTotal')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('p.nombre as puestoNombre')
            ->leftJoin('cs.clienteRel', 'c')
            ->leftJoin('cs.puestoRel', 'p')
            ->where('cs.codigoCierreFk = ' . $codigoCierre);
        return $queryBuilder->getQuery()->getResult();
    }

}
