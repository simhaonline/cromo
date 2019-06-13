<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCuentaCobrar;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarAplicacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarAplicacion::class);
    }

    public function anular($arAplicacion)
    {
        $em = $this->getEntityManager();
        if ($arAplicacion->getEstadoAprobado() && !$arAplicacion->getEstadoAnulado()) {
            $aplicar = $arAplicacion->getVrAplicacion();

            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arAplicacion->getCodigoCuentaCobrarFk());
            $saldo = $arCuentaCobrar->getVrSaldo() + $aplicar;
            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
            $arCuentaCobrar->setVrSaldo($saldo);
            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $aplicar);
            $em->persist($arCuentaCobrar);

            $arCuentaCobrarAplicar = $em->getRepository(CarCuentaCobrar::class)->find($arAplicacion->getCodigoCuentaCobrarAplicacionFk());
            $saldo = $arCuentaCobrarAplicar->getVrSaldo() + $aplicar;
            $saldoOperado = $saldo * $arCuentaCobrarAplicar->getOperacion();
            $arCuentaCobrarAplicar->setVrSaldo($saldo);
            $arCuentaCobrarAplicar->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrarAplicar->setVrAbono($arCuentaCobrarAplicar->getVrAbono() - $aplicar);
            $em->persist($arCuentaCobrarAplicar);

            $arAplicacion->setEstadoAnulado(1);
            $arAplicacion->setVrAplicacion(0);
            $em->persist($arAplicacion);
            $em->flush();
        } else {
            Mensajes::error('El registro debe estar aprobado, sin anular previamente y sin contabilizar');
        }
    }

    public function referencia($codigoCuentaCobrar)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAplicacion::class, 'a')
            ->select('a.codigoAplicacionPk')
            ->addSelect('a.numeroDocumento')
            ->addSelect('a.numeroDocumentoAplicacion')
            ->addSelect('a.estadoAnulado')
            ->addSelect('a.vrAplicacion')
            ->where('a.codigoCuentaCobrarFk = ' . $codigoCuentaCobrar)
            ->orWhere('a.codigoCuentaCobrarAplicacionFk = ' . $codigoCuentaCobrar);
        return $queryBuilder->getQuery()->getResult();
    }

    public function aplicar($arNotaCredito, $codigoMovimiento) {

    }

}