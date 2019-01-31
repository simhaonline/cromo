<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinAsientoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FinAsientoDetalleRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FinAsientoDetalle::class);
    }

    public function asiento($codigo)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
            ->select('ad.codigoAsientoDetallePk')
            ->addSelect('ad.codigoCuentaFk')
            ->addSelect('c.exigeTercero')
            ->addSelect('ad.codigoCentroCostoFk')
            ->addSelect('ad.codigoTerceroFk')
            ->addSelect('ad.vrDebito')
            ->addSelect('ad.vrCredito')
            ->addSelect('ad.vrBase')
            ->addSelect('c.nombre as cuentaNombre')
            ->addSelect('t.nombreCorto as terceroNombre')
            ->leftJoin('ad.cuentaRel', 'c')
            ->leftJoin('ad.terceroRel', 't')
            ->where('ad.codigoAsientoFk = ' . $codigo);
        return $queryBuilder->getQuery();
    }

    /**
     * @param $arPedido InvPedido
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arAsiento, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if (!$arAsiento->getEstadoAutorizado()) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(FinAsientoDetalle::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro porque se encuentra en uso');
                }
            }
        } else {
            Mensajes::error('No se puede eliminar, el registro se encuentra autorizado');
        }
    }

    /**
     * @param $codigoAsiento
     * @return mixed
     */
    public function formatoAsiento($codigoAsiento)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinAsientoDetalle::class, 'ad')
            ->select('ad.codigoAsientoDetallePk')
            ->addSelect('ad.codigoCuentaFk')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.nombreCorto')
            ->addSelect('ad.vrDebito')
            ->addSelect('ad.vrCredito')
            ->addSelect('ad.vrBase')
            ->join('ad.terceroRel', 't')
            ->where('ad.codigoAsientoFk = ' . $codigoAsiento);

        return $queryBuilder->getQuery()->getResult();
    }

}