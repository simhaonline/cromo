<?php

namespace App\Repository\Inventario;


use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Inventario\InvCosto;
use App\Entity\Inventario\InvCostoDetalle;
use App\Entity\Inventario\InvCostoTipo;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Entity\Inventario\InvTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Utilidades\Mensajes;

class InvCostoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvCosto::class);
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCosto = null;
        $codigoCostoTipo = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoCosto = $filtros['codigoCosto'] ?? null;
            $codigoCostoTipo = $filtros['codigoCostoTipo'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvCosto::class, 'c')
            ->select('c.codigoCostoPk')
            ->addSelect('ct.nombre as costoTipo')
            ->addSelect('c.anio')
            ->addSelect('c.mes')
            ->addSelect('c.vrCosto')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado')
            ->leftJoin('c.costoTipoRel', 'ct');

        if ($codigoCosto) {
            $queryBuilder->andWhere("c.codigoCostoPk = '{$codigoCosto}'");
        }
        if ($codigoCostoTipo) {
            $queryBuilder->andWhere("c.codigoCostoTipoFk = '{$codigoCostoTipo}'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAnulado = 1");
                break;
        }

        $queryBuilder->addOrderBy('c.codigoCostoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $codigoCosto
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoCosto)
    {
        $em = $this->getEntityManager();
        $arCosto = $em->getRepository(InvCosto::class)->find($codigoCosto);
        $totalCosto = 0;
        $arCostoDetalles = $em->getRepository(InvCostoDetalle::class)->findBy(['codigoCostoFk' => $codigoCosto]);
        foreach ($arCostoDetalles as $arCostoDetalle) {
            $totalCosto += $arCostoDetalle->getVrCosto();
        }
        $arCosto->setVrCosto($totalCosto);
        $em->persist($arCosto);
        $em->flush();
    }

    /**
     * @param $arCosto InvCosto
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arCosto)
    {
        $em = $this->getEntityManager();
        if (!$arCosto->getEstadoAutorizado()) {
            $arMovimientoDetalles = $em->getRepository(InvMovimientoDetalle::class)->costoVentas($arCosto->getAnio(), $arCosto->getMes());
            foreach ($arMovimientoDetalles as $arMovimientoDetalle) {
                $arItem = $em->getRepository(InvItem::class)->find($arMovimientoDetalle['codigoItemFk']);
                $arCostoDetalle = new InvCostoDetalle();
                $arCostoDetalle->setCostoRel($arCosto);
                $arCostoDetalle->setItemRel($arItem);
                $arCostoDetalle->setVrCosto($arMovimientoDetalle['vrCosto']);
                $em->persist($arCostoDetalle);
            }
            $arCosto->setEstadoAutorizado(1);
            $em->persist($arCosto);
            $em->flush();
            $this->liquidar($arCosto->getCodigoCostoPk());
        }
    }

    /**
     * @param $arCosto InvCosto
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCosto)
    {
        $em = $this->getEntityManager();
        if ($arCosto->getEstadoAutorizado()) {
            $arCostoDetalles = $em->getRepository(InvCostoDetalle::class)->findBy(['codigoCostoFk' => $arCosto->getCodigoCostoPk()]);
            foreach ($arCostoDetalles as $arCostoDetalle) {
                $em->remove($arCostoDetalle);
            }
            $arCosto->setEstadoAutorizado(0);
            $em->persist($arCosto);
            $em->flush();
            $this->liquidar($arCosto->getCodigoCostoPk());
        }
    }

    /**
     * @param $arImportacion InvCosto
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arCosto)
    {
        $em = $this->getEntityManager();
        if ($arCosto->getEstadoAutorizado() && !$arCosto->getEstadoAnulado()) {
            if ($arCosto->getNumero() == 0 || $arCosto->getNumero() == "") {
                $arCostoTipo = $this->getEntityManager()->getRepository(InvCostoTipo::class)->find($arCosto->getCodigoCostoTipoFk());
                if ($arCostoTipo) {
                    $arCostoTipo->setConsecutivo($arCostoTipo->getConsecutivo() + 1);
                    $arCosto->setNumero($arCostoTipo->getConsecutivo());
                    $em->persist($arCostoTipo);
                }
            }
            $arCosto->setEstadoAprobado(1);
            $em->persist($arCosto);
            $em->flush();
            /*$arConfiguracion = $em->getRepository(GenConfiguracion::class)->contabilidadAutomatica();
            if ($arConfiguracion['contabilidadAutomatica']) {
                $this->contabilizar(array($arImportacion->getCodigoImportacionPk()));
            }*/
        }
    }

    public function registroContabilizar($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvCosto::class, 'c')
            ->select('c.codigoCostoPk')
            ->addSelect('c.codigoTerceroFk')
            ->addSelect('c.numero')
            ->addSelect('c.anio')
            ->addSelect('c.mes')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoContabilizado')
            ->addSelect('ct.codigoComprobanteFk')
            ->addSelect('ct.prefijo')
            ->leftJoin('c.costoTipoRel', 'ct')
            ->where('c.codigoCostoPk = ' . $codigo);
        $arCosto = $queryBuilder->getQuery()->getSingleResult();
        return $arCosto;
    }

    /**
     * @param $arr
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            foreach ($arr AS $codigo) {
                $arCosto = $em->getRepository(InvCosto::class)->registroContabilizar($codigo);
                if ($arCosto) {
                    if ($arCosto['estadoAprobado'] == 1 && $arCosto['estadoContabilizado'] == 0) {
                        if (!$arCosto['codigoComprobanteFk']) {
                            $error = "El comprobante en el tipo de costo no esta configurado";
                            break;
                        }
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arCosto['codigoComprobanteFk']);
                        if($arComprobante) {
                            $arTercero = $em->getRepository(InvTercero::class)->terceroFinanciero($arCosto['codigoTerceroFk']);
                            //Cliente
                            $arCostoDetalles = $em->getRepository(InvCostoDetalle::class)->findBy(array('codigoCostoFk' => $arCosto['codigoCostoPk']));
                            foreach ($arCostoDetalles as $arCostoDetalle) {
                                //Cuenta del costo
                                $codigoCuenta = $arCostoDetalle->getItemRel()->getCodigoCuentaCostoFk();
                                if($codigoCuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta " . $codigoCuenta . " del costo del item " . $arCostoDetalle->getCodigoItemFk();
                                        break;
                                    }
                                    $fecha = date_create($arCosto['anio']."-".$arCosto['mes']."-01");
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arCosto['numero']);
                                    $arRegistro->setNumeroPrefijo($arCosto['prefijo']);
                                    $arRegistro->setFecha($fecha);
                                    $arRegistro->setVrDebito($arCostoDetalle->getVrCosto());
                                    $arRegistro->setNaturaleza('D');
                                    $arRegistro->setDescripcion($arCuenta->getNombre());
                                    $arRegistro->setCodigoModeloFk('InvCosto');
                                    $arRegistro->setCodigoDocumento($arCosto['codigoCostoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El item " . $arCostoDetalle->getCodigoItemFk() . " no tiene configurada la cuenta del costo";
                                    break;
                                }

                                //Cuenta compra
                                if($arCostoDetalle->getVrCosto() > 0) {
                                    $codigoCuenta = $arCostoDetalle->getItemRel()->getCodigoCuentaCompraFk();
                                    if($codigoCuenta) {
                                        $arCuenta = $em->getRepository(FinCuenta::class)->find($codigoCuenta);
                                        if (!$arCuenta) {
                                            $error = "No se encuentra la cuenta " . $codigoCuenta . " de compras del item " . $arCostoDetalle->getCodigoItemFk();
                                            break;
                                        }
                                        $fecha = date_create($arCosto['anio']."-".$arCosto['mes']."-01");
                                        $arRegistro = new FinRegistro();
                                        $arRegistro->setTerceroRel($arTercero);
                                        $arRegistro->setCuentaRel($arCuenta);
                                        $arRegistro->setComprobanteRel($arComprobante);
                                        $arRegistro->setNumero($arCosto['numero']);
                                        $arRegistro->setNumeroPrefijo($arCosto['prefijo']);
                                        $arRegistro->setFecha($fecha);
                                        $arRegistro->setVrCredito($arCostoDetalle->getVrCosto());
                                        $arRegistro->setNaturaleza('C');
                                        $arRegistro->setDescripcion($arCuenta->getNombre());
                                        $arRegistro->setCodigoModeloFk('InvCosto');
                                        $arRegistro->setCodigoDocumento($arCosto['codigoCostoPk']);
                                        $em->persist($arRegistro);
                                    } else {
                                        $error = "El item " . $arCostoDetalle->getCodigoItemFk() . " no tiene configurada la cuenta de compras";
                                        break;
                                    }
                                }
                            }

                            $arCostoAct = $em->getRepository(InvCosto::class)->find($arCosto['codigoCostoPk']);
                            $arCostoAct->setEstadoContabilizado(1);
                            $em->persist($arCostoAct);
                        } else {
                            $error = "El comprobante " . $arCosto['codigoComprobanteFk'] . " no existe";
                            break;
                        }
                    }
                } else {
                    $error = "El costo codigo " . $codigo . " no existe";
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
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvCosto::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvCostoDetalle::class)->findBy(['codigoCostoFk' => $arRegistro->getCodigoCostoPk()])) <= 0) {
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

    
}