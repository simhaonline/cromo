<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Financiero\FinCuenta;
use App\Entity\General\GenImpuesto;
use App\Entity\Tesoreria\TesEgresoDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CarIngresoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarIngresoDetalle::class);
    }

    public function lista($codigoIngreso)
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(CarIngresoDetalle::class, 'id')
            ->select('id.codigoIngresoDetallePk')
            ->addSelect('id.numero')
            ->addSelect('id.codigoCuentaCobrarFk')
            ->addSelect('id.codigoCuentaCobrarTipoFk')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentifiacion')
            ->addSelect('id.vrPago')
            ->addSelect('id.vrRetencion')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.codigoClienteFk')
            ->addSelect('id.naturaleza')
            ->addSelect('id.codigoImpuestoRetencionFk')
            ->leftJoin('id.clienteRel', 'c')
            ->where("id.codigoIngresoFk = '{$codigoIngreso}'");
        return $queryBuilder;
    }

    public function eliminar($arIngreso, $arrDetallesSeleccionados)
    {
        if ($arIngreso->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(CarIngresoDetalle::class)->find($codigo);
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

    public function actualizar($arrControles, $id)
    {
        $em = $this->getEntityManager();
        $arrCuenta = $arrControles['arrCuenta'];
        $arrCliente = $arrControles['arrCliente'];
        $arrRetencion = $arrControles['cboImpuestoRetencion'];
        $arIngresosDetalle = $em->getRepository(CarIngresoDetalle::class)->findBy(['codigoIngresoFk' => $id]);
        foreach ($arIngresosDetalle as $arIngresoDetalle) {
            $intCodigo = $arIngresoDetalle->getCodigoIngresoDetallePk();
            $valorPago = isset($arrControles['TxtVrPago' . $intCodigo]) && $arrControles['TxtVrPago' . $intCodigo] != '' ? $arrControles['TxtVrPago' . $intCodigo] : 0;
            $codigoNaturaleza = isset($arrControles['cboNaturaleza' . $intCodigo]) && $arrControles['cboNaturaleza' . $intCodigo] != '' ? $arrControles['cboNaturaleza' . $intCodigo] : null;
            $codigoRetencion = $arrRetencion[$intCodigo];
            $retencion = 0;
            if ($arrCuenta[$intCodigo]) {
                $arCuenta = $em->getRepository(FinCuenta::class)->find($arrCuenta[$intCodigo]);
                if ($arCuenta) {
                    $arIngresoDetalle->setCuentaRel($arCuenta);
                } else {
                    $arIngresoDetalle->setCuentaRel(null);
                }
            } else {
                $arIngresoDetalle->setCuentaRel(null);
            }
            if ($arrCliente[$intCodigo]) {
                $arCliente = $em->getRepository(CarCliente::class)->find($arrCliente[$intCodigo]);
                if ($arCliente) {
                    $arIngresoDetalle->setClienteRel($arCliente);
                } else {
                    $arIngresoDetalle->setClienteRel(null);
                }
            } else {
                $arIngresoDetalle->setClienteRel(null);
            }
            if($codigoRetencion) {
                if($codigoRetencion != "R00") {
                    /** @var $arImpuesto GenImpuesto*/
                    $arImpuesto = $em->getRepository(GenImpuesto::class)->find($codigoRetencion);
                    if($valorPago >= $arImpuesto->getBase()) {
                        $retencion = round($valorPago * $arImpuesto->getPorcentaje() / 100);
                    }
                }
            }

            $arIngresoDetalle->setVrPago($valorPago);
            $arIngresoDetalle->setVrRetencion($retencion);
            $arIngresoDetalle->setNaturaleza($codigoNaturaleza);
            $arIngresoDetalle->setCodigoImpuestoRetencionFk($codigoRetencion);
            $em->persist($arIngresoDetalle);
        }
        $em->flush();
    }

    public function duplicar($arIngreso, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arIngreso->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(CarIngresoDetalle::class)->find($codigo);
                        if ($ar) {
                            $arIngresoDetalle = new CarIngresoDetalle();
                            $arIngresoDetalle->setIngresoRel($arIngreso);
                            $arIngresoDetalle->setCuentaCobrarTipoRel($ar->getCuentaCobrarTipoRel());
                            $arIngresoDetalle->setNumero($ar->getNumero());
                            $arIngresoDetalle->setClienteRel($ar->getClienteRel());
                            $arIngresoDetalle->setNaturaleza('C');
                            $arIngresoDetalle->setCodigoImpuestoRetencionFk('R00');
                            $em->persist($arIngresoDetalle);

                        }
                    }
                    try {
                        $em->flush();
                    } catch (\Exception $e) {
                        Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                    }
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    public function listaContabilizar($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarIngresoDetalle::class, 'id');
        $queryBuilder
            ->select('id.codigoIngresoDetallePk')
            ->addSelect('id.vrPago')
            ->addSelect('id.vrRetencion')
            ->addSelect('id.numero')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.naturaleza')
            ->addSelect('id.codigoImpuestoRetencionFk')
            ->where('id.codigoIngresoFk = ' . $id);
        $queryBuilder->orderBy('id.codigoIngresoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function listaFormato($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarIngresoDetalle::class, 'id');
        $queryBuilder
            ->select('id.codigoIngresoDetallePk')
            ->addSelect('id.codigoCuentaCobrarFk')
            ->addSelect('id.codigoCuentaCobrarTipoFk')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.naturaleza')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentificacion')
            ->addSelect('id.vrPago')
            ->addSelect('id.numero')
            ->leftJoin('id.clienteRel', 'c')
            ->where('id.codigoIngresoFk = ' . $id);
        $queryBuilder->orderBy('id.codigoIngresoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

}