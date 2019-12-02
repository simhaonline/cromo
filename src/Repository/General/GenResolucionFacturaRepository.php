<?php

namespace App\Repository\General;

use App\Entity\General\GenAsesor;
use App\Entity\General\GenResolucionFactura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenResolucionFacturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenResolucionFactura::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoResolucionFactura = null;

        if ($filtros) {
            $codigoResolucionFactura = $filtros['codigoResolucionFactura'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenResolucionFactura::class, 'rf')
            ->select('rf.codigoResolucionFacturaPk')
            ->addSelect('rf.numero')
            ->addselect('rf.fechaDesde')
            ->addselect('rf.fechaHasta')
            ->addselect('rf.fechaHasta')
            ->addselect('rf.prefijo')
            ->addselect('rf.numeroDesde')
            ->addselect('rf.numeroHasta');
        if ($codigoResolucionFactura) {
            $queryBuilder->andWhere("rf.codigoResolucionFacturaPk = '{$codigoResolucionFactura}'");
        }
        $queryBuilder->addOrderBy('rf.codigoResolucionFacturaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(GenResolucionFactura::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }
}