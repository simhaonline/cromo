<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteVehiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteVehiculoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteVehiculo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $placa = null;

        if ($filtros) {
            $placa = $filtros['placa'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteVehiculo::class, 'v')
            ->select('v.codigoVehiculoPk')
            ->addSelect('v.placa')
            ->addSelect('v.modelo')
            ->addSelect('v.placaRemolque')
            ->addSelect('v.motor')
            ->addSelect('v.numeroEjes')
            ->addSelect('v.celular')
            ->addSelect('v.fechaVencePoliza')
            ->addSelect('m.nombre AS marca')
            ->addSelect('pro.nombreCorto AS propietario')
            ->addSelect('pos.nombreCorto AS poseedor')
            ->leftJoin('v.marcaRel', 'm')
            ->leftJoin('v.propietarioRel', 'pro')
            ->leftJoin('v.poseedorRel', 'pos')
            ->where('v.codigoVehiculoPk IS NOT NULL')
            ->orderBy('v.placa', 'ASC');

        if ($placa) {
            $queryBuilder->andWhere("v.placa = '{$placa}'");
        }

        $queryBuilder->addOrderBy('v.codigoVehiculoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function listaDql()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteVehiculo::class, 'v')
            ->select('v.codigoVehiculoPk')
            ->addSelect('v.placa')
            ->where("v.codigoVehiculoPk IS NOT NULL")
            ->orderBy('v.placa');
        if ($session->get('filtroTteVehiculoPlaca') != '') {
            $queryBuilder->andWhere("v.placa LIKE '%{$session->get('filtroTteVehiculoPlaca')}%'");
        }
        return $queryBuilder;
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Transporte\TteVehiculo', 'v')
            ->select('v.codigoVehiculoPk AS ID')
            ->addSelect('v.placa AS PLACA');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function dqlRndc($codigoVehiculo): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        v.codigoVehiculoPk,
        v.configuracion,
        v.numeroEjes,
        v.codigoMarcaFk as codigoMarca,
        l.linea as codigoLinea,
        v.codigoColorFk as codigoColor,
        v.codigoTipoCombustibleFk as tipoCombustible,
        v.codigoTipoCarroceriaFk as tipoCarroceria,
        v.pesoVacio,
        v.modelo,
        pi.codigoInterface as tipoIdentificacionPropietario,
        p.numeroIdentificacion as numeroIdentificacionPropietario,
        psi.codigoInterface as tipoIdentificacionPoseedor,
        ps.numeroIdentificacion as numeroIdentificacionPoseedor, 
        v.numeroPoliza,
        v.fechaVencePoliza,
        a.numeroIdentificacion as numeroIdentificacionAseguradora,        
        v.capacidad       
        FROM App\Entity\Transporte\TteVehiculo v          
        LEFT JOIN v.marcaRel m 
        LEFT JOIN v.lineaRel l
        LEFT JOIN v.propietarioRel p
        LEFT JOIN p.identificacionRel pi
        LEFT JOIN v.poseedorRel ps
        LEFT JOIN ps.identificacionRel psi
        LEFT JOIN v.aseguradoraRel a 
        WHERE v.codigoVehiculoPk = :codigoVehiculo'
        )->setParameter('codigoVehiculo', $codigoVehiculo);
        $arVehiculo = $query->getSingleResult();
        return $arVehiculo;

    }

}