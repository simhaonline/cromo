<?php

namespace App\Repository;

use App\Entity\Recogida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recogida::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, r.fechaRegistro, r.fecha, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad, 
        cd.nombre AS ciudadDestino, r.estadoProgramado, r.estadoRecogido, r.unidades, r.pesoReal, r.pesoVolumen
        FROM App\Entity\Recogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co
        LEFT JOIN r.ciudadDestinoRel cd'
        );
        return $query->execute();

    }

    public function despacho($codigoRecogidaDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, r.fechaRegistro, r.fecha, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad, 
        cd.nombre AS ciudadDestino, r.estadoProgramado, r.estadoRecogido, r.unidades, r.pesoReal, r.pesoVolumen
        FROM App\Entity\Recogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co
        LEFT JOIN r.ciudadDestinoRel cd
        WHERE r.codigoDespachoRecogidaFk = :codigoDespachoRecogida'
        )->setParameter('codigoDespachoRecogida', $codigoRecogidaDespacho);

        return $query->execute();

    }

    public function despachoPendiente(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad, cd.nombre AS ciudadDestino
        FROM App\Entity\Recogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co
        LEFT JOIN r.ciudadDestinoRel cd
        WHERE r.estadoProgramado = 0'
        );
        return $query->execute();
    }

    public function despachoSinDescargar($codigoRecogidaDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoRecogidaPk, r.fechaRegistro, r.fecha, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad, 
        cd.nombre AS ciudadDestino, r.estadoProgramado, r.estadoRecogido, r.unidades, r.pesoReal, r.pesoVolumen
        FROM App\Entity\Recogida r 
        LEFT JOIN r.clienteRel c
        LEFT JOIN r.ciudadRel co
        LEFT JOIN r.ciudadDestinoRel cd
        WHERE r.estadoRecogido = 0 AND r.codigoDespachoRecogidaFk = :codigoDespachoRecogida'
        )->setParameter('codigoDespachoRecogida', $codigoRecogidaDespacho);

        return $query->execute();
    }

    public function cuentaPendientes($fechaDesde, $fechaHasta): int
    {
        $em = $this->getEntityManager();
        $cantidad = 0;
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad
        FROM App\Entity\Recogida r
        WHERE r.estadoProgramado = 0 AND r.fecha >= :fechaDesde AND  r.fecha <= :fechaHasta')
            ->setParameter('fechaDesde', $fechaDesde . " 00:00")
            ->setParameter('fechaHasta', $fechaHasta . " 23:59");
        $arrRecogidas = $query->getSingleResult();
        if($arrRecogidas) {
            $cantidad = $arrRecogidas['cantidad'];
        }
        return $cantidad;
    }

    public function cuentaSinDescargar($fechaDesde, $fechaHasta): int
    {
        $em = $this->getEntityManager();
        $cantidad = 0;
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad
        FROM App\Entity\Recogida r
        WHERE r.estadoProgramado = 1 AND r.estadoRecogido = 0 AND r.fecha >= :fechaDesde AND  r.fecha <= :fechaHasta')
            ->setParameter('fechaDesde', $fechaDesde . " 00:00")
            ->setParameter('fechaHasta', $fechaHasta . " 23:59");
        $arrRecogidas = $query->getSingleResult();
        if($arrRecogidas) {
            $cantidad = $arrRecogidas['cantidad'];
        }
        return $cantidad;
    }
    public function cuentaDescargadas($fechaDesde, $fechaHasta): int
    {
        $em = $this->getEntityManager();
        $cantidad = 0;
        $query = $em->createQuery(
            'SELECT COUNT(r.codigoRecogidaPk) as cantidad
        FROM App\Entity\Recogida r
        WHERE r.estadoRecogido = 1 AND r.fecha >= :fechaDesde AND  r.fecha <= :fechaHasta')
            ->setParameter('fechaDesde', $fechaDesde . " 00:00")
            ->setParameter('fechaHasta', $fechaHasta . " 23:59");
        $arrRecogidas = $query->getSingleResult();
        if($arrRecogidas) {
            $cantidad = $arrRecogidas['cantidad'];
        }
        return $cantidad;
    }

    public function resumenCuenta($fechaDesde, $fechaHasta): array
    {
        $arrResumen = array();
        $pendientes = $this->cuentaPendientes($fechaDesde, $fechaHasta);
        $sinDescargar = $this->cuentaSinDescargar($fechaDesde, $fechaHasta);
        $descagadas = $this->cuentaDescargadas($fechaDesde, $fechaHasta);
        $arrResumen['pendientes'] = $pendientes;
        $arrResumen['sinDescargar'] = $sinDescargar;
        $arrResumen['descargadas'] = $descagadas;
        return $arrResumen;
    }
}