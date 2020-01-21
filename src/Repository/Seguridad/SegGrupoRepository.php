<?php


namespace App\Repository\Seguridad;


use App\Entity\Seguridad\SegGrupo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class SegGrupoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SegGrupo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoGrupo = null;
        $nombre = null;


        if ($filtros) {
            $codigoGrupo = $filtros['codigoGrupo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(SegGrupo::class, 'g')
            ->select('g.codigoGrupoPk')
            ->addSelect('g.nombre');

        if ($codigoGrupo) {
            $queryBuilder->andWhere("g.codigoGrupoPk = '{$codigoGrupo}'");
        }
        if($nombre){
            $queryBuilder->andWhere("g.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('g.codigoGrupoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(SegGrupo::class)->find($codigo);
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