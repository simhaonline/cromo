<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurSimulacion;
use App\Entity\Turno\TurTurno;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurSimulacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurSimulacion::class);
    }

    public function listaProgramar($codigoPedidoDetalle){
        $session = new Session();
        $arSimulaciones = null;
        if($codigoPedidoDetalle) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSimulacion::class, 's')
                ->select('s.codigoSimulacionPk')
                ->addSelect('s.codigoEmpleadoFk')
                ->addSelect('s.nombreCorto')
                ->addSelect('s.anio')
                ->addSelect('s.mes')
                ->addSelect('s.dia1')
                ->addSelect('s.dia2')
                ->addSelect('s.dia3')
                ->addSelect('s.dia4')
                ->addSelect('s.dia5')
                ->addSelect('s.dia6')
                ->addSelect('s.dia7')
                ->addSelect('s.dia8')
                ->addSelect('s.dia9')
                ->addSelect('s.dia10')
                ->addSelect('s.dia11')
                ->addSelect('s.dia12')
                ->addSelect('s.dia13')
                ->addSelect('s.dia14')
                ->addSelect('s.dia15')
                ->addSelect('s.dia16')
                ->addSelect('s.dia17')
                ->addSelect('s.dia18')
                ->addSelect('s.dia19')
                ->addSelect('s.dia20')
                ->addSelect('s.dia21')
                ->addSelect('s.dia22')
                ->addSelect('s.dia23')
                ->addSelect('s.dia24')
                ->addSelect('s.dia25')
                ->addSelect('s.dia26')
                ->addSelect('s.dia27')
                ->addSelect('s.dia28')
                ->addSelect('s.dia29')
                ->addSelect('s.dia30')
                ->addSelect('s.dia31')
                ->where("s.codigoPedidoDetalleFk = {$codigoPedidoDetalle}");
            $arSimulaciones = $queryBuilder->getQuery()->getResult();
        }
        return $arSimulaciones;
    }


    public function listaExcel($codigoPedidoDetalle){
        $session = new Session();
        $arSimulaciones = null;
        if($codigoPedidoDetalle) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurSimulacion::class, 's')
                ->select('s.codigoSimulacionPk')
                ->addSelect('s.codigoEmpleadoFk')
                ->addSelect('s.nombreCorto')
                ->addSelect('s.anio')
                ->addSelect('s.mes')
                ->addSelect('s.dia1')
                ->addSelect('s.dia2')
                ->addSelect('s.dia3')
                ->addSelect('s.dia4')
                ->addSelect('s.dia5')
                ->addSelect('s.dia6')
                ->addSelect('s.dia7')
                ->addSelect('s.dia8')
                ->addSelect('s.dia9')
                ->addSelect('s.dia10')
                ->addSelect('s.dia11')
                ->addSelect('s.dia12')
                ->addSelect('s.dia13')
                ->addSelect('s.dia14')
                ->addSelect('s.dia15')
                ->addSelect('s.dia16')
                ->addSelect('s.dia17')
                ->addSelect('s.dia18')
                ->addSelect('s.dia19')
                ->addSelect('s.dia20')
                ->addSelect('s.dia21')
                ->addSelect('s.dia22')
                ->addSelect('s.dia23')
                ->addSelect('s.dia24')
                ->addSelect('s.dia25')
                ->addSelect('s.dia26')
                ->addSelect('s.dia27')
                ->addSelect('s.dia28')
                ->addSelect('s.dia29')
                ->addSelect('s.dia30')
                ->addSelect('s.dia31')
                ->addSelect('p.nombre as puestoNombre')
                ->leftJoin('s.pedidoDetalleRel','pd')
                ->join('pd.puestoRel','p')
                ->where("s.codigoPedidoDetalleFk = {$codigoPedidoDetalle}");
            $arSimulaciones = $queryBuilder->getQuery()->getResult();
        }
        return $arSimulaciones;
    }

    public function limpiar($codigoPedidoDetalle) {
        $em = $this->getEntityManager();
        $em->createQueryBuilder()->delete(TurSimulacion::class,'s')->andWhere("s.codigoPedidoDetalleFk = " . $codigoPedidoDetalle)->getQuery()->execute();
        $em->flush();
    }

}
