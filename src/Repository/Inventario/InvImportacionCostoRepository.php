<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacionCosto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvImportacionCostoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacionCosto::class);
    }

    public function lista($codigoImportacion){
        return $this->_em->createQueryBuilder()->from(InvImportacionCosto::class,'ic')
            ->select('ic.codigoImportacionCostoPk')
            ->addSelect('ic.soporte')
            ->addSelect('ic.codigoImportacionCostoConceptoFk')
            ->addSelect('icc.nombre as importacionCostoConceptoNombre')
            ->addSelect('ic.vrValor')
            ->addSelect('t.nombreCorto as terceroNombreCorto')
            ->leftJoin('ic.importacionCostoConceptoRel','icc')
            ->leftJoin('ic.terceroRel', 't')
            ->where("ic.codigoImportacionFk = {$codigoImportacion}");
    }

    /**
     * @param $codigoImportacion
     * @return mixed
     */
    public function totalCostos($codigoImportacion){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacionCosto::class, 'ic')
            ->select("SUM(ic.vrValor)")
            ->where("ic.codigoImportacionFk = {$codigoImportacion} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function eliminar($arImportacion, $arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if (!$arImportacion->getEstadoAutorizado()) {
            if ($arrDetallesSeleccionados) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(InvImportacionCosto::class)->find($codigo);
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

}
