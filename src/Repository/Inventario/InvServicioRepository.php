<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvServicio;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InvServicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvServicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvServicio[]    findAll()
 * @method InvServicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvServicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvServicio::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoServicio = null;
        $servicioTipo = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoServicio = $filtros['codigoServicio'] ?? null;
            $servicioTipo = $filtros['servicioTipo'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvServicio::class, 's')
            ->select('s.codigoServicioPk')
            ->addSelect('s.fecha')
            ->addSelect('s.comentario')
            ->addSelect('s.estadoAutorizado')
            ->addSelect('s.estadoAprobado')
            ->addSelect('s.estadoAnulado')
            ->addSelect('st.nombre as servicioTipo')
            ->leftJoin('s.servicioTipoRel', 'st');
        if ($codigoServicio) {
            $queryBuilder->andWhere("s.codigoServicioPk = '{$codigoServicio}'");
        }

        if ($servicioTipo) {
            $queryBuilder->andWhere("s.codigoServicioTipoFk = '{$servicioTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("s.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("s.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('s.codigoServicioPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }
    
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvServicio::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() != 0) {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                    if ($respuesta != '') {
                        Mensajes::error($respuesta);
                    } else {
                        $this->getEntityManager()->remove($arRegistro);
                        $this->getEntityManager()->flush();
                    }
                }
            }
        }
    }

}
