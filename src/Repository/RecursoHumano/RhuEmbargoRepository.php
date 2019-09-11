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

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuEmbargo::class, 'e')
            ->select('e.codigoEmbargoPk')
            ->addSelect('et.nombre as embargoTipo')
            ->addSelect('e.numero')
            ->addSelect('em.numeroIdentificacion')
            ->addSelect('em.nombreCorto')
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

        if ($session->get('RhuEmbargo_codigoEmbargoPk')) {
            $queryBuilder->andWhere("e.codigoEmbargoPk = '{$session->get('RhuEmbargo_codigoEmbargoPk')}'");
        }

        if ($session->get('RhuEmbargo_codigoEmbargoTipoFk')) {
            $queryBuilder->andWhere("e.codigoEmbargoTipoFk = '{$session->get('RhuEmbargo_codigoEmbargoTipoFk')}'");
        }

        if ($session->get('RhuEmbargo_numero')) {
            $queryBuilder->andWhere("e.numero = '{$session->get('RhuEmbargo_numero')}'");
        }

        if ($session->get('RhuEmbargo_codigoEmpleadoFk')) {
            $queryBuilder->andWhere("e.codigoEmpleadoFk = '{$session->get('RhuEmbargo_codigoEmpleadoFk')}'");
        }

        if ($session->get('RhuEmbargo_fechaDesde') != null) {
            $queryBuilder->andWhere("e.fechaDesde >= '{$session->get('RhuEmbargo_fechaDesde')} 00:00:00'");
        }

        if ($session->get('RhuEmbargo_fechaHasta') != null) {
            $queryBuilder->andWhere("e.fechaHasta <= '{$session->get('RhuEmbargo_fechaHasta')} 23:59:59'");
        }

        switch ($session->get('RhuEmbargo_estadoActivo')) {
            case '0':
                $queryBuilder->andWhere("e.estadoActivo = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoActivo = 1");
                break;
        }

        return $queryBuilder;
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