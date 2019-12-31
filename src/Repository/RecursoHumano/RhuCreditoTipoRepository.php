<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCreditoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCreditoTipo::class);
    }

    /**
     * @return mixed
     */
    public function parametrosExcel(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargo::class,'re')
            ->select('re.codigoEmbargoPk')
            ->addSelect('re.fecha')
            ->where('re.codigoEmbargoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function camposPredeterminados(){
        return $this->_em->createQueryBuilder()->from(RhuCreditoTipo::class,'ct')
            ->select('ct.codigoCreditoTipoPk AS ID')
            ->addSelect('ct.nombre')
            ->addSelect('ct.cupoMaximo')
            ->leftJoin('ct.conceptoRel','c')
            ->addSelect('c.nombre AS CONCEPTO')
            ->where('ct.codigoCreditoTipoPk IS NOT NULL')->getQuery()->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCreditoTipo = null;
        $nombre = null;
        $codigoConcepto = null;

        if ($filtros) {
            $codigoCreditoTipo = $filtros['codigoCreditoTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $codigoConcepto = $filtros['codigoConcepto'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCreditoTipo::class, 'ct')
            ->select('ct.codigoCreditoTipoPk')
            ->addSelect('ct.nombre')
            ->addSelect('ct.cupoMaximo')
            ->addSelect('ct.nombre as concepto')
            ->leftJoin('ct.conceptoRel', 'c');
        if ($codigoCreditoTipo) {
            $queryBuilder->andWhere("ct.codigoCreditoTipoPk = '{$codigoCreditoTipo}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("ct.nombre LIKE '%{$nombre}%'");
        }
        if ($codigoConcepto) {
            $queryBuilder->andWhere("ct.codigoConceptoFk = '{$codigoConcepto}'");
        }
        $queryBuilder->addOrderBy('ct.codigoCreditoTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuCreditoTipo::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }
}