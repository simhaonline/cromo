<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuEmbargoPagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEmbargoPago::class);
    }

    public function generar($codigoEmbargo, $arPago, $valor) {
        $em = $this->getEntityManager();
        $arEmbargo = $em->getRepository(RhuEmbargo::class)->find($codigoEmbargo);
        if($arEmbargo) {
            $arEmbargoPago = new RhuEmbargoPago();
            $arEmbargoPago->setEmbargoRel($arEmbargo);
            $arEmbargoPago->setFechaPago(new \ DateTime('now'));
            $arEmbargoPago->setVrCuota($valor);
            $arEmbargo->setDescuento($arEmbargo->getDescuento() + $valor);
            $em->persist($arEmbargoPago);
            $em->persist($arEmbargo);
        }

    }
}