<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionAdicional;
use App\Entity\RecursoHumano\RhuNovedad;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuReclamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuLiquidacionAdicionalRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuLiquidacionAdicional::class);
    }

    public function eliminarEmbargosLiquidacion($codigoLiquidacion)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()->from(RhuLiquidacionAdicional::class, "la")
            ->select("la")
            ->where("la.codigoLiquidacionFk = {$codigoLiquidacion}")
            ->andWhere("la.codigoEmbargoFk IS NOT NULL");
        $arLiquidacionAdicionales = $query->getQuery()->getResult();

        if ($arLiquidacionAdicionales) {
            try {
                foreach ($arLiquidacionAdicionales as $arLiquidacionAdicional) {
                    $em->remove($arLiquidacionAdicional);
                }
            } catch (\Exception $exception) {

            }
        }
    }

    public function ibpSuplementario($arLiquidacion)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(RhuLiquidacionAdicional::class, "la")
            ->select("SUM(la.vrBonificacion) as suplementario")
            ->join("la.conceptoRel", "c")
            ->where("la.codigoLiquidacionFk = {$arLiquidacion->getCodigoLiquidacionPk()}")
            ->andWhere("c.ajusteSuplementario = 1");
        $query = $em->createQuery($qb);
        $arrayResultado = $query->getResult();
        $suplementario = $arrayResultado[0]['suplementario'];
        if ($suplementario == null) {
            $suplementario = 0;
        }
        return $suplementario;
    }
}