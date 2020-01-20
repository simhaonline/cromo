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

    public function tiposRestriccionesMedicas($CodigoExamenRestriccionMedica)
    {
        $em = $this->getEntityManager();
        $db = $em->getConnection();
        $query = "SELECT codigo_examen_restriccion_medica_tipo_pk as codigoExamenRestriccionMedicaTipoPk, nombre "
            . " FROM rhu_examen_restriccion_medica_tipo "
            . "WHERE rhu_examen_restriccion_medica_tipo.codigo_examen_restriccion_medica_tipo_pk NOT IN (SELECT rhu_examen_restriccion_medica_detalle.codigo_examen_restriccion_medica_tipo_fk FROM rhu_examen_restriccion_medica_detalle)";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $arExamenRestriccionMedicaTipos = $stmt->fetchAll();
        return $arExamenRestriccionMedicaTipos;
    }
}