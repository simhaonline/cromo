<?php

namespace App\Repository\Tesoreria;


use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Tesoreria\TesCompra;
use App\Entity\Tesoreria\TesCompraDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesCompraDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCompraDetalle::class);
    }

    public function lista($codigoCompra)
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesCompraDetalle::class, 'cd')
            ->select('cd.codigoCompraDetallePk')
            ->addSelect('cd.numero')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('cd.vrPrecio')
            ->addSelect('cd.codigoCuentaFk')
            ->addSelect('cd.codigoTerceroFk')
            ->addSelect('cd.naturaleza')
            ->addSelect('cd.codigoCentroCostoFk')
            ->leftJoin('cd.terceroRel', 't')
            ->where("cd.codigoCompraFk = '{$codigoCompra}'");
        return $queryBuilder;
    }



    /**
     * @param $arCompra
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arCompra, $arrDetallesSeleccionados)
    {
        if ($arCompra->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TesCompraDetalle::class)->find($codigo);
                        if ($ar) {
                            $this->getEntityManager()->remove($ar);
                        }
                    }
                    try {
                        $this->getEntityManager()->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }


    public function actualizar($arrControles, $idCompra)
    {
        $em = $this->getEntityManager();
        if(isset($arrControles['arrCuenta'])) {
            $arrCuenta = $arrControles['arrCuenta'];
            $arrCentroCosto = $arrControles['arrCentroCosto'];
            $arrTercero = $arrControles['arrTercero'];
            $arrNumero = $arrControles['arrNumero'];
            $arComprasDetalle = $em->getRepository(TesCompraDetalle::class)->findBy(['codigoCompraFk' => $idCompra]);
            foreach ($arComprasDetalle as $arCompraDetalle) {
                $intCodigo = $arCompraDetalle->getCodigoCompraDetallePk();
                $valorPago = isset($arrControles['TxtVrPrecio' . $intCodigo]) && $arrControles['TxtVrPrecio' . $intCodigo] != '' ? $arrControles['TxtVrPrecio' . $intCodigo] : 0;
                $numero = $arrNumero[$intCodigo] && $arrNumero[$intCodigo] != '' ? $arrNumero[$intCodigo] : null;
                $codigoNaturaleza = isset($arrControles['cboNaturaleza' . $intCodigo]) && $arrControles['cboNaturaleza' . $intCodigo] != '' ? $arrControles['cboNaturaleza' . $intCodigo] : null;
                if ($arrCuenta[$intCodigo]) {
                    $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCuenta[$intCodigo]);
                    if ($arCuenta) {
                        $arCompraDetalle->setCuentaRel($arCuenta);
                    } else {
                        $arCompraDetalle->setCuentaRel(null);
                    }
                } else {
                    $arCompraDetalle->setCuentaRel(null);
                }
                if ($arrTercero[$intCodigo]) {
                    $arTercero = $em->getRepository(TesTercero::class)->find($arrTercero[$intCodigo]);
                    if ($arTercero) {
                        $arCompraDetalle->setTerceroRel($arTercero);
                    } else {
                        $arCompraDetalle->setTerceroRel(null);
                    }
                } else {
                    $arCompraDetalle->setTerceroRel(null);
                }
                if ($arrCentroCosto[$intCodigo]) {
                    $arCentroCosto = $em->getRepository(FinCentroCosto::class)->find($arrCentroCosto[$intCodigo]);
                    if ($arCentroCosto) {
                        $arCompraDetalle->setCentroCostoRel($arCentroCosto);
                    } else {
                        $arCompraDetalle->setCentroCostoRel(null);
                    }
                } else {
                    $arCompraDetalle->setCentroCostoRel(null);
                }
                $arCompraDetalle->setVrPrecio($valorPago);
                $arCompraDetalle->setNaturaleza($codigoNaturaleza);
                $arCompraDetalle->setNumero($numero);
                $em->persist($arCompraDetalle);
            }
            $em->flush();
        }
    }

    public function listaFormato($codigoCompra)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCompraDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoCompraDetallePk')
            ->addSelect('cd.codigoCuentaFk')
            ->addSelect('cd.naturaleza')
            ->addSelect('ter.nombreCorto AS terceroNombreCorto')
            ->addSelect('ter.numeroIdentificacion as terceroNumeroIdentificacion')
            ->addSelect('cd.vrPrecio')
            ->addSelect('cd.numero')
            ->addSelect('cd.codigoCentroCostoFk')
            ->leftJoin('cd.compraRel', 'r')
            ->leftJoin('cd.terceroRel', 'ter')
            ->where('cd.codigoCompraFk = ' . $codigoCompra);
        $queryBuilder->orderBy('cd.codigoCompraDetallePk', 'ASC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function listaContabilizar($codigoCompra)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesCompraDetalle::class, 'cd');
        $queryBuilder
            ->select('cd.codigoCompraDetallePk')
            ->addSelect('cd.vrPrecio')
            ->addSelect('cd.numero')
            ->addSelect('cd.codigoCuentaFk')
            ->addSelect('cd.codigoTerceroFk')
            ->addSelect('cd.naturaleza')
            ->where('cd.codigoCompraFk = ' . $codigoCompra);
        $queryBuilder->orderBy('cd.codigoCompraDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

}
