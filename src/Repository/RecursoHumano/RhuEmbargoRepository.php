<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuEmbargoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEmbargo::class);
    }

    /**
     * @param $arrSeleccionados array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if (is_array($arrSeleccionados) && count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuEmbargo::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEmbargo = null;
        $embargoTipo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoActivo = null;

        if ($filtros) {
            $codigoEmbargo = $filtros['codigoEmbargo'] ?? null;
            $embargoTipo = $filtros['embargoTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoActivo = $filtros['estadoActivo'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuEmbargo::class, 'e')
            ->select('e.codigoEmbargoPk')
            ->addSelect('et.nombre as embargoTipo')
            ->addSelect('e.numero')
            ->addSelect('em.numeroIdentificacion')
            ->addSelect('em.nombreCorto AS empleado')
            ->addSelect('e.fecha')
            ->addSelect('e.vrValor')
            ->addSelect('e.porcentajeDevengado')
            ->addSelect('e.porcentajeDevengadoPrestacional')
            ->addSelect('e.porcentajeExcedaSalarioMinimo')
            ->addSelect('e.porcentaje')
            ->addSelect('e.partesExcedaSalarioMinimo')
            ->addSelect('e.partes')
            ->addSelect('e.estadoActivo')
            ->leftJoin('e.embargoTipoRel', 'et')
            ->leftJoin('e.empleadoRel', 'em');
        if ($codigoEmbargo) {
            $queryBuilder->andWhere("e.codigoEmbargoPk = '{$codigoEmbargo}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("e.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($embargoTipo) {
            $queryBuilder->andWhere("e.codigoEmbargoTipoFk = '{$embargoTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("e.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("e.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoActivo) {
            case '0':
                $queryBuilder->andWhere("e.estadoActivo = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoActivo = 1");
                break;
        }
        $queryBuilder->addOrderBy('e.fecha', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();


    }
    
    public function listaEmbargo($codigoEmpleado, $afectaLiquidacion = 0, $afectaVacacion = 0, $afectaPrima = 0, $afectaCesantias = 0)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuEmbargo::class, "em")
            ->select("em")
            ->where("em.codigoEmpleadoFk = {$codigoEmpleado}")
            ->andWhere("em.estadoActivo = 1");
        if ($afectaLiquidacion) {
            $query->andWhere("em.afectaLiquidacion = 1");
        }
        if ($afectaVacacion) {
            $query->andWhere("em.afectaVacacion = 1");
        }
        if ($afectaPrima) {
            $query->andWhere("em.afectaPrima = 1");
        }
        if ($afectaCesantias) {
            $query->andWhere("em.afectaCesantia = 1");
        }

        $arEmbargos = $query->getQuery()->getResult();

        return $arEmbargos;
    }
}