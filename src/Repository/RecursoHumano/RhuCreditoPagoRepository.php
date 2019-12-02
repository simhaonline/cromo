<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuCreditoPagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCreditoPago::class);
    }

    public function listaPorCredito($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCreditoPago::class, 'cp')
            ->select('cp')
            ->where("cp.codigoCreditoFk = {$id}")
            ->orderBy('cp.codigoCreditoPagoPk', 'DESC');

        return $queryBuilder;
    }

    public function generar($codigoCredito, $tipo, $valor) {
        $em = $this->getEntityManager();
        $arCredito = $em->getRepository(RhuCredito::class)->find($codigoCredito);
        $arCreditoTipoPago = $em->getRepository(RhuCreditoPagoTipo::class)->find($tipo);
        if($arCredito && $arCreditoTipoPago) {
            $saldo = $arCredito->getVrSaldo() - $valor;
            $arCredito->setVrSaldo($saldo);
            $arCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual() + 1);
            $arCredito->setVrAbonos($arCredito->getVrAbonos() + $valor);

            $arPagoCredito = new RhuCreditoPago();
            $arPagoCredito->setCreditoRel($arCredito);
            $arPagoCredito->setfechaPago(new \ DateTime("now"));
            $arPagoCredito->setCreditoPagoTipoRel($arCreditoTipoPago);
            $arPagoCredito->setVrPago($valor);
            $arPagoCredito->setVrSaldo($saldo);
            $arPagoCredito->setNumeroCuotaActual($arCredito->getNumeroCuotaActual());
            $em->persist($arPagoCredito);
            if ($arCredito->getVrSaldo() <= 0) {
                $arCredito->setEstadoPagado(1);
            }
            $em->persist($arCredito);
        }
    }
}