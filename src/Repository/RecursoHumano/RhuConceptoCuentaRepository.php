<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuConceptoCuentaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuConceptoCuenta::class);
    }

    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $arConceptoCuenta = $em->getRepository(RhuConceptoCuenta::class)->find($codigo);
                if ($arConceptoCuenta) {
                    $em->remove($arConceptoCuenta);
                }
            }
            $em->flush();
        }
    }
}