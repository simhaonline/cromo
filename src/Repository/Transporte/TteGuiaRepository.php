<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteGuiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuia::class);
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
        FROM App\Entity\Transporte\TteGuia g 
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
        FROM App\Entity\Transporte\TteGuia g 
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
        FROM App\Entity\Transporte\TteGuia g 
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
        cd.nombre AS ciudadDestino,
        g.nombreDestinatario
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.codigoDespachoFk = :codigoDespacho'
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

    }

    public function despachoOrden($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,           
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,             
        c.nombreCorto AS clienteNombreCorto, 
        g.codigoCiudadDestinoFk,
        cd.nombre AS ciudadDestino,
        g.nombreDestinatario
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.codigoDespachoFk = :codigoDespacho
        ORDER BY g.codigoRutaFk, g.ordenRuta'
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

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
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoDespachado = 0 AND g.estadoEmbarcado = 0 
        ORDER BY g.codigoRutaFk, g.codigoCiudadDestinoFk'
        );
        return $query->execute();
    }

    public function cumplido($codigoCumplido): array
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
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.codigoCumplidoFk = :codigoCumplido'
        )->setParameter('codigoCumplido', $codigoCumplido);

        return $query->execute();

    }

    public function factura($codigoFactura): array
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
        g.vrFlete,
        g.vrManejo,             
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.codigoFacturaFk = :codigoFactura'
        )->setParameter('codigoFactura', $codigoFactura);

        return $query->execute();

    }

    public function facturaPendiente($codigoCliente = null): array
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
        g.vrFlete,
        g.vrManejo,        
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoFacturado = 0 AND g.codigoClienteFk = :codigoCliente
        ORDER BY g.codigoRutaFk, g.codigoCiudadDestinoFk'
        )->setParameter('codigoCliente', $codigoCliente);
        return $query->execute();
    }

    public function pendienteGenerarFactura($codigoCliente = null): array
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
        g.vrFlete,
        g.vrManejo,        
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd    
        WHERE g.estadoImpreso = 1 AND g.estadoFacturaGenerada = 0 AND g.factura = 1   
        ORDER BY g.codigoRutaFk, g.codigoCiudadDestinoFk'
        );
        return $query->execute();
    }

    public function entrega($arrGuias, $arrControles): bool
    {
        $em = $this->getEntityManager();
        if ($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    $fechaHora = date_create($arrControles['txtFechaEntrega' . $codigoGuia] . " " . $arrControles['txtHoraEntrega' . $codigoGuia]);
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
        if ($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    $arGuia->setFechaSoporte(new \DateTime("now"));
                    $arGuia->setEstadoSoporte(1);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function generarFactura($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    $arGuia->setEstadoFacturaGenerada(1);
                    $em->persist($arGuia);
                    $arFactura = new TteFactura();
                    $arFactura->setFacturaTipoRel($arGuia->getGuiaTipoRel()->getFacturaTipoRel());
                    $arFactura->setNumero($arGuia->getNumero());
                    $arFactura->setFecha($arGuia->getFechaIngreso());
                    $total = $arGuia->getVrFlete() + $arGuia->getVrManejo();
                    $arFactura->setVrFlete($arGuia->getVrFlete());
                    $arFactura->setVrManejo($arGuia->getVrManejo());
                    $arFactura->setVrTotal($total);
                    $arFactura->setGuias(1);
                    $arFactura->setClienteRel($arGuia->getClienteRel());
                    $em->persist($arFactura);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function cumplidoPendiente($codigoCliente): array
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
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoCumplido = 0 AND g.estadoSoporte = 1 AND g.codigoClienteFk = :codigoCliente
        ORDER BY g.codigoRutaFk, g.codigoCiudadDestinoFk'
        )->setParameter('codigoCliente', $codigoCliente);
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
        FROM App\Entity\Transporte\TteGuia g 
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
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.estadoDespachado = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function pendientesEntregaCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.estadoDespachado = 1 AND g.estadoEntregado = 0');
        $arrGuias = $query->getSingleResult();
        return $arrGuias;
    }

    public function pendientesCumplidoCuenta(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad
        FROM App\Entity\Transporte\TteGuia g
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