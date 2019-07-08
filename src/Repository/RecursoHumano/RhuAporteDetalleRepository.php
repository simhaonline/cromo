<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteContrato;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteSoporte;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAporteDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAporteDetalle::class);
    }

    public function lista()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(RhuAporteSoporte::class, 'asop')
            ->select('asop.codigoAporteSoportePk')
            ->where('asop.codigoAporteContratoFk=');
        $arAporteSoportes = $queryBuilder->getQuery()->getResult();
        return $arAporteSoportes;
    }

    public function generar($arAporte)
    {
        $em = $this->getEntityManager();
        $arAporteContratos = $em->getRepository(RhuAporteContrato::class)->listaGenerarSoporte($arAporte->getCodigoAportePk());
        foreach ($arAporteContratos as $arAporteContrato) {

        }
    }
}