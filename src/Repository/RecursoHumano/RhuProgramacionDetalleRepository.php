<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuProgramacionDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuProgramacionDetalle::class);
    }

    public function eliminar($arrSeleccionados){
        foreach ($arrSeleccionados as $codigoProgramacionDetalle){
            $arProgramacionDetalle = $this->_em->getRepository(RhuProgramacionDetalle::class)->find($codigoProgramacionDetalle);
            if($arProgramacionDetalle){
                $this->_em->remove($arProgramacionDetalle);
            }
        }
        $this->_em->flush();
    }

    public function contarDetalles($codigoProgramacion){
        $this->_em->createQueryBuilder()->from(RhuProgramacionDetalle::class,'pd')
            ->select('COUNT(pd.codigoProgramacionDetallePk)')
            ->where('pd.codigoProgramacionFk =' . $codigoProgramacion)->getQuery()->execute();
    }
}