<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinAsientoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinAsiento::class);
    }


    /**
     * @param $codigoAsiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoAsiento)
    {
        $em = $this->getEntityManager();
        $arAsiento = $em->getRepository(FinAsiento::class)->find($codigoAsiento);
        $arAsientoDetalles = $em->getRepository(FinAsientoDetalle::class)->findBy(['codigoAsientoFk' => $codigoAsiento]);
        $debitoGeneral = 0;
        $creditoGeneral = 0;
        foreach ($arAsientoDetalles as $arAsientoDetalle) {
            //$arAsientoDetalleAct = $em->getRepository(FinAsientoDetalle::class)->find($arAsientoDetalle->getCodigoAsientoDetallePk());
            $debitoGeneral += $arAsientoDetalle->getVrDebito();
            $creditoGeneral += $arAsientoDetalle->getVrCredito();
            //$arAsientoDetalleAct->setVrIva($iva);
            //$arAsientoDetalleAct->setVrTotal($total);
            //$em->persist($arAsientoDetalleAct);
        }
        $arAsiento->setVrDebito($debitoGeneral);
        $arAsiento->setVrCredito($creditoGeneral);
        $em->persist($arAsiento);
        $em->flush();
    }

    /**
     * @param $arAsiento FinAsiento
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arAsiento)
    {
        if (!$arAsiento->getEstadoAutorizado()) {
            $registros = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
                ->select('COUNT(ad.codigoAsientoDetallePk) AS registros')
                ->addSelect('SUM(ad.vrDebito) AS debito, SUM(ad.vrCredito) AS credito')
                ->where('ad.codigoAsientoFk = ' . $arAsiento->getCodigoAsientoPk())
                ->getQuery()->getResult();


            if ($registros[0]['registros'] > 0) {
                if ($registros[0]['debito'] != $registros[0]['credito']) {
                    Mensajes::error("Debitos y Creditos deben ser iguales");
                } else {
                    $arAsiento->setEstadoAutorizado(1);
                    $this->getEntityManager()->persist($arAsiento);
                    $this->getEntityManager()->flush();
                }
            } else {
                Mensajes::error("El registro no tiene detalles");
            }
        } else {
            Mensajes::error('El documento ya esta autorizado');
        }
    }

    /**
     * @param $arAsiento FinAsiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arAsiento)
    {
        if ($arAsiento->getEstadoAutorizado()) {
            $arAsiento->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arAsiento);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');
        }
    }

    /**
     * @param $arAsiento FinAsiento
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arAsiento)
    {
        $em = $this->getEntityManager();
        if ($arAsiento->getEstadoAutorizado() == 1 && $arAsiento->getEstadoAprobado() == 0) {
            if ($arAsiento->getVrDebito() == $arAsiento->getVrCredito()) {
                $arAsientoDetalles = $em->getRepository(FinAsientoDetalle::class)->findBy(array('codigoAsientoFk' => $arAsiento->getCodigoAsientoPk()));
                foreach ($arAsientoDetalles AS $arAsientoDetalle) {
                    $arRegistro = new FinRegistro();
                    $arRegistro->setComprobanteRel($arAsiento->getComprobanteRel());
                    $arRegistro->setFecha($arAsiento->getFechaContable());
                    $arRegistro->setNumero($arAsiento->getNumero());
                    $arRegistro->setNumeroReferencia($arAsiento->getNumero());
                    $arRegistro->setVrDebito($arAsientoDetalle->getVrDebito());
                    $arRegistro->setVrCredito($arAsientoDetalle->getVrCredito());
                    if ($arAsientoDetalle->getVrDebito() > 0) {
                        $arRegistro->setNaturaleza("D");
                    } else {
                        $arRegistro->setNaturaleza("C");
                    }
                    $arRegistro->setVrBase($arAsientoDetalle->getVrBase());
                    $arRegistro->setCuentaRel($arAsientoDetalle->getCuentaRel());
                    $arRegistro->setDescripcion($arAsientoDetalle->getCuentaRel()->getNombre());
                    $arRegistro->setTerceroRel($arAsientoDetalle->getTerceroRel());
                    $em->persist($arRegistro);
                }

                $arAsiento->setEstadoAprobado(1);
                $this->getEntityManager()->persist($arAsiento);
                $this->getEntityManager()->flush();
            } else {
                Mensajes::error('El asiento esta descuadrado y no se puede aprobar');
            }

        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    /**
     * @param $codigoAsiento
     * @param $arrControles
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($codigoAsiento, $arrControles)
    {
        $em = $this->getEntityManager();
        $error = false;
        if ($this->contarDetalles($codigoAsiento) > 0) {
            $arrCuenta = $arrControles['arrCuenta'];
            $arrTercero = $arrControles['arrTercero'];
            $arrCodigo = $arrControles['TxtCodigo'];
            $arrDebito = $arrControles['TxtDebito'];
            $arrCredito = $arrControles['TxtCredito'];
            $arrBase = $arrControles['TxtBase'];
            foreach ($arrCodigo as $codigo) {
                $arAsientoDetalle = $em->getRepository(FinAsientoDetalle::class)->find($codigo);
                if ($arrCuenta[$codigo]) {
                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCuenta[$codigo]);
                    if ($arCuenta) {
                        $arAsientoDetalle->setCuentaRel($arCuenta);
                    } else {
                        $arAsientoDetalle->setCuentaRel(null);
                    }
                } else {
                    $arAsientoDetalle->setCuentaRel(null);
                }
                if ($arCuenta->getPermiteMovimiento()) {
                    // validacion de tercero
                    if ($arCuenta->getExigeTercero()) {
                        if ($arrTercero[$codigo]) {
                            $arTercero = $em->getRepository(FinTercero::class)->find($arrTercero[$codigo]);
                            if ($arTercero) {
                                $arAsientoDetalle->setTerceroRel($arTercero);
                            } else {
                                $error = true;
                                Mensajes::error("El tercero no existe.");
                            }
                        } else {
                            $error = true;
                            Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " exige tercero.");
                        }
                    } else {
                        $arAsientoDetalle->setTerceroRel(null);
                    }
                    // validaciones de base
                    if ($arCuenta->getExigeBase()) {
                        if ($arrBase[$codigo] == 0) {
                            $error = true;
                            Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " exige base.");
                        } else {
                            $arAsientoDetalle->setVrBase($arrBase[$codigo]);
                        }
                    } else {
                        $arAsientoDetalle->setVrBase(0);
                    }

                    //validacion debitos y creditos
                    $arAsientoDetalle->setVrDebito($arrDebito[$codigo] != '' ? $arrDebito[$codigo] : 0);
                    $arAsientoDetalle->setVrCredito($arrCredito[$codigo] != '' ? $arrCredito[$codigo] : 0);
                    if ($arrDebito[$codigo] > 0 && $arrCredito[$codigo] > 0) {
                        $error = true;
                        Mensajes::error("Por cada linea solo el debito o credito puede tener valor mayor a cero.");
                    }
                    $em->persist($arAsientoDetalle);
                } else {
                    $error = true;
                    Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " no permite movimiento.");
                }
            }
        }
        if ($error == false) {
            $em->flush();
            $this->liquidar($codigoAsiento);
        }
        return $error;

    }

    /**
     * @param $codigo
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoAsiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
            ->select("COUNT(ad.codigoAsientoDetallePk)")
            ->where("ad.codigoAsientoFk = {$codigoAsiento} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }


}