<?php


namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuDotacionElemento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuDotacionElementoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuDotacionElemento::class);
    }

    public function lista ($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoDotacionElemento = null;
        $nombre = null;

        if ($filtros) {
            $codigoDotacionElemento = $filtros['codigoDotacionElemento'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuDotacionElemento::class, 'e')
            ->select('e.codigoDotacionElementoPk')
            ->addSelect('e.nombre');
        $queryBuilder->orderBy('e.codigoDotacionElementoPk', 'DESC');

        if ($codigoDotacionElemento) {
            $queryBuilder->andWhere("e.codigoDotacionElementoPk = {$codigoDotacionElemento}");
        }
        if ($nombre) {
            $queryBuilder->andWhere("e.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('e.codigoDotacionElementoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuDotacionElemento::class)->find($codigo);
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