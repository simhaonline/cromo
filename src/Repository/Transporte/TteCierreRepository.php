<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteCosto;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteCierreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCierre::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.codigoCierrePk, 
        c.anio, 
        c.mes,
        c.estadoGenerado,
        c.estadoCerrado
        FROM App\Entity\Transporte\TteCierre c                 
        ORDER BY c.anio, c.mes DESC '
        );
        return $query->execute();
    }

    public function generar($codigoCierre): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arCierre = $em->getRepository(TteCierre::class)->find($codigoCierre);
        /*$ultimoDia = date("d", (mktime(0, 0, 0, $arCierre->getMes() + 1, 1, $arCierre->getAnio()) - 1));
        $fechaDesde = $arCierre->getAnio() . "-" . $arCierre->getMes() . "-01 00:00:00";
        $fechaHasta = $arCierre->getAnio() . "-" . $arCierre->getMes() . "-" . $ultimoDia . " 23:59:00";
        $fletePago = $em->getRepository(TteDespacho::class)->fletePago($fechaDesde, $fechaHasta);
        $fletePagoRecogidas = $em->getRepository(TteDespachoRecogida::class)->fletePago($fechaDesde, $fechaHasta);
        $fleteCobro = $em->getRepository(TteFactura::class)->fleteCobro($fechaDesde, $fechaHasta);
        $arCierre->setVrFletePago($fletePago);
        $arCierre->setVrFlete($fleteCobro);*/

        $arCierre->setEstadoGenerado(1);
        $em->flush();
        return $respuesta;
    }
    public function deshacer($codigoCierre): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arCierre = $em->getRepository(TteCierre::class)->find($codigoCierre);
        //$query = $em->createQuery('DELETE FROM App\Entity\Transporte\TteCosto c WHERE c.codigoCierreFk =' . $codigoCierre);
        //$numDeleted = $query->execute();
        $arCierre->setEstadoGenerado(0);
        $em->flush();
        return $respuesta;
    }


}