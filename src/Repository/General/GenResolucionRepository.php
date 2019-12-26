<?php

namespace App\Repository\General;

use App\Entity\General\GenAsesor;
use App\Entity\General\GenResolucion;
use App\Entity\General\GenResolucionFactura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenResolucionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenResolucion::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoResolucion = null;

        if ($filtros) {
            $codigoResolucion = $filtros['codigoResolucion'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenResolucion::class, 'r')
            ->select('r.codigoResolucionPk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaDesde')
            ->addSelect('r.fechaHasta')
            ->addSelect('r.prefijo')
            ->addSelect('r.numeroDesde')
            ->addSelect('r.numeroHasta')
            ->addSelect('r.llaveTecnica')
            ->addSelect('r.ambiente')
            ->addSelect('r.pin');
        if ($codigoResolucion) {
            $queryBuilder->andWhere("r.codigoResolucionPk = '{$codigoResolucion}'");
        }
        $queryBuilder->addOrderBy('r.codigoResolucionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(GenResolucion::class)->find($codigo);
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