<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComEgreso;
use App\Entity\Compra\ComEgresoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComEgreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComEgreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComEgreso[]    findAll()
 * @method ComEgreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComEgresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComEgreso::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arEgreso ComEgreso
         *
         */
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(ComEgreso::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(ComEgresoDetalle::class)->findBy(['codigoEgresoFk' => $arRegistro->getCodigoEgresoPk()])) <= 0) {
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
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    public function autorizar($arEgreso)
    {
        $em = $this->getEntityManager();
        if ($arEgreso->getEstadoAutorizado() == 0) {
            $error = false;
            $arEgresosDetalles = $em->getRepository(ComEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
            if ($arEgresosDetalles) {
                foreach ($arEgresosDetalles AS $arEgresoDetalle) {
                    if ($arEgresoDetalle->getCodigoCuentaPagarFk()) {
                        $arCuentaPagarAplicacion = $em->getRepository(ComCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
                        if ($arCuentaPagarAplicacion->getVrSaldo() >= $arEgresoDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaPagarAplicacion->getVrSaldo() - $arEgresoDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaPagarAplicacion->getOperacion();
                            $arCuentaPagarAplicacion->setVrSaldo($saldo);
                            $arCuentaPagarAplicacion->setvRSaldoOperado($saldoOperado);
                            $arCuentaPagarAplicacion->setVrAbono($arCuentaPagarAplicacion->getVrAbono() + $arEgresoDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaPagarAplicacion);
                            //Cuenta por pagar
//                            $arCuentaPagar = $em->getRepository(ComCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
//                            $saldo = $arCuentaPagar->getVrSaldo() - $arEgresoDetalle->getVrPagoAfectar();
//                            $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
//                            $arCuentaPagar->setVrSaldo($saldo);
//                            $arCuentaPagar->setVrSaldoOperado($saldoOperado);
//                            $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() + $arEgresoDetalle->getVrPagoAfectar());
//                            $em->persist($arCuentaPagar);
                        } else {
                            Mensajes::error('El valor a afectar del documento aplicacion ' . $arCuentaPagarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaPagarAplicacion->getVrSaldo());
                            $error = true;
                            break;
                        }

                    } else {
                        $arCuentaPagar = $em->getRepository(ComCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
                        if ($arCuentaPagar->getVrSaldo() >= $arEgresoDetalle->getVrPagoAfectar()) {
                            $saldo = $arCuentaPagar->getVrSaldo() - $arEgresoDetalle->getVrPagoAfectar();
                            $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
                            $arCuentaPagar->setVrSaldo($saldo);
                            $arCuentaPagar->setVrSaldoOperado($saldoOperado);
                            $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() + $arEgresoDetalle->getVrPagoAfectar());
                            $em->persist($arCuentaPagar);
                        } else {
                            Mensajes::error("El saldo " . $arCuentaPagar->getVrSaldo() . " de la cuenta por cobrar numero: " . $arCuentaPagar->getNumeroDocumento() . " es menor al egreso detalle " . $arEgresoDetalle->getVrPagoAfectar());
                            $error = true;
                            break;
                        }
                    }
                }
                if ($error == false) {
                    $arEgreso->setEstadoAutorizado(1);
                    $em->persist($arEgreso);
                    $em->flush();
                }
            } else {
                Mensajes::error("No se puede autorizar un recibo sin detalles");
            }
        }
    }

    public function desAutorizar($arEgreso)
    {
        $em = $this->getEntityManager();
        $arEgresoDetalles = $em->getRepository(ComEgresoDetalle::class)->findBy(array('codigoEgresoFk' => $arEgreso->getCodigoEgresoPk()));
        foreach ($arEgresoDetalles AS $arEgresoDetalle) {
            $arCuentaPagar = $em->getRepository(ComCuentaPagar::class)->find($arEgresoDetalle->getCodigoCuentaPagarFk());
            $saldo = $arCuentaPagar->getVrSaldo() + $arEgresoDetalle->getVrPagoAfectar();
            $saldoOperado = $saldo * $arCuentaPagar->getOperacion();
            $arCuentaPagar->setVrSaldo($saldo);
            $arCuentaPagar->setVrSaldoOperado($saldoOperado);
            $arCuentaPagar->setVrAbono($arCuentaPagar->getVrAbono() - $arEgresoDetalle->getVrPagoAfectar());
            $em->persist($arCuentaPagar);
//            if ($arEgresoDetalle->getCodigoCuentaCobrarAplicacionFk()) {
//                $arCuentaCobrarAplicacion = $em->getRepository(CarCuentaCobrar::class)->find($arEgresoDetalle->getCodigoCuentaCobrarAplicacionFk());
//                $saldo = $arCuentaCobrarAplicacion->getVrSaldo() + $arEgresoDetalle->getVrPagoAfectar();
//                $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
//                $arCuentaCobrarAplicacion->setVrSaldo($saldo);
//                $arCuentaCobrarAplicacion->setVrSaldoOperado($saldoOperado);
//                $arCuentaCobrarAplicacion->setVrAbono($arCuentaCobrarAplicacion->getVrAbono() - $arEgresoDetalle->getVrPagoAfectar());
//                $em->persist($arCuentaCobrarAplicacion);
//            }
        }
        $arEgreso->setEstadoAutorizado(0);
        $em->persist($arEgreso);
        $em->flush();
    }

}
