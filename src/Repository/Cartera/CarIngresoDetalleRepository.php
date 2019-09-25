<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Financiero\FinCuenta;
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
            ->addSelect('cc.codigoCuentaCobrarTipoFk')
            ->addSelect('c.nombreCorto as clienteNombreCorto')
            ->addSelect('c.numeroIdentificacion as clienteNumeroIdentifiacion')
            ->addSelect('cc.vrSaldo')
            ->addSelect('id.vrPago')
            ->addSelect('id.codigoCuentaFk')
            ->addSelect('id.codigoClienteFk')
            ->addSelect('id.naturaleza')
            ->leftJoin('id.cuentaCobrarRel', 'cc')
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
        $arIngresosDetalle = $em->getRepository(CarIngresoDetalle::class)->findBy(['codigoIngresoFk' => $id]);
        foreach ($arIngresosDetalle as $arIngresoDetalle) {
            $intCodigo = $arIngresoDetalle->getCodigoIngresoDetallePk();
            $valorPago = isset($arrControles['TxtVrPago' . $intCodigo]) && $arrControles['TxtVrPago' . $intCodigo] != '' ? $arrControles['TxtVrPago' . $intCodigo] : 0;
            $codigoNaturaleza = isset($arrControles['cboNaturaleza' . $intCodigo]) && $arrControles['cboNaturaleza' . $intCodigo] != '' ? $arrControles['cboNaturaleza' . $intCodigo] : null;
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

            $arIngresoDetalle->setVrPago($valorPago);
            $arIngresoDetalle->setNaturaleza($codigoNaturaleza);
            $em->persist($arIngresoDetalle);
        }
        $em->flush();
    }

}