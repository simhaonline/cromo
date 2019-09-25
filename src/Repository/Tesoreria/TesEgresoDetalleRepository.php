<?php

namespace App\Repository\Tesoreria;


use App\Entity\Tesoreria\TesEgreso;
use App\Entity\Tesoreria\TesEgresoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesEgresoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesEgresoDetalle::class);
    }

    public function lista($codigoEgreso)
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesEgresoDetalle::class, 'ed')
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.numero')
            ->addSelect('ed.codigoCuentaPagarFk')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('cp.vrSaldo')
            ->addSelect('ed.vrPago')
            ->addSelect('cp.cuenta')
            ->addSelect('ed.codigoCuentaFk')
            ->addSelect('ed.codigoTerceroFk')
            ->addSelect('ed.naturaleza')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->leftJoin('ed.terceroRel', 't')
            ->where("ed.codigoEgresoFk = '{$codigoEgreso}'");

        return $queryBuilder;
    }



    /**
     * @param $arEgreso
     * @param $arrDetallesSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arEgreso, $arrDetallesSeleccionados)
    {
        if ($arEgreso->getEstadoAutorizado() == 0) {
            if ($arrDetallesSeleccionados) {
                if (count($arrDetallesSeleccionados)) {
                    foreach ($arrDetallesSeleccionados as $codigo) {
                        $ar = $this->getEntityManager()->getRepository(TesEgresoDetalle::class)->find($codigo);
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


    public function actualizar($arrControles, $idEgreso)
    {
        $em = $this->getEntityManager();
        $arEgresosDetalle = $em->getRepository(TesEgresoDetalle::class)->findBy(['codigoEgresoFk' => $idEgreso]);
        foreach ($arEgresosDetalle as $arEgresoDetalle) {
            $intCodigo = $arEgresoDetalle->getCodigoEgresoDetallePk();
            $valorPago = isset($arrControles['TxtVrPago' . $intCodigo]) && $arrControles['TxtVrPago' . $intCodigo] != '' ? $arrControles['TxtVrPago' . $intCodigo] : 0;
            $codigoNaturaleza = isset($arrControles['cboNaturaleza' . $intCodigo]) && $arrControles['cboNaturaleza' . $intCodigo] != '' ? $arrControles['cboNaturaleza' . $intCodigo] : null;
            $arEgresoDetalle->setVrPago($valorPago);
            $arEgresoDetalle->setNaturaleza($codigoNaturaleza);
            $em->persist($arEgresoDetalle);
        }
        $em->flush();
    }

    public function listaFormato($codigoEgreso)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesEgresoDetalle::class, 'ed');
        $queryBuilder
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.codigoCuentaPagarFk')
            ->addSelect('ed.codigoCuentaFk')
            ->addSelect('ed.naturaleza')
            ->addSelect('ter.nombreCorto AS terceroNombreCorto')
            ->addSelect('ter.numeroIdentificacion as terceroNumeroIdentificacion')
            ->addSelect('ed.vrPago')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->leftJoin('ed.egresoRel', 'r')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->leftJoin('ed.terceroRel', 'ter')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->where('ed.codigoEgresoFk = ' . $codigoEgreso);
        $queryBuilder->orderBy('ed.codigoEgresoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function listaContabilizar($codigoEgreso)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesEgresoDetalle::class, 'ed');
        $queryBuilder
            ->select('ed.codigoEgresoDetallePk')
            ->addSelect('ed.vrPago')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cpt.codigoCuentaProveedorFk')
            ->leftJoin('ed.cuentaPagarRel', 'cp')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->where('ed.codigoEgresoFk = ' . $codigoEgreso);
        $queryBuilder->orderBy('ed.codigoEgresoDetallePk', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

}
