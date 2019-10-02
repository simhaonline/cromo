<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuLiquidacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuLiquidacionTipo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoLiquidacionTipo = null;
        $nombreLiquidacionTipo = null;

        if ($filtros) {
            $codigoLiquidacionTipo = $filtros['codigoLiquidacionTipo'] ?? null;
            $nombreLiquidacionTipo = $filtros['nombreLiquidacionTipo'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuLiquidacionTipo::class, 'vt')
            ->select('vt.codigoLiquidacionTipoPk')
            ->addSelect('vt.nombre')
            ->addSelect('vt.consecutivo')
            ->addSelect('vt.codigoConceptoCesantiaFk')
            ->addSelect('vt.codigoConceptoInteresFk')
            ->addSelect('vt.codigoConceptoCesantiaAnteriorFk')
            ->addSelect('vt.codigoConceptoInteresAnteriorFk')
            ->addSelect('vt.codigoConceptoPrimaFk')
            ->addSelect('vt.codigoConceptoVacacionFk')
            ->addSelect('vt.codigoConceptoIndemnizacionFk')
        ;
        if ($codigoLiquidacionTipo) {
            $queryBuilder->andWhere("vt.codigoLiquidacionTipoPk = '{$codigoLiquidacionTipo}'");
        }
        if ($nombreLiquidacionTipo) {
            $queryBuilder->andWhere("vt.nombre LIKE '%{$nombreLiquidacionTipo}%' ");
        }
        $queryBuilder->addOrderBy('vt.codigoLiquidacionTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        try{
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $arRegistro = $this->getEntityManager()->getRepository(RhuLiquidacionTipo::class)->find($arrSeleccionado);
                if ($arRegistro) {
                    $this->getEntityManager()->remove($arRegistro);
                }
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $ex) {
            Mensajes::error("El registro tiene registros relacionados");
        }
    }
}