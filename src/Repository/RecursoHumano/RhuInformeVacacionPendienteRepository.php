<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Turno\Informe\Juridico\contratoController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuInformeVacacionPendiente;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuInformeVacacionPendienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuInformeVacacionPendiente::class);
    }

    public function informe($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEmpleado = null;
        $estadoTerminado = null;

        if ($filtros) {
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $estadoTerminado = $filtros['estadoTerminado'] ?? null;
        }

        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuInformeVacacionPendiente::class, 'ivp')
            ->addSelect('ivp.codigoInformeVacacionPendientePk')
            ->addSelect('ivp.codigoContratoFk')
            ->addSelect('ivp.tipoContrato')
            ->addSelect('ivp.fechaIngreso')
            ->addSelect('ivp.numeroIdentificacion')
            ->addSelect('ivp.empleado')
            ->addSelect('ivp.grupo')
            ->addSelect('ivp.zona')
            ->addSelect('ivp.fechaUltimoPago')
            ->addSelect('ivp.fechaUltimoPagoVacaciones')
            ->addSelect('ivp.estadoTerminado')
            ->addSelect('ivp.vrSalario')
            ->addSelect('ivp.dias')
            ->addSelect('ivp.diasAusentismo')
            ->addSelect('ivp.vrVacacion')
            ->addSelect('ivp.vrPromedioRecargoNocturno')
            ->addSelect('ivp.vrSalarioPromedio');

        if ($codigoEmpleado) {
            $queryBuilder->andWhere("ivp.codigoEmpleadoFk = {$codigoEmpleado} ");
        }
        switch ($estadoTerminado) {
            case '0':
                $queryBuilder->andWhere("ivp.estadoTerminado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("ivp.estadoTerminado = 1");
                break;
        }
        return $queryBuilder->getQuery()->getResult();

    }

}