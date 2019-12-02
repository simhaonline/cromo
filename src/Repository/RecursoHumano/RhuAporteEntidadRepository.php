<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteEntidad;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionAporte;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteEntidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuAporteEntidad::class);
    }

    public function lista($codigoAporte)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAporteEntidad::class, 'ae')
            ->select('ae.codigoAporteEntidadPk')
            ->addSelect('ae.tipo')
            ->addSelect('ae.vrAporte')
            ->addSelect('e.nombre as entidadNombre')
            ->leftJoin('ae.entidadRel', 'e')
            ->where('ae.codigoAporteFk = ' . $codigoAporte)
            ->orderBy('ae.tipo', 'DESC');
        return $queryBuilder;
    }

}
