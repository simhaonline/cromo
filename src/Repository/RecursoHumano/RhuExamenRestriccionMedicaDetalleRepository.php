<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamenRestriccionMedicaDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class RhuExamenRestriccionMedicaDetalleRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuExamenRestriccionMedicaDetalle::class);
    }
//
//    public function tiposRestriccionesMedicas($CodigoExamenRestriccionMedica)
//    {
//        $em = $this->getEntityManager();
//        $db = $em->getConnection();
//        $query = "SELECT codigo_examen_restriccion_medica_tipo_pk as codigoExamenRestriccionMedicaTipoPk, nombre "
//            . " FROM rhu_examen_restriccion_medica_tipo "
//            . "WHERE rhu_examen_restriccion_medica_tipo.codigo_examen_restriccion_medica_tipo_pk NOT IN (SELECT rhu_examen_restriccion_medica_detalle.codigo_examen_restriccion_medica_tipo_fk FROM rhu_examen_restriccion_medica_detalle)";
//        $stmt = $db->prepare($query);
//        $params = array();
//        $stmt->execute($params);
//        $arExamenRestriccionMedicaTipos = $stmt->fetchAll();
//        return $arExamenRestriccionMedicaTipos;
//    }
//
//    public function listaDQL($identificacion = '',$restriccionTipo = '', $estadoEmpleado= ""){
//        $em = $this->getEntityManager();
//        $qb = $em->createQueryBuilder()->from('BrasaRecursoHumanoBundle:RhuExamenRestriccionMedicaDetalle','erd')
//        ->select('erd')
//        ->addSelect('er')
//        ->addSelect('ex')
//        ->innerJoin('erd.examenRestriccionMedicaDetalleRel','er')
//        ->innerJoin('er.examenRel','ex')
//            ->leftJoin('ex.empleadoRel','epl')
//        ->where('erd.codigoExamenRestriccionMedicaDetallePk <> 0');
//        if($identificacion != ''){
//            $qb->andWhere("ex.identificacion = {$identificacion}");
//        }
//        if($restriccionTipo != ''){
//            $qb->andWhere("erd.codigoExamenRestriccionMedicaTipoFk = {$restriccionTipo}");
//        }
//        if(is_numeric($estadoEmpleado)){
//            $qb->andWhere("epl.estadoActivo = {$estadoEmpleado}");
//        }
//        $qb->orderBy('erd.codigoExamenRestriccionMedicaDetallePk','DESC');
//        return $qb->getDQL();
//    }
}