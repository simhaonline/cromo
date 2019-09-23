<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Compra\ComCuentaPagarTipo;
use App\Entity\Compra\ComProveedor;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConsecutivo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuCierre;
use App\Entity\RecursoHumano\RhuCierreDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PhpParser\Node\Expr\New_;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class RhuCierreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuCierre::class);
    }

    public function lista($raw)
    {

        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCierre::class, 'c')
            ->select('c.codigoCierrePk')
            ->addSelect('c.anio')
            ->addSelect('c.mes')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado');
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
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrSeleccionados array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if (is_array($arrSeleccionados) && count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuCierre::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    /**
     * @param $arCierre RhuCierre
     * @param $usuario Usuario
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arCierre, $usuario)
    {
        $em = $this->getEntityManager();
        if (!$arCierre->getEstadoAutorizado()) {
            $em->getRepository(RhuCosto::class)->generar($arCierre);
            $arCierre->setEstadoAutorizado(1);
            $em->persist($arCierre);
            $em->flush();
        }
    }


    /**
     * @param $arCierre RhuCierre
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arCierre)
    {
        /**
         * @var $arPago
         */
        $em = $this->getEntityManager();
        if ($arCierre->getEstadoAutorizado() == 1 && $arCierre->getEstadoAprobado() == 0) {
            $arCierre->setEstadoAprobado(1);
            $em->persist($arCierre);
            $arConsecutivo = $em->getRepository(RhuConsecutivo::class)->find(1);
            $arPagos = $em->getRepository(RhuPago::class)->findBy(array('codigoCierreFk' => $arCierre->getCodigoCierrePk()));
            foreach ($arPagos as $arPago) {
                $arPago->setNumero($arConsecutivo->getConsecutivo());
                $arPago->setEstadoAutorizado(1);
                $arPago->setEstadoAprobado(1);
                $em->persist($arPago);
                $arConsecutivo->setConsecutivo($arConsecutivo->getConsecutivo() + 1);
            }
            $em->persist($arConsecutivo);

            //Procesar creditos
            $arPagoDetalleCreditos = $em->getRepository(RhuPagoDetalle::class)->creditos($arCierre->getCodigoCierrePk());
            foreach ($arPagoDetalleCreditos as $arPagoDetalleCredito) {
                $arPagoDetalle = $em->getRepository(RhuPagoDetalle::class)->find($arPagoDetalleCredito['codigoPagoDetallePk']);
                /** @var  $arCredito RhuCredito */
                $arCredito = $arPagoDetalle->getCreditoRel();
                //Crear credito pago, se guarda el pago en la tabla rhu_pago_credito
                $arPagoCredito = new RhuCreditoPago();
                $arPagoCredito->setCreditoRel($arCredito);
                $arPagoCredito->setPagoDetalleRel($arPagoDetalle);
                $arPagoCredito->setfechaPago(new \ DateTime("now"));
                $arPagoCredito->setCreditoPagoTipoRel($arCredito->getCreditoPagoTipoRel());
                $arPagoCredito->setVrPago($arPagoDetalle->getVrPago());

                //Actualizar el saldo del credito
                $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
                $arCredito->setVrSaldo($arCredito->getVrSaldo() - $arPagoDetalleCredito['vrPago']);
                $arCredito->setVrAbonos($arCredito->getVrAbonos() + $arPagoDetalleCredito['vrPago']);
                if ($arCredito->getVrSaldo() <= 0) {
                    $arCredito->setEstadoPagado(1);
                }
                $arPagoCredito->setVrSaldo($arCredito->getVrSaldo());
                $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
                $em->persist($arPagoCredito);
                $em->persist($arCredito);
            }

            //Verificar tercero en cuenta por pagar
            if ($arPago->getPagoTipoRel()->getGeneraTesoreria()) {
                foreach ($arPagos as $arPago) {
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arPago->getCodigoEmpleadoFk());
                    $arTerceroCuentaPagar = $em->getRepository(TesTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arPago->getEmpleadoRel()->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arPago->getEmpleadoRel()->getNumeroIdentificacion()));
                    if ($arTerceroCuentaPagar) {
                        $bancoActual = $arTerceroCuentaPagar->getCodigoBancoFk();
                        $cuentaActual = $arTerceroCuentaPagar->getCuenta();
                        if ($bancoActual != $arPago->getEmpleadoRel()->getCodigoBancoFk()) {
                            $arTerceroCuentaPagar->setBancoRel($arEmpleado->getBancoRel());
                        }
                        if ($cuentaActual != $arEmpleado->getCuenta()) {
                            $arTerceroCuentaPagar->setCuenta($arEmpleado->getCuenta());
                        }
                    }
                    if (!$arTerceroCuentaPagar) {
                        $arTerceroCuentaPagar = new TesTercero();
                        $arTerceroCuentaPagar->setIdentificacionRel($arEmpleado->getIdentificacionRel());
                        $arTerceroCuentaPagar->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arTerceroCuentaPagar->setNombre1($arEmpleado->getNombre1());
                        $arTerceroCuentaPagar->setNombre2($arEmpleado->getNombre2());
                        $arTerceroCuentaPagar->setApellido1($arEmpleado->getApellido1());
                        $arTerceroCuentaPagar->setApellido2($arEmpleado->getApellido2());
                        $arTerceroCuentaPagar->setNombreCorto($arEmpleado->getNombreCorto());
                        $arTerceroCuentaPagar->setCiudadRel($arEmpleado->getCiudadRel());
                        $arTerceroCuentaPagar->setCelular($arEmpleado->getCelular());
                        $arTerceroCuentaPagar->setBancoRel($arEmpleado->getBancoRel());
                        $arTerceroCuentaPagar->setCuenta($arEmpleado->getCuenta());
                        $arTerceroCuentaPagar->setCodigoCuentaTipoFk($arEmpleado->getCodigoCuentaTipoFk());
                    }
                    $em->persist($arTerceroCuentaPagar);

                    $arCuentaPagarTipo = $em->getRepository(TesCuentaPagarTipo::class)->find($arPago->getPagoTipoRel()->getCodigoCuentaPagarTipoFk());
                    $arCuentaPagar = New TesCuentaPagar();
                    $arCuentaPagar->setCuentaPagarTipoRel($arCuentaPagarTipo);
                    $arCuentaPagar->setTerceroRel($arTerceroCuentaPagar);
                    $arCuentaPagar->setBancoRel($arEmpleado->getBancoRel());
                    $arCuentaPagar->setCuenta($arEmpleado->getCuenta());
                    $arCuentaPagar->setNumeroDocumento($arPago->getNumero());
                    $arCuentaPagar->setNumeroReferencia($arPago->getCodigoCierreFk());
                    $arCuentaPagar->setFecha($arPago->getFechaDesde());
                    $arCuentaPagar->setFechaVence($arPago->getFechaDesde());
                    $arCuentaPagar->setVrSubtotal($arPago->getVrNeto());
                    $arCuentaPagar->setVrTotal($arPago->getVrNeto());
                    $arCuentaPagar->setVrSaldo($arPago->getVrNeto());
                    $arCuentaPagar->setVrSaldoOperado($arPago->getVrNeto());
                    $arCuentaPagar->setVrSaldoOriginal($arPago->getVrNeto());
                    $arCuentaPagar->setEstadoAutorizado(1);
                    $arCuentaPagar->setEstadoAprobado(1);
                    $arCuentaPagar->setOperacion(1);
                    $em->persist($arCuentaPagar);
                }
            }
            $em->flush();


        } else {
            Mensajes::error('El documento debe estar autorizado y no puede estar previamente aprobado');
        }
    }

    /**
     * @param $arCierre RhuCierre
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCierre)
    {
        $em = $this->getEntityManager();
        if ($arCierre->getEstadoAutorizado()) {
            $em->createQueryBuilder()->delete(RhuCosto::class, 'c')
                ->where("c.codigoCierreFk = ({$arCierre->getCodigoCierrePk()})")->getQuery()->execute();
            $arCierre->setEstadoAutorizado(0);
            $em->persist($arCierre);
            $em->flush();
        }
    }

    /**
     * @param $arCierre RhuCierre
     * @throws \Doctrine\ORM\ORMException
     */
    public function liquidar($arCierre)
    {
        $em = $this->getEntityManager();
        set_time_limit(0);
        $numeroPagos = 0;
        $douNetoTotal = 0;
//        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        $arPagos = $em->getRepository(RhuPago::class)->findBy(['codigoCierreFk' => $arCierre->getCodigoCierrePk()]);
        foreach ($arPagos as $arPago) {
            $vrNeto = $em->getRepository(RhuPago::class)->liquidar($arPago);
            $arCierreDetalle = $em->getRepository(RhuCierreDetalle::class)->find($arPago->getCodigoCierreDetalleFk());
            $arCierreDetalle->setVrNeto($vrNeto);
            $em->persist($arCierreDetalle);
            $douNetoTotal += $vrNeto;
            $numeroPagos++;
        }
        $arCierre->setVrNeto($douNetoTotal);
        $arCierre->setCantidad($numeroPagos);
        $em->persist($arCierre);
        $em->flush();
    }

}

