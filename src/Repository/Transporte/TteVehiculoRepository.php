<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteVehiculo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteVehiculoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteVehiculo::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteVehiculo::class, 'v')
            ->select('v.codigoVehiculoPk')
            ->leftJoin('v.marcaRel','m')
            ->addSelect('v.placa')
            ->addSelect('v.modelo')
            ->addSelect('v.placaRemolque')
            ->addSelect('v.motor')
            ->addSelect('v.numeroEjes')
            ->addSelect('m.nombre')
            ->where('v.codigoVehiculoPk IS NOT NULL')
            ->orderBy('v.placa', 'ASC');
        if ($session->get('filtroPlaca')) {
            $queryBuilder->andWhere("v.codigoVehiculoPk = '{$session->get('filtroPlaca')}'");
        }

        return $queryBuilder;
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
        return $queryBuilder->getDQL();
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteVehiculo','v')
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
        v.codigoLineaFk as codigoLinea,
        v.codigoColorFk as codigoColor,
        v.codigoTipoCombustibleFk as tipoCombustible,
        v.codigoTipoCarroceriaFk as tipoCarroceria,
        v.pesoVacio,
        v.modelo,
        p.codigoIdentificacionFk as tipoIdentificacionPropietario,
        p.numeroIdentificacion as numeroIdentificacionPropietario,
        ps.codigoIdentificacionFk as tipoIdentificacionPoseedor,
        ps.numeroIdentificacion as numeroIdentificacionPoseedor, 
        v.numeroPoliza,
        v.fechaVencePoliza,
        a.numeroIdentificacion as numeroIdentificacionAseguradora,
        a.digitoVerificacion as digitoVerificacionAseguradora,
        v.capacidad       
        FROM App\Entity\Transporte\TteVehiculo v          
        LEFT JOIN v.marcaRel m 
        LEFT JOIN v.propietarioRel p
        LEFT JOIN v.poseedorRel ps
        LEFT JOIN v.aseguradoraRel a 
        WHERE v.codigoVehiculoPk = :codigoVehiculo'
        )->setParameter('codigoVehiculo', $codigoVehiculo);
        $arVehiculo =  $query->getSingleResult();
        return $arVehiculo;

    }

}