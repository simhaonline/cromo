<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Entity\Cartera\CarReciboTipo;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarRecibo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarRecibo::class, 'r')
            ->select('r.codigoReciboPk')
            ->leftJoin('r.clienteRel','cr')
            ->leftJoin('r.cuentaRel','c')
            ->addSelect('c.nombre')
            ->addSelect('cr.nombreCorto')
            ->addSelect('cr.numeroIdentificacion')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.vrPagoTotal')
            ->addSelect('r.usuario')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('r.estadoImpreso')
            ->where('r.codigoReciboPk <> 0')
            ->orderBy('r.codigoReciboPk', 'DESC');
        if ($session->get('filtroNumero')) {
            $queryBuilder->andWhere("r.numero = '{$session->get('filtroNumero')}'");
        }
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }

        return $queryBuilder;
    }

    public function autorizar($arRecibo)
    {
        $em = $this->getEntityManager();
        if($arRecibo->getEstadoAutorizado() == 0){
            $error = false;
            $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $arRecibo->getCodigoReciboPk()));
            if (count($em->getRepository(CarReciboDetalle::class)->findBy(['codigoReciboFk' => $arRecibo->getCodigoReciboPk()])) > 0){
                foreach ($arReciboDetalles AS $arReciboDetalle) {
                    if ($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                        $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                        if ($arCuentaCobrarAplicacion->getVrSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                            $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                            $arCuentaCobrarAplicacion->setvRSaldoOperado($saldoOperado);
                            $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrarAplicacion);
                            //Cuenta por cobrar
                            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                            $saldo = $arCuentaCobrar->getVrSaldo() - $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                            $arCuentaCobrar->setVrSaldo($saldo);
                            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrar);
                        } else {
                            Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaCobrarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaCobrarAplicacion->getVrSaldo());
                            $error = true;
                            break;
                        }

                    } else {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                        if($arCuentaCobrar->getVrSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaCobrar->getVrSaldo() - $arReciboDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                            $arCuentaCobrar->setVrSaldo($saldo);
                            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $arReciboDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaCobrar);
                        } else {
                            Mensajes::error("El saldo " . $arCuentaCobrar->getVrSaldo() . " de la cuenta por cobrar numero: " . $arCuentaCobrar->getNumeroDocumento() . " es menor al recibo detalle " . $arReciboDetalle->getVrPagoAfectar());
                            $error = true;
                            break;
                        }
                    }
                }
                if($error == false){
                    $arRecibo->setEstadoAutorizado(1);
                    $em->persist($arRecibo);
                    $em->flush();
                }
            } else {
                Mensajes::error("No se puede autorizar un recibo sin detalles");
            }
        }
    }

    public function desAutorizar($arRecibo)
    {
        $em = $this->getEntityManager();
        $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $arRecibo->getCodigoReciboPk()));
        foreach ($arReciboDetalles AS $arReciboDetalle) {
            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
            $saldo = $arCuentaCobrar->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
            $arCuentaCobrar->setVrSaldo($saldo);
            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
            $em->persist($arCuentaCobrar);
            if ($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
                $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                $arCuentaCobrarAplicacion->setVrSaldo($saldo);
                $arCuentaCobrarAplicacion->setVrSaldoOperado($saldoOperado);
                $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
                $em->persist($arCuentaCobrarAplicacion);
            }
        }
        $arRecibo->setEstadoAutorizado(0);
        $em->persist($arRecibo);
        $em->flush();
    }

    public function aprobar($arRecibo){
        $em = $this->getEntityManager();
        if($arRecibo->getEstadoAutorizado()){
            $arReciboTipo = $em->getRepository(CarReciboTipo::class)->find($arRecibo->getCodigoReciboTipoFk());
            if ($arRecibo->getNumero() == 0 || $arRecibo->getNumero() == NULL) {
                $arRecibo->setNumero($arReciboTipo->getConsecutivo());
                $arReciboTipo->setConsecutivo($arReciboTipo->getConsecutivo() + 1);
                $em->persist($arReciboTipo);
            }

            $arRecibo->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arRecibo);
            $this->getEntityManager()->flush();
        }
    }
}