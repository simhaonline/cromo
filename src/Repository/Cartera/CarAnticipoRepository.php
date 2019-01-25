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
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarAnticipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarAnticipo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAnticipo::class, 'a')
            ->select('a.codigoAnticipoPk')
//            ->leftJoin('r.reciboTipoRel', 'rt')
//            ->addSelect('c.nombre')
//            ->addSelect('cr.nombreCorto')
//            ->addSelect('cr.numeroIdentificacion')
//            ->addSelect('r.numero')
//            ->addSelect('rt.nombre AS tipo')
//            ->addSelect('r.fecha')
//            ->addSelect('r.fechaPago')
//            ->addSelect('r.codigoCuentaFk')
//            ->addSelect('r.vrPagoTotal')
//            ->addSelect('r.usuario')
//            ->addSelect('r.estadoAutorizado')
//            ->addSelect('r.estadoAnulado')
//            ->addSelect('r.estadoImpreso')
//            ->addSelect('r.estadoAprobado')
//            ->leftJoin('r.clienteRel','cr')
//            ->leftJoin('r.cuentaRel','c')
//            ->where('r.codigoReciboPk <> 0')
//            ->orderBy('r.estadoAprobado', 'ASC')
            ->addOrderBy('a.fecha', 'DESC');
//        $fecha =  new \DateTime('now');
//        if($session->get('filtroFecha') == true){
//            if ($session->get('filtroFechaDesde') != null) {
//                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
//            } else {
//                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
//            }
//            if ($session->get('filtroFechaHasta') != null) {
//                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
//            } else {
//                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
//            }
//        }
//        if ($session->get('filtroCarReciboNumero')) {
//            $queryBuilder->andWhere("r.numero = '{$session->get('filtroCarReciboNumero')}'");
//        }
//        if($session->get('filtroCarCodigoCliente')){
//            $queryBuilder->andWhere("r.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
//        }
//        if ($session->get('filtroCarReciboTipo')) {
//            $queryBuilder->andWhere("r.codigoReciboTipoFk = '" . $session->get('filtroCarReciboTipo') . "'");
//        }
//        switch ($session->get('filtroCarReciboEstadoAprobado')) {
//            case '0':
//                $queryBuilder->andWhere("r.estadoAprobado = 0");
//                break;
//            case '1':
//                $queryBuilder->andWhere("r.estadoAprobado = 1");
//                break;
//        }
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
            ->leftJoin('a.anticipoTipoRel', 'at')
            ->leftJoin('a.cuentaRel', 'c')
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
                                $arAnticipoDetalles = $em->getRepository(CarAnticipoDetalle::class)->listaContabilizar($codigo);
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