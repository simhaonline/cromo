<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCargoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCargo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoCargo = null;
        $nombre = null;

        if ($filtros) {
            $codigoCargo = $filtros['codigoCargo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCargo::class, 'c')
            ->select('c.codigoCargoPk')
            ->addSelect('c.nombre')
            ->addSelect('ABS(c.nombre) AS HIDDEN nombre')
            ->orderBy('c.codigoCargoPk');
        if ($codigoCargo) {
            $queryBuilder->andWhere("c.codigoCargoPk = '{$codigoCargo}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("c.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('c.codigoCargoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuCargo::class)->find($codigo);
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

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuCargo','c')
            ->select('c.codigoCargoPk AS ID')
            ->addSelect('c.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}