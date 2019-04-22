<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEmpleado::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RHuEmpleado', 'e')
            ->select('e.codigoEmpleadoPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class,'e')
            ->select('e.codigoContratoFk')
            ->addSelect('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->where('e.codigoEmpleadoPk <> 0')
            ->andWhere('e.estadoContrato = 1');
        if($session->get('filtroRhuEmpleadoCodigo')){
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroRhuEmpleadoCodigo')}");
        }
        if($session->get('filtroRhuEmpleadoNombre')){
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroRhuEmpleadoNombre')}%'");
        }
        if($session->get('filtroRhuEmpleadoIdentificacion')){
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroRhuEmpleadoIdentificacion')}' ");
        }
        if($session->get('filtroRhuEmpleadoEstadoContrato')){
            $queryBuilder->orWhere("e.estadoContrato = 0");
        }
        return $queryBuilder;
    }

    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoEmpleadoPk')
            ->addSelect('e.codigoIdentificacionFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('ce.nombre AS ciudadExpedicion')
            ->addSelect('e.nombre1')
            ->addSelect('e.nombre2')
            ->addSelect('e.apellido1')
            ->addSelect('e.apellido2')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.telefono')
            ->addSelect('e.correo')
            ->addSelect('e.direccion')
            ->addSelect('e.barrio')
            ->addSelect('e.correo')
            ->addSelect('b.nombre AS banco')
            ->addSelect('e.cuenta')
            ->addSelect('e.codigoRhFk')
            ->addSelect('e.codigoSexoFk')
            ->addSelect('e.codigoEstadoCivilFk')
            ->leftJoin('e.ciudadRel', 'c')
            ->leftJoin('e.ciudadNacimientoRel', 'cn')
            ->leftJoin('e.ciudadExpedicionRel', 'ce')
            ->leftJoin('e.bancoRel','b')
            ->where('e.codigoEmpleadoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }
}