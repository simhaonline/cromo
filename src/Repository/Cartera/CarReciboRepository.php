<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarReciboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarRecibo::class);
    }

    public function lista(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarRecibo::class, 'r');
        $qb->select('r.codigoReciboPk')
            ->leftJoin('r.clienteRel','cr')
            ->leftJoin('r.cuentaRel','c')
            ->addSelect('c.nombre')
            ->addSelect('cr.nombreCorto')
            ->addSelect('cr.numeroIdentificacion')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.fechaPago')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.vrPagoTotal')
            ->addSelect('r.estadoAutorizado')
            ->addSelect('r.estadoAnulado')
            ->addSelect('r.estadoImpreso')
            ->where('r.codigoReciboPk <> 0')
            ->orderBy('r.codigoReciboPk', 'DESC');
        if ($session->get('filtroNumero')) {
            $qb->andWhere("r.numero = '{$session->get('filtroNumero')}'");
        }
        $query = $qb->getDQL();
        $query = $em->createQuery($query);

        return $query->execute();
    }

    public function autorizar($arRecibo)
    {
        if (count($this->getEntityManager()->getRepository(CarReciboDetalle::class)->findBy(['codigoReciboFk' => $arRecibo->getCodigoReciboPk()])) > 0) {
            $arRecibo->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arRecibo);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    public function desAutorizar($arRecibo)
    {
        $em = $this->getEntityManager();
        $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $arRecibo->getCodigoREciboPk()));
        foreach ($arReciboDetalles AS $arReciboDetalle) {
            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arReciboDetalle->getCodigoCuentaCobrarFk());
            $saldo = $arCuentaCobrar->getVrSaldo() + $arReciboDetalle->getVrPagoAfectar();
            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
            $arCuentaCobrar->setVrSaldo($saldo);
            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $arReciboDetalle->getVrPagoAfectar());
            $em->persist($arCuentaCobrar);
        }

        $arRecibo->setEstadoAutorizado(0);
        $em->persist($arRecibo);
        $em->flush();
    }
}