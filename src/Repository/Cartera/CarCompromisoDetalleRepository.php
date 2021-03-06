<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCompromiso;
use App\Entity\Cartera\CarCompromisoDetalle;
use App\Entity\Cartera\CarConfiguracion;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarIngresoConcepto;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenConfiguracion;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarCompromisoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarCompromisoDetalle::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminarSeleccionados($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            $em = $this->getEntityManager();
            foreach ($arrSeleccionados AS $codigo) {
                $arCompromisoDetalle = $em->getRepository(CarCompromisoDetalle::class)->find($codigo);
                $em->remove($arCompromisoDetalle);
            }
            $em->flush();
        }
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(CarCompromisoDetalle::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();
    }

    public function listaFormato($codigoCompromiso)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCompromisoDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoCompromisoDetallePk')
            ->addSelect('cc.numeroDocumento AS cuentaCobrar')
            ->addSelect('cct.nombre AS cuentaCobrarTipo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cc.plazo')
            ->addSelect('cc.vrSaldoOriginal')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrSaldoOperado')
            ->leftJoin('cd.compromisoRel', 'com')
            ->leftJoin('cd.cuentaCobrarRel', 'cc')
            ->leftJoin('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cd.codigoCompromisoFk = ' . $codigoCompromiso);
        $queryBuilder->orderBy('cd.codigoCompromisoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult() ;
    }
}
