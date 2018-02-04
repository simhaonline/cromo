<?php

namespace App\Repository;

use App\Entity\Guia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GuiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Guia::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,         
        g.estadoImpreso,
        g.estadoEmbarcado,
        g.estadoDespachado, 
        g.estadoEntregado, 
        g.estadoSoporte, 
        g.estadoCumplido
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd'
        );
        return $query->execute();
    }

    public function listaEntrega($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoEntregado = 0 AND g.codigoDespachoFk = :codigoDespacho'
        )->setParameter('codigoDespacho', $codigoDespacho);
        return $query->execute();
    }

    public function listaSoporte($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoEntregado = 1 AND g.estadoSoporte = 0 AND g.codigoDespachoFk = :codigoDespacho'
        )->setParameter('codigoDespacho', $codigoDespacho);
        return $query->execute();
    }

    public function despacho($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,        
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk,     
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,             
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.codigoDespachoFk = :codigoDespacho'
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

    }

    public function entrega($arrGuias, $arrControles): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(Guia::class)->find($codigoGuia);
                    $fechaHora = date_create($arrControles['txtFechaEntrega'.$codigoGuia] . " " . $arrControles['txtHoraEntrega'.$codigoGuia]);
                    $arGuia->setFechaEntrega($fechaHora);
                    $arGuia->setEstadoEntregado(1);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function soporte($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(Guia::class)->find($codigoGuia);
                    $arGuia->setFechaSoporte(new \DateTime("now"));
                    $arGuia->setEstadoSoporte(1);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function despachoPendiente(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,        
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoDespachado = 0
        ORDER BY g.codigoRutaFk, g.codigoCiudadDestinoFk'
        );
        return $query->execute();

    }

    public function informeDespachoPendienteRuta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        g.codigoRutaFk,
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal,
        g.pesoVolumen
        FROM App\Entity\Guia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoDespachado = 0 
        ORDER BY g.codigoRutaFk, g.codigoCiudadDestinoFk'
        );
        return $query->execute();
    }

    public function pendientesDespachoCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Guia g
        WHERE g.estadoDespachado = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function pendientesEntregaCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Guia g
        WHERE g.estadoDespachado = 1 AND g.estadoEntregado = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function pendientesCumplidoCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Guia g
        WHERE g.estadoDespachado = 1 AND g.estadoEntregado = 1 AND g.estadoCumplido = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function resumenPendientesCuenta(): array
    {
        $arrResumen = array();
        $despacho = $this->pendientesDespachoCuenta();
        $entregar = $this->pendientesEntregaCuenta();
        $cumplir = $this->pendientesCumplidoCuenta();
        $arrResumen['despachar'] = $despacho;
        $arrResumen['entregar'] = $entregar;
        $arrResumen['cumplir'] = $cumplir;
        return $arrResumen;
    }

}