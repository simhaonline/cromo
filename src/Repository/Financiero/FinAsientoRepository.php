<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class FinAsientoRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinAsiento::class);
    }

	public function  lista($raw)
	{
		$limiteRegistros = $raw['limiteRegistros'] ?? 100;
		$filtros = $raw['filtros'] ?? null;

		$numero = null;
		$codigoComprobante = null;
		$fechaDesde = null;
		$fechaHasta = null;
		$estadoAutorizado = null;
		$estadoAprobado = null;
		$estadoAnulado = null;

		if ($filtros) {
			$numero = $filtros['numero'] ?? null;
			$codigoComprobante = $filtros['codigoComprobante'] ?? null;
			$fechaDesde = $filtros['fechaDesde'] ?? null;
			$fechaHasta = $filtros['fechaHasta'] ?? null;
			$estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
			$estadoAprobado = $filtros['estadoAprobado'] ?? null;
			$estadoAnulado = $filtros['estadoAnulado'] ?? null;
		}

		$queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsiento::class, 'a')
			->select('a.codigoAsientoPk')
			->addSelect('a.numero')
			->addSelect('a.fecha')
			->addSelect('a.fechaContable')
			->addSelect('a.vrDebito')
			->addSelect('a.vrCredito')
			->addSelect('a.estadoAutorizado')
			->addSelect('a.estadoAprobado')
			->addSelect('a.estadoAnulado')
			->addSelect('c.nombre as comprobante')
			->leftJoin ('a.comprobanteRel', 'c');
		if ($numero) {
			$queryBuilder->andWhere("a.numero = {$numero}");
		}
		if ($numero) {
			$queryBuilder->andWhere("a.numero = {$numero}");
		}
		if ($codigoComprobante) {
			$queryBuilder->andWhere("a.codigoComprobanteFk = {$codigoComprobante}");
		}
		if ($fechaDesde) {
			$queryBuilder->andWhere("a.fecha >= '{$fechaDesde} 00:00:00'");
		}
		if ($fechaHasta) {
			$queryBuilder->andWhere("a.fecha <= '{$fechaHasta} 23:59:59'");
		}
		switch ($estadoAutorizado) {
			case '0':
				$queryBuilder->andWhere("a.estadoAutorizado = 0");
				break;
			case '1':
				$queryBuilder->andWhere("a.estadoAutorizado = 1");
				break;
		}
		switch ($estadoAprobado) {
			case '0':
				$queryBuilder->andWhere("a.estadoAprobado = 0");
				break;
			case '1':
				$queryBuilder->andWhere("a.estadoAprobado = 1");
				break;
		}
		switch ($estadoAnulado) {
			case '0':
				$queryBuilder->andWhere("a.estadoAnulado = 0");
				break;
			case '1':
				$queryBuilder->andWhere("a.estadoAnulado = 1");
				break;
		}
		$queryBuilder->addOrderBy('a.codigoAsientoPk', 'DESC');
		$queryBuilder->setMaxResults($limiteRegistros);
		return $queryBuilder->getQuery()->getResult();
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
        $em = $this->getEntityManager();
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
                    $em->persist($arAsiento);
                    $em->flush();
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
        $em = $this->getEntityManager();
        if ($arAsiento->getEstadoAutorizado()) {
            $arAsiento->setEstadoAutorizado(0);
            $em->persist($arAsiento);
            $em->flush();

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
                $arComprobante = $em->getRepository(FinComprobante::class)->find($arAsiento->getCodigoComprobanteFk());
                if ($arAsiento->getNumero() == 0 || $arAsiento->getNumero() == NULL) {
                    $arAsiento->setNumero($arComprobante->getConsecutivo());
                    $arComprobante->setConsecutivo($arComprobante->getConsecutivo() + 1);
                    $em->persist($arComprobante);
                    $arAsiento->setFecha(new \DateTime('now'));
                }
                $arAsiento->setEstadoAprobado(1);
                $em->persist($arAsiento);
                $em->flush();
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
                $arTercero = null;
                if($arrTercero[$codigo]) {
                    $arTercero = $em->getRepository(FinTercero::class)->find($arrTercero[$codigo]);
                    if ($arTercero) {
                        $arAsientoDetalle->setTerceroRel($arTercero);
                    } else {
                        $error = true;
                        Mensajes::error("El tercero no existe.");
                    }
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
//                } else {
//                    $error = true;
//                    Mensajes::error("La cuenta " . $arCuenta->getCodigoCuentaPk() . " " . $arCuenta->getNombre() . " no permite movimiento.");
//                }
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

    public function registroContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsiento::class, 'a')
            ->select('a.codigoAsientoPk')
            ->addSelect('a.fechaContable')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoContabilizado')
            ->addSelect('a.codigoComprobanteFk')
            ->where('a.codigoAsientoPk = ' . $codigo);
        $ar = $queryBuilder->getQuery()->getSingleResult();
        return $ar;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //$arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarRecibo();
            foreach ($arr AS $codigo) {
                $arAsiento = $em->getRepository(FinAsiento::class)->find($codigo);
                if ($arAsiento) {
                    if ($arAsiento->getEstadoAprobado() && !$arAsiento->getEstadoContabilizado()) {
                        $arAsientoDetalles = $em->getRepository(FinAsientoDetalle::class)->findBy(array('codigoAsientoFk' => $arAsiento->getCodigoAsientoPk()));
                        foreach ($arAsientoDetalles as $arAsientoDetalle) {
                            $arRegistro = new FinRegistro();
                            $arRegistro->setComprobanteRel($arAsiento->getComprobanteRel());
                            $arRegistro->setFecha($arAsiento->getFechaContable());
                            $arRegistro->setNumero($arAsiento->getNumero());
                            $arRegistro->setVrDebito($arAsientoDetalle->getVrDebito());
                            $arRegistro->setVrCredito($arAsientoDetalle->getVrCredito());
                            if ($arAsientoDetalle->getVrDebito() > 0) {
                                $arRegistro->setNaturaleza("D");
                            } else {
                                $arRegistro->setNaturaleza("C");
                            }
                            $arRegistro->setVrBase($arAsientoDetalle->getVrBase());
                            $arRegistro->setCuentaRel($arAsientoDetalle->getCuentaRel());
                            $arRegistro->setDescripcion("");
                            $arRegistro->setTerceroRel($arAsientoDetalle->getTerceroRel());
                            $arRegistro->setCodigoModeloFk('FinAsiento');
                            $arRegistro->setCodigoDocumento($arAsiento->getCodigoAsientoPk());
                            $em->persist($arRegistro);
                        }
                        $arAsiento->setEstadoContabilizado(1);
                        $em->persist($arAsiento);
                    }
                } else {
                    $error = "La asiento codigo " . $codigo . " no existe";
                    break;
                }
            }
            if ($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }

        }
        return true;
    }

	/**
	 * @param $arrSeleccionados
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	public function eliminar($arrSeleccionados)
	{
		try{
			foreach ($arrSeleccionados as $arrSeleccionado) {
				$arRegistro = $this->getEntityManager()->getRepository(FinAsiento::class)->find($arrSeleccionado);
				if ($arRegistro) {
					$this->getEntityManager()->remove($arRegistro);
				}
			}
			$this->getEntityManager()->flush();
		} catch (\Exception $ex) {
			Mensajes::error("El registro tiene registros relacionados");
		}
	}
}