<?php

namespace App\Repository\Inventario;

use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Inventario\InvImportacion;
use App\Entity\Inventario\InvImportacionCosto;
use App\Entity\Inventario\InvImportacionDetalle;
use App\Entity\Inventario\InvImportacionTipo;
use App\Entity\Inventario\InvTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvImportacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacion::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacion::class, 'i')
            ->select('i.codigoImportacionPk')
            ->addSelect('i.numero')
            ->addSelect('i.fecha')
            ->addSelect('it.nombre')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->addSelect('i.vrSubtotalExtranjero')
            ->addSelect('i.vrIvaExtranjero')
            ->addSelect('i.vrNetoExtranjero')
            ->addSelect('i.vrTotalExtranjero')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->leftJoin('i.terceroRel', 't')
            ->leftJoin('i.importacionTipoRel', 'it')
            ->where('i.codigoImportacionPk <> 0')
            ->orderBy('i.codigoImportacionPk', 'DESC');
        if ($session->get('filtroInvNumeroImportacion')) {
            $queryBuilder->andWhere("i.numero = {$session->get('filtroInvNumeroImportacion')}");
        }
        if ($session->get('filtroInvImportacionTipo')) {
            $queryBuilder->andWhere("i.codigoImportacionTipoFk = '{$session->get('filtroInvImportacionTipo')}'");
        }
        if ($session->get('filtroInvCodigoTercero')) {
            $queryBuilder->andWhere("i.codigoTerceroFk = {$session->get('filtroInvCodigoTercero')}");
        }
        return $queryBuilder;

    }

    /**
     * @param $codigoImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($codigoImportacion)
    {
        $em = $this->getEntityManager();
        $arImportacion = $em->getRepository(InvImportacion::class)->find($codigoImportacion);
        $vrTotalCosto = $em->getRepository(InvImportacionCosto::class)->totalCostos($arImportacion->getCodigoImportacionPk());
        $subtotalGeneralExtranjero = 0;
        $subtotalGeneralExtranjeroTemporal = 0;
        $ivaGeneralExtranjero = 0;
        $totalGeneralExtranjero = 0;
        $subtotalGeneralLocal = 0;
        $ivaGeneralLocal = 0;
        $totalGeneralLocal = 0;
        $arImportacionDetalles = $em->getRepository(InvImportacionDetalle::class)->findBy(['codigoImportacionFk' => $codigoImportacion]);
        foreach ($arImportacionDetalles as $arImportacionDetalle) {
            $subtotalGeneralExtranjeroTemporal += $arImportacionDetalle->getCantidad() * $arImportacionDetalle->getVrPrecioExtranjero();
        }
        foreach ($arImportacionDetalles as $arImportacionDetalle) {
            $subtotalExtranjero = $arImportacionDetalle->getCantidad() * $arImportacionDetalle->getVrPrecioExtranjero();
            $porcentajeIvaExtranjero = $arImportacionDetalle->getPorcentajeIvaExtranjero();
            $ivaExtranjero = $subtotalExtranjero * $porcentajeIvaExtranjero / 100;
            $subtotalGeneralExtranjero += $subtotalExtranjero;
            $ivaGeneralExtranjero += $ivaExtranjero;
            $totalExtranjero = $subtotalExtranjero + $ivaExtranjero;
            $totalGeneralExtranjero += $totalExtranjero;
            $arImportacionDetalle->setVrSubtotalExtranjero($subtotalExtranjero);
            $arImportacionDetalle->setVrIvaExtranjero($ivaExtranjero);
            $arImportacionDetalle->setVrTotalExtranjero($totalExtranjero);

            $precioLocal = $arImportacionDetalle->getVrPrecioExtranjero() * $arImportacion->getTasaRepresentativaMercado();
            $porcentajeParticipaCosto = 0;
            $costoParticipa = 0;
            if($vrTotalCosto > 0) {
                if($subtotalGeneralExtranjeroTemporal > 0) {
                    $porcentajeParticipaCosto = ($arImportacionDetalle->getVrSubtotalExtranjero() / $subtotalGeneralExtranjeroTemporal) * 100;
                    $costoParticipa = (($vrTotalCosto * $porcentajeParticipaCosto) / 100) / $arImportacionDetalle->getCantidad();
                }

            }
            $precioLocalTotal = $arImportacionDetalle->getVrPrecioLocal() + $costoParticipa;
            $subtotalLocal = $arImportacionDetalle->getCantidad() * $precioLocalTotal;
            $porcentajeIvaLocal = $arImportacionDetalle->getPorcentajeIvaLocal();
            $ivaLocal = $subtotalExtranjero * $porcentajeIvaLocal / 100;
            $subtotalGeneralLocal += $subtotalLocal;
            $ivaGeneralLocal += $ivaLocal;
            $totalLocal = $subtotalLocal + $ivaLocal;
            $totalGeneralLocal += $totalLocal;
            $arImportacionDetalle->setVrPrecioLocal($precioLocal);
            $arImportacionDetalle->setVrPrecioLocalTotal($precioLocalTotal);
            $arImportacionDetalle->setVrSubtotalLocal($subtotalLocal);
            $arImportacionDetalle->setVrIvaLocal($ivaLocal);
            $arImportacionDetalle->setVrTotalLocal($totalLocal);
            $arImportacionDetalle->setPorcentajeParticipaCosto($porcentajeParticipaCosto);
            $arImportacionDetalle->setVrCostoParticipa($costoParticipa);
            $em->persist($arImportacionDetalle);
        }

        $arImportacion->setVrTotalCosto($vrTotalCosto);
        $arImportacion->setVrSubtotalExtranjero($subtotalGeneralExtranjero);
        $arImportacion->setVrIvaExtranjero($ivaGeneralExtranjero);
        $arImportacion->setVrTotalExtranjero($totalGeneralExtranjero);

        $arImportacion->setVrSubtotalLocal($subtotalGeneralLocal);
        $arImportacion->setVrIvaLocal($ivaGeneralLocal);
        $arImportacion->setVrTotalLocal($totalGeneralLocal);

        $em->persist($arImportacion);
        $em->flush();
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($this->contarDetalles($arImportacion->getCodigoImportacionPk()) > 0) {
            if (!$arImportacion->getEstadoAutorizado()) {
                $arImportacion->setEstadoAutorizado(1);
                $em->persist($arImportacion);
                $em->flush();
            }
        } else {
            Mensajes::error('El movimiento no tiene detalles');
        }
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($arImportacion->getEstadoAutorizado() && !$arImportacion->getEstadoAnulado()) {
            if($arImportacion->getNumero() == 0 || $arImportacion->getNumero() == "") {
                $arImportacionTipo = $this->getEntityManager()->getRepository(InvImportacionTipo::class)->find($arImportacion->getCodigoImportacionTipoFk());
                if($arImportacionTipo){
                    $arImportacionTipo->setConsecutivo($arImportacionTipo->getConsecutivo() + 1);
                    $arImportacion->setNumero($arImportacionTipo->getConsecutivo());
                    $em->persist($arImportacionTipo);
                }
            }
            $arImportacion->setEstadoAprobado(1);
            $em->persist($arImportacion);
            $em->flush();
        }
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function anular($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($arImportacion->getEstadoAprobado()) {
            $arImportacion->setEstadoAnulado(1);
            $em->persist($arImportacion);
            $em->flush();
        }
    }

    /**
     * @param $arImportacion InvImportacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arImportacion)
    {
        $em = $this->getEntityManager();
        if ($arImportacion->getEstadoAutorizado()) {
            $arImportacion->setEstadoAutorizado(0);
            $em->persist($arImportacion);
            $em->flush();
        }
    }

    public function eliminar($arrSeleccionados)
    {
        /**
         * @var $arImportacion InvImportacion
         */
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvImportacion::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(InvImportacionDetalle::class)->findBy(['codigoImportacionFk' => $arRegistro->getCodigoImportacionPk()])) <= 0) {
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
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    /**
     * @param $id
     * @param $arrControles
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($id, $arrControles)
    {
        if (isset($arrControles['TxtCodigo'])) {
            $em = $this->getEntityManager();
            $arrCantidades = $arrControles['TxtCantidad'];
            $arrPrecios = $arrControles['TxtPrecioExtranjero'];
            if ($arrControles) {
                foreach ($arrControles['TxtCodigo'] as $codigoImportacionDetalle) {
                    $arImportacionDetalle = $em->getRepository(InvImportacionDetalle::class)->find($codigoImportacionDetalle);
                    if ($arImportacionDetalle) {
                        $arImportacionDetalle->setCantidad($arrCantidades[$codigoImportacionDetalle]);
                        $arImportacionDetalle->setCantidadPendiente($arrCantidades[$codigoImportacionDetalle]);
                        $arImportacionDetalle->setVrPrecioExtranjero($arrPrecios[$codigoImportacionDetalle]);
                        $em->persist($arImportacionDetalle);
                    }
                }
                $em->flush();
            }
        }
    }

    /**
     * @param $codigoImportacion integer
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function contarDetalles($codigoImportacion){
        return $this->_em->createQueryBuilder()->from(InvImportacionDetalle::class, 'imd')
            ->select('COUNT(imd.codigoImportacionDetallePk)')
            ->where("imd.codigoImportacionFk = {$codigoImportacion}")->getQuery()->getSingleResult()[1];
    }

    public function listaContabilizar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacion::class, 'i');
        $queryBuilder
            ->select('i.codigoImportacionPk')
            ->addSelect('i.numero')
            ->addSelect('i.soporte')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->addSelect('i.fecha')
            ->addSelect('i.vrSubtotalLocal')
            ->addSelect('i.vrTotalLocal')
            ->addSelect('i.vrSubtotalExtranjero')
            ->addSelect('i.vrTotalExtranjero')
            ->addSelect('i.tasaRepresentativaMercado')
            ->addSelect('i.estadoAnulado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('it.nombre as importacionTipoNombre')
            ->addSelect('m.nombre as monedaNombre')
            ->leftJoin('i.terceroRel', 't')
            ->leftJoin('i.importacionTipoRel', 'it')
            ->leftJoin('i.monedaRel', 'm')
            ->where("i.estadoAprobado = 1 ")
        ->andWhere('i.estadoContabilizado=0');
        if ($session->get('filtroInvImportacionNumero') != "") {
            $queryBuilder->andWhere("i.numero = " . $session->get('filtroInvImportacionNumero'));
        }
        if ($session->get('filtroInvImportacionCodigo') != "") {
            $queryBuilder->andWhere("i.codigoImportacionPk = " . $session->get('filtroInvImportacionCodigo'));
        }
        if($session->get('filtroInvCodigoTercero')){
            $queryBuilder->andWhere("i.codigoTerceroFk = {$session->get('filtroInvCodigoTercero')}");
        }
        switch ($session->get('filtroInvImportacionEstadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("i.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAutorizado = 1");
                break;
        }
        switch ($session->get('filtroInvImportacionEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("i.estadoAprobado= 0");
                break;
            case '1':
                $queryBuilder->andWhere("i.estadoAprobado = 1");
                break;
        }
        if($session->get('filtroGenAsesor')) {
            $queryBuilder->andWhere("i.codigoAsesorFk = '{$session->get('filtroGenAsesor')}'");
        }
        $queryBuilder->orderBy('i.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('i.fecha', 'DESC');
        return $queryBuilder;
    }

    public function registroContabilizar($codigo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacion::class, 'i')
            ->select('i.codigoImportacionPk')
            ->addSelect('i.codigoTerceroFk')
            ->addSelect('i.numero')
            ->addSelect('i.fecha')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoContabilizado')
            ->addSelect('i.vrSubtotalLocal')
            ->addSelect('it.codigoComprobanteFk')
//            ->addSelect('ft.codigoCuentaIngresoTerceroFk')
            ->leftJoin('i.importacionTipoRel', 'it')
            ->where('i.codigoImportacionPk = ' . $codigo);
        $arImportacion = $queryBuilder->getQuery()->getSingleResult();
        return $arImportacion;
    }

    public function contabilizar($arr = null)
    {
        $em = $this->getEntityManager();
        $error = "";
        if ($arr) {
            foreach ($arr AS $codigo) {
                $arImportacion = $em->getRepository(InvImportacion::class)->registroContabilizar($codigo);
                if($arImportacion) {
                    if($arImportacion['estadoAprobado'] == 1 && $arImportacion['estadoContabilizado'] == 0) {
                        $arComprobante = $em->getRepository(FinComprobante::class)->find($arImportacion['codigoComprobanteFk']);
                        $arTercero = $em->getRepository(InvTercero::class)->terceroFinanciero($arImportacion['codigoTerceroFk']);

                        $arrCompras = $em->getRepository(InvImportacionDetalle::class)->cuentaCompra($codigo);
                        foreach ($arrCompras as $arrCompra) {
                            if($arrCompra['codigoCuentaCompraFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCompra['codigoCuentaCompraFk']);
                                if(!$arCuenta) {
                                    $error = "No se encuentra la cuenta " . $arrCompra['codigoCuentaCompraFk'];
                                    break 2;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                /*if($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arImportacion['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }*/
                                //$arRegistro->setNumeroPrefijo($arImportacion['prefijo']);
                                $arRegistro->setNumero($arImportacion['numero']);
                                //$arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                                //$arRegistro->setNumeroReferencia($numeroReferencia);
                                $arRegistro->setFecha($arImportacion['fecha']);
                                $arRegistro->setVrDebito($arrCompra['vrSubtotalLocal']);
                                $arRegistro->setNaturaleza('D');
                                $arRegistro->setDescripcion('COMPRA/IMPORTACION');
                                $arRegistro->setCodigoModeloFk('InvImportacion');
                                $arRegistro->setCodigoDocumento($arImportacion['codigoImportacionPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "No tiene configurada la cuenta de compra para los item de esta importacion";
                                break;
                            }
                        }

                        //Cuenta inventario transito
                        $arrInventariosTransito = $em->getRepository(InvImportacionDetalle::class)->cuentaInventarioTransito($codigo);
                        foreach ($arrInventariosTransito as $arrInventarioTransito) {
                            if($arrInventarioTransito['codigoCuentaInventarioTransitoFk']) {
                                $arCuenta = $em->getRepository(FinCuenta::class)->find($arrInventarioTransito['codigoCuentaInventarioTransitoFk']);
                                if(!$arCuenta) {
                                    $error = "No se encuentra la cuenta " . $arrInventarioTransito['codigoCuentaInventarioTransitoFk'];
                                    break 2;
                                }
                                $arRegistro = new FinRegistro();
                                $arRegistro->setTerceroRel($arTercero);
                                $arRegistro->setCuentaRel($arCuenta);
                                $arRegistro->setComprobanteRel($arComprobante);
                                /*if($arCuenta->getExigeCentroCosto()) {
                                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arImportacion['codigoCentroCostoFk']);
                                    $arRegistro->setCentroCostoRel($arCentroCosto);
                                }*/
                                //$arRegistro->setNumeroPrefijo($arImportacion['prefijo']);
                                $arRegistro->setNumero($arImportacion['numero']);
                                //$arRegistro->setNumeroReferenciaPrefijo($prefijoReferencia);
                                //$arRegistro->setNumeroReferencia($numeroReferencia);
                                $arRegistro->setFecha($arImportacion['fecha']);
                                $arRegistro->setVrCredito($arrInventarioTransito['vrSubtotalLocal']);
                                $arRegistro->setNaturaleza('C');
                                $arRegistro->setDescripcion('INVENTARIO TRANSITO');
                                $arRegistro->setCodigoModeloFk('InvImportacion');
                                $arRegistro->setCodigoDocumento($arImportacion['codigoImportacionPk']);
                                $em->persist($arRegistro);
                            } else {
                                $error = "No tiene configurada la cuenta de inventario en transito para los item de esta importacion";
                                break;
                            }
                        }

                        $arImportacionAct = $em->getRepository(InvImportacion::class)->find($arImportacion['codigoImportacionPk']);
                        $arImportacionAct->setEstadoContabilizado(1);
                        $em->persist($arImportacionAct);
                    }
                } else {
                    $error = "La importacion codigo " . $codigo . " no existe";
                    break;
                }
            }
            if($error == "") {
                $em->flush();
            } else {
                Mensajes::error($error);
            }
        } else {
            $error = "No se seleccionaron registros para contabilizar";
        }
        return $error;
    }    
    
}
