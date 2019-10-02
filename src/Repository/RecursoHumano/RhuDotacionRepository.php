<?php


namespace App\Repository\RecursoHumano;


use App\Entity\RecursoHumano\RhuDotacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuDotacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuDotacion::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoDotacion = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoCerrado = null;
        $estadoSalidaInventario = null;
        $codigoInternoReferencia = null;

        if ($filtros) {
            $codigoDotacion = $filtros['codigoDotacion'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoCerrado = $filtros['estadoCerrado'] ?? null;
            $estadoSalidaInventario = $filtros['estadoSalidaInventario'] ?? null;
            $codigoInternoReferencia = $filtros['codigoInternoReferencia'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuDotacion::class, 'd')
            ->select('d.codigoDotacionPk')
            ->addSelect('d.fecha')
            ->addSelect('d.codigoInternoReferencia')
            ->addSelect('d.estadoAutorizado')
            ->addSelect('d.estadoCerrado')
            ->addSelect('d.estadoSalidaInventario');
        if ($codigoDotacion) {
            $queryBuilder->andWhere("d.codigoDotacionPk = '{$codigoDotacion}'");
        }

        if ($codigoInternoReferencia) {
            $queryBuilder->andWhere("d.codigoInternoReferencia = '{$codigoInternoReferencia}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("d.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("d.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("d.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("d.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("d.estadoAutorizado = 1");
                break;
        }
        switch ($estadoCerrado) {
            case '0':
                $queryBuilder->andWhere("d.estadoCerrado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("d.estadoCerrado = 1");
                break;
        }
        switch ($estadoSalidaInventario) {
            case '0':
                $queryBuilder->andWhere("d.estadoSalidaInventario = 0");
                break;
            case '1':
                $queryBuilder->andWhere("d.estadoSalidaInventario = 1");
                break;
        }
        $queryBuilder->addOrderBy('d.codigoDotacionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        try{
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $arRegistro = $this->getEntityManager()->getRepository(RhuDotacion::class)->find($arrSeleccionado);
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