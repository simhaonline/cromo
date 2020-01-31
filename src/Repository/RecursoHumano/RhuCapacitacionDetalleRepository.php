<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuCapacitacion;
use App\Entity\RecursoHumano\RhuCapacitacionDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuCapacitacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCapacitacionDetalle::class);
    }

    public function lista($id)
    {
//        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
//        $filtros = $raw['filtros'] ?? null;
//
//        $codigoSolicitud = null;
//        $nombre = null;
//        $estadoAutorizado = null;
//        $estadoAprobado = null;
//        $estadoAnulado = null;
//
//        if ($filtros) {
//            $codigoSolicitud = $filtros['codigoSolicitud'] ?? null;
//            $nombre = $filtros['nombre'] ?? null;
//            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
//            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
//            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
//        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCapacitacionDetalle::class, 'cd')
            ->select('cd.codigoCapacitacionDetallePk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto AS empleado')
            ->addSelect('g.nombre AS grupo')
            ->addSelect('cd.evaluacion')
            ->addSelect('cd.asistencia')
            ->where("cd.codigoCapacitacionFk = '{$id}'")
            ->leftJoin('cd.empleadoRel', 'e')
            ->leftJoin('e.contratoRel', 'c')
            ->leftJoin('c.grupoRel', 'g');
//        if ($codigoSolicitud) {
//            $queryBuilder->andWhere("s.codigoSolicitudPk = '{$codigoSolicitud}'");
//        }
//        if ($nombre) {
//            $queryBuilder->andWhere("s.nombreCorto LIKE '%{$nombre}%'");
//        }
//        switch ($estadoAutorizado) {
//            case '0':
//                $queryBuilder->andWhere("s.estadoAutorizado = 0");
//                break;
//            case '1':
//                $queryBuilder->andWhere("s.estadoAutorizado = 1");
//                break;
//        }
//        switch ($estadoAprobado) {
//            case '0':
//                $queryBuilder->andWhere("s.estadoAprobado = 0");
//                break;
//            case '1':
//                $queryBuilder->andWhere("s.estadoAprobado = 1");
//                break;
//        }
//        switch ($estadoAnulado) {
//            case '0':
//                $queryBuilder->andWhere("s.estadoAnulado = 0");
//                break;
//            case '1':
//                $queryBuilder->andWhere("s.estadoAnulado = 1");
//                break;
//        }
        $queryBuilder->addOrderBy('cd.codigoCapacitacionDetallePk', 'DESC');
//        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrControles
     * @param $form
     * @param $arCapacitacion
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arCapacitacion)
    {
        $em = $this->getEntityManager();
        if ($this->getEntityManager()->getRepository(RhuCapacitacion::class)->contarDetalles($arCapacitacion->getCodigoCapacitacionPk()) > 0) {
            $arrCodigo = $arrControles['arrCodigo'];
            $arrEvaluacion = $arrControles['arrEvaluacion'];
            foreach ($arrCodigo as $codigoCapacitacionDetalle) {
                $arCapacitacionDetalle = $this->getEntityManager()->getRepository(RhuCapacitacionDetalle::class)->find($codigoCapacitacionDetalle);
                $arCapacitacionDetalle->setEvaluacion($arrEvaluacion[$codigoCapacitacionDetalle]);
                $em->persist($arCapacitacionDetalle);
                $em->flush();
            }
        }
    }

    public function asiste($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigoCapacitacionDetalle) {
                $arCapacitacionDetalle = $em->getRepository(RhuCapacitacionDetalle::class)->find($codigoCapacitacionDetalle);
                if ($arCapacitacionDetalle) {
                    if ($arCapacitacionDetalle->getAsistencia() == 0) {
                        $arCapacitacionDetalle->setAsistencia(1);
                        $em->persist($arCapacitacionDetalle);
                    } else {
                        Mensajes::error("El empleado ya esta marcado que asistió");
                    }
                }
            }
        }
        $em->flush();
    }

    public function noAsiste($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigoCapacitacionDetalle) {
                $arCapacitacionDetalle = $em->getRepository(RhuCapacitacionDetalle::class)->find($codigoCapacitacionDetalle);
                if ($arCapacitacionDetalle) {
                    if ($arCapacitacionDetalle->getAsistencia() == 1) {
                        $arCapacitacionDetalle->setAsistencia(0);
                        $em->persist($arCapacitacionDetalle);
                    } else {
                        Mensajes::error("El empleado ya esta marcado que no asistió");
                    }
                }
            }
        }
        $em->flush();
    }

}