<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamenEntidad;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuExamenEntidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuExamenEntidad::class);
    }

    /**
     * @param $arEntidadExamenes
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($arEntidadExamenes)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuExamenListaPrecio::class, 'ep')
            ->select("COUNT(ep.codigoExamenListaPrecioPk)")
            ->where("ep.codigoEntidadExamenFk = {$arEntidadExamenes} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEntidadExamen = null;
        $nombre = null;

        if ($filtros) {
            $codigoEntidadExamen = $filtros['codigoEntidadExamen'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuExamenEntidad::class, 'e')
            ->select('e.codigoExamenEntidadPk')
            ->addSelect('e.nombre')
            ->addSelect('e.nit')
            ->addSelect('e.direccion')
            ->addSelect('e.telefono');

        if ($codigoEntidadExamen) {
            $queryBuilder->andWhere("e.codigoExamenEntidadPk = '{$codigoEntidadExamen}'");
        }

        if ($nombre) {
            $queryBuilder->andWhere("e.nombre LIKE '%{$nombre}%'");
        }
        $queryBuilder->addOrderBy('e.codigoExamenEntidadPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(RhuExamenEntidad::class)->find($codigo);
                if ($arRegistro) {
                    $em->remove($arRegistro);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $e) {
                Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }
}