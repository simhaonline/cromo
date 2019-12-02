<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarAnticipo;
use App\Entity\Cartera\CarAnticipoDetalle;
use App\Entity\Cartera\CarAnticipoTipo;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarConfiguracion;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenConfiguracion;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CarAnticipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarAnticipo::class);
    }

    public function lista($raw)
    {

        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoCliente = null;
        $numero = null;
        $codigoAnticipo = null;
        $codigoAnticipoTipo = null;
        $codigoAsesor =null;
        $fechaDesde =  null;
        $fechaHasta =  null;
        $estadoAutorizado = null;
        $estadoAprobado =  null;
        $estadoAnulado =null;
        if ($filtros) {
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $numero = $filtros['numero'] ?? null;
            $codigoAnticipo = $filtros['codigoAnticipo'] ?? null;
            $codigoAnticipoTipo = $filtros['codigoAnticipoTipo'] ?? null;
            $codigoAsesor = $filtros['codigoAsesor'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAnticipo::class, 'a')
            ->select('a.codigoAnticipoPk')
            ->addSelect('a.numero')
            ->addSelect('a.fecha')
            ->addSelect('a.fechaPago')
            ->addSelect('a.vrPago')
            ->addSelect('a.usuario')
            ->addSelect('a.estadoAutorizado')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoAnulado')
            ->addSelect('at.nombre as anticipoTipo')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto as cliente')
            ->addSelect('cu.nombre as cuenta')
            ->leftJoin('a.anticipoTipoRel', 'at')
            ->leftJoin('a.clienteRel', 'cl')
            ->leftJoin('a.cuentaRel', 'cu')
            ->addOrderBy('a.fecha', 'DESC');
        if ($codigoCliente) {
            $queryBuilder->andWhere("cl.codigoClientePk = '{$codigoCliente}'");
        }
        if ($numero) {
            $queryBuilder->andWhere("a.numero = '{$numero}'");
        }
        if ($codigoAnticipo) {
            $queryBuilder->andWhere("a.codigoAnticipoPk = '{$codigoAnticipo}'");
        }
        if ($codigoAnticipoTipo) {
            $queryBuilder->andWhere("a.codigoAnticipoTipoFk = '{$codigoAnticipoTipo}'");
        }
        if ($codigoAsesor) {
            $queryBuilder->andWhere("a.codigoAsesorFk = '{$codigoAsesor}'");
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
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
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
                $arRegistro = $this->getEntityManager()->getRepository(CarAnticipo::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(CarAnticipoDetalle::class)->findBy(['codigoAnticipoFk' => $arRegistro->getCodigoAnticipoPk()])) <= 0) {
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

    /**
     * @param $codigoAnticipo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoAnticipo)
    {
        $em = $this->getEntityManager();
        $arAnticipo = $em->getRepository(CarAnticipo::class)->find($codigoAnticipo);
        $arAnticipoDetalles = $em->getRepository(CarAnticipoDetalle::class)->findBy(['codigoAnticipoFk' => $codigoAnticipo]);
        $pagoTotalGeneral = 0;
        foreach ($arAnticipoDetalles as $arAnticipoDetalle) {
            $arAnticipoDetalleAct = $em->getRepository(CarAnticipoDetalle::class)->find($arAnticipoDetalle->getCodigoAnticipoDetallePk());
            $subtotal = $arAnticipoDetalle->getVrPago();
            $pagoTotalGeneral += $subtotal;
            $arAnticipoDetalleAct->setVrPago($subtotal);
            $em->persist($arAnticipoDetalleAct);
        }
        $arAnticipo->setVrPago($pagoTotalGeneral);
        $em->persist($arAnticipo);
        $em->flush();
    }

    /**
     * @param $arAnticipo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arAnticipo)
    {
        if (count($this->_em->getRepository(CarAnticipoDetalle::class)->findBy(['codigoAnticipoFk' => $arAnticipo->getCodigoAnticipoPk()])) > 0) {
            $arAnticipo->setEstadoAutorizado(1);
            $this->_em->persist($arAnticipo);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arAnticipo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arAnticipo)
    {
        if ($arAnticipo->getEstadoAutorizado()) {
            $arAnticipo->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arAnticipo);
            $this->getEntityManager()->flush();

        } else {
            Mensajes::error('El documento no esta autorizado');

        }
    }

    /**
     * @param $arAnticipo CarAnticipo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arAnticipo)
    {
        $em = $this->getEntityManager();
        if ($arAnticipo->getEstadoAprobado() == 0) {
            $arAnticipoTipo = $this->getEntityManager()->getRepository(CarAnticipoTipo::class)->find($arAnticipo->getCodigoAnticipoTipoFk());
            if($arAnticipoTipo){
                if ($arAnticipo->getNumero() == 0 || $arAnticipo->getNumero() == "") {
                    $arAnticipoTipo->setConsecutivo($arAnticipoTipo->getConsecutivo() + 1);
                    $arAnticipo->setNumero($arAnticipoTipo->getConsecutivo());
                    $this->getEntityManager()->persist($arAnticipoTipo);
                }
            }
            $arAnticipo->setEstadoAprobado(1);
            $this->getEntityManager()->persist($arAnticipo);

            $arClienteCartera = $em->getRepository(CarCliente::class)->findOneBy(['codigoIdentificacionFk' => $arAnticipo->getClienteRel()->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arAnticipo->getClienteRel()->getNumeroIdentificacion()]);

            $arCuentaCobrarTipo = $em->getRepository(CarCuentaCobrarTipo::class)->find($arAnticipo->getAnticipoTipoRel()->getCodigoCuentaCobrarTipoFk());
            $arCuentaCobrar = new CarCuentaCobrar();
            $arCuentaCobrar->setClienteRel($arClienteCartera);
            $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
            $arCuentaCobrar->setFecha($arAnticipo->getFecha());
            $arCuentaCobrar->setFechaVence($arAnticipo->getFecha());
            $arCuentaCobrar->setModulo("CAR");
            $arCuentaCobrar->setNumeroDocumento($arAnticipo->getNumero());
            $arCuentaCobrar->setSoporte($arAnticipo->getSoporte());
            $arCuentaCobrar->setVrSubtotal($arAnticipo->getVrPago());
            $arCuentaCobrar->setVrIva(0);
            $arCuentaCobrar->setVrTotal($arAnticipo->getVrPago());
            $arCuentaCobrar->setVrRetencionFuente(0);
            $arCuentaCobrar->setVrRetencionIva(0);
            $arCuentaCobrar->setVrSaldo($arAnticipo->getVrPago());
            $arCuentaCobrar->setVrSaldoOperado($arAnticipo->getVrPago() * $arCuentaCobrarTipo->getOperacion());
            $arCuentaCobrar->setPlazo(0);
            $arCuentaCobrar->setOperacion($arCuentaCobrarTipo->getOperacion());
            $arCuentaCobrar->setComentario($arAnticipo->getComentarios());
            $arCuentaCobrar->setAsesorRel($arAnticipo->getAsesorRel());
            $arCuentaCobrar->setAnticipo(1);
            $em->persist($arCuentaCobrar);
            $this->getEntityManager()->flush();
            $arConfiguracion = $em->getRepository(GenConfiguracion::class)->contabilidadAutomatica();
            if ($arConfiguracion['contabilidadAutomatica']) {
                $this->contabilizar(array($arAnticipo->getCodigoAnticipoPk()));
            }
        } else {
            Mensajes::error("El anticipo ya fue aprobado aprobado");
        }
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAnticipo::class, 'a')
            ->select('a.codigoAnticipoPk')
            ->addSelect('a.numero')
            ->addSelect('a.fecha')
            ->addSelect('a.vrPago')
            ->addSelect('a.codigoClienteFk')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoContabilizado')
            ->addSelect('at.codigoComprobanteFk')
            ->addSelect('at.prefijo')
            ->addSelect('c.codigoCuentaContableFk')
            ->addSelect('acct.codigoCuentaAplicacionFk')
            ->leftJoin('a.anticipoTipoRel', 'at')
            ->leftJoin('a.cuentaRel', 'c')
            ->leftJoin('at.cuentaCobrarTipoRel', 'acct')
            ->where('a.codigoAnticipoPk = ' . $codigo);
        $arAnticipo = $queryBuilder->getQuery()->getSingleResult();
        return $arAnticipo;
    }

    public function contabilizar($arr): bool
    {
        $em = $this->getEntityManager();
        if ($arr) {
            $error = "";
            //$arConfiguracion = $em->getRepository(CarConfiguracion::class)->contabilizarRecibo();
            foreach ($arr AS $codigo) {
                $arAnticipo = $em->getRepository(CarAnticipo::class)->registroContabilizar($codigo);
                if ($arAnticipo) {
                    if ($arAnticipo['estadoAprobado'] == 1 && $arAnticipo['estadoContabilizado'] == 0) {
                        if ($arAnticipo['codigoComprobanteFk']) {
                            $arComprobante = $em->getRepository(FinComprobante::class)->find($arAnticipo['codigoComprobanteFk']);
                            if ($arComprobante) {
                                $arTercero = $em->getRepository(CarCliente::class)->terceroFinanciero($arAnticipo['codigoClienteFk']);
                                /*$arAnticipoDetalles = $em->getRepository(CarAnticipoDetalle::class)->listaContabilizar($codigo);
                                foreach ($arAnticipoDetalles as $arAnticipoDetalle) {
                                    //Cuenta concepto
                                    if ($arAnticipoDetalle['vrPago'] > 0) {
                                        $descripcion = "ANTICIPO";
                                        $cuenta = $arAnticipoDetalle['codigoCuentaFk'];
                                        if ($cuenta) {
                                            $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                            if (!$arCuenta) {
                                                $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                                break;
                                            }
                                            $arRegistro = new FinRegistro();
                                            $arRegistro->setTerceroRel($arTercero);
                                            $arRegistro->setCuentaRel($arCuenta);
                                            $arRegistro->setComprobanteRel($arComprobante);
                                            $arRegistro->setNumero($arAnticipo['numero']);
                                            $arRegistro->setNumeroPrefijo($arAnticipo['prefijo']);
                                            $arRegistro->setFecha($arAnticipo['fecha']);
                                            $arRegistro->setVrCredito($arAnticipoDetalle['vrPago']);
                                            $arRegistro->setNaturaleza('C');
                                            $arRegistro->setDescripcion($descripcion);
                                            $arRegistro->setCodigoModeloFk('CarAnticipo');
                                            $arRegistro->setCodigoDocumento($arAnticipo['codigoAnticipoPk']);
                                            $em->persist($arRegistro);
                                        } else {
                                            $error = "El concepto no tiene configurada la cuenta " . $descripcion;
                                            break;
                                        }
                                    }
                                }*/

                                //Cuenta anticipo
                                $descripcion = "APLICACION ANTICIPO";
                                $cuenta = $arAnticipo['codigoCuentaAplicacionFk'];
                                if ($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arAnticipo['numero']);
                                    $arRegistro->setNumeroPrefijo($arAnticipo['prefijo']);
                                    $arRegistro->setFecha($arAnticipo['fecha']);
                                    $arRegistro->setVrCredito($arAnticipo['vrPago']);
                                    $arRegistro->setNaturaleza('C');
                                    $arRegistro->setDescripcion($descripcion);
                                    $arRegistro->setCodigoModeloFk('CarAnticipo');
                                    $arRegistro->setCodigoDocumento($arAnticipo['codigoAnticipoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo de cuenta por cobrar del tipo de anticipo no tiene configurada la cuenta de aplicacion ";
                                    break;
                                }

                                //Cuenta banco
                                $descripcion = "BANCO/CAJA";
                                $cuenta = $arAnticipo['codigoCuentaContableFk'];
                                if ($cuenta) {
                                    $arCuenta = $em->getRepository(FinCuenta::class)->find($cuenta);
                                    if (!$arCuenta) {
                                        $error = "No se encuentra la cuenta  " . $descripcion . " " . $cuenta;
                                        break;
                                    }
                                    $arRegistro = new FinRegistro();
                                    $arRegistro->setTerceroRel($arTercero);
                                    $arRegistro->setCuentaRel($arCuenta);
                                    $arRegistro->setComprobanteRel($arComprobante);
                                    $arRegistro->setNumero($arAnticipo['numero']);
                                    $arRegistro->setNumeroPrefijo($arAnticipo['prefijo']);
                                    $arRegistro->setFecha($arAnticipo['fecha']);
                                    $arRegistro->setVrDebito($arAnticipo['vrPago']);
                                    $arRegistro->setNaturaleza('D');
                                    $arRegistro->setDescripcion($descripcion);
                                    $arRegistro->setCodigoModeloFk('CarAnticipo');
                                    $arRegistro->setCodigoDocumento($arAnticipo['codigoAnticipoPk']);
                                    $em->persist($arRegistro);
                                } else {
                                    $error = "El tipo no tiene configurada la cuenta contable para la cuenta bancaria en el anticipo " . $arAnticipo['numero'];
                                    break;
                                }
                                $arAnticipoAct = $em->getRepository(CarAnticipo::class)->find($arAnticipo['codigoAnticipoPk']);
                                $arAnticipoAct->setEstadoContabilizado(1);
                                $em->persist($arAnticipoAct);
                            } else {
                                $error = "No existe el comprobante en el [tipo anticipo] del anticipo " . $arAnticipo['numero'];
                                break;
                            }
                        } else {
                            $error = "No esta configurado el comprobante en el [tipo anticipo] del anticipo " . $arAnticipo['numero'];
                            break;
                        }

                    }
                } else {
                    $error = "La anticipo codigo " . $codigo . " no existe";
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

}