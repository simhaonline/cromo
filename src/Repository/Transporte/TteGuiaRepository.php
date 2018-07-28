<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;
class TteGuiaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuia::class);
    }

    public function listaDql(): string
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = 'SELECT g.codigoGuiaPk, 
        g.codigoServicioFk,
        g.codigoGuiaTipoFk, 
        g.numero,
        g.documentoCliente, 
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,
        g.vrFlete,
        g.vrManejo,
        g.vrRecaudo,         
        g.estadoImpreso,
        g.estadoEmbarcado,
        g.estadoDespachado, 
        g.estadoEntregado, 
        g.estadoSoporte, 
        g.estadoCumplido
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd WHERE g.codigoGuiaPk <> 0 ';
        if ($session->get('filtroTteCodigoGuiaTipo')) {
            $dql .= " AND g.codigoGuiaTipoFk = '" . $session->get('filtroTteCodigoGuiaTipo') . "'";
        }
        if ($session->get('filtroTteCodigoServicio')) {
            $dql .= " AND g.codigoServicioFk = '" . $session->get('filtroTteCodigoServicio') . "'";
        }
        if ($session->get('filtroTteDocumento') != "") {
            $dql .= " AND g.documentoCliente LIKE '%" . $session->get('filtroTteDocumento') . "%'";
        }
        if ($session->get('filtroTteNumeroGuia') != "") {
            $dql .= " AND g.numero =" . $session->get('filtroTteNumeroGuia');
        }
        $dql .= " ORDER BY g.fechaIngreso DESC";
        return $dql;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg');
        $queryBuilder
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.codigoServicioFk')
            ->addSelect('tg.codigoGuiaTipoFk')
            ->addSelect('tg.numero')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('tg.codigoOperacionIngresoFk')
            ->addSelect('tg.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.vrManejo')
            ->addSelect('tg.vrRecaudo')
            ->addSelect('tg.estadoImpreso')
            ->addSelect('tg.estadoAutorizado')
            ->addSelect('tg.estadoAnulado')
            ->addSelect('tg.estadoAprobado')
            ->addSelect('tg.estadoEmbarcado')
            ->addSelect('tg.estadoDespachado')
            ->addSelect('tg.estadoEntregado')
            ->addSelect('tg.estadoSoporte')
            ->addSelect('tg.estadoCumplido')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->where('tg.codigoGuiaPk <> 0');
        if ($session->get('filtroTteGuiaCodigoGuiaTipo')) {
            $queryBuilder->andWhere("tg.codigoGuiaTipoFk = '" . $session->get('filtroTteGuiaCodigoGuiaTipo') . "'");
        }
        if ($session->get('filtroTteGuiaCodigoServicio')) {
            $queryBuilder->andWhere("tg.codigoServicioFk = '" . $session->get('filtroTteGuiaCodigoServicio') . "'");
        }
        if ($session->get('filtroTteGuiaDocumento') != "") {
            $queryBuilder->andWhere("tg.documentoCliente LIKE '%" . $session->get('filtroTteGuiaDocumento') . "%'");
        }
        if ($session->get('filtroTteGuiaNumero') != "") {
            $queryBuilder->andWhere("tg.numero = " . $session->get('filtroTteGuiaNumero'));
        }
        if ($session->get('filtroTteGuiaCodigo') != "") {
            $queryBuilder->andWhere("tg.codigoGuiaPk = " . $session->get('filtroTteGuiaCodigo'));
        }
        $queryBuilder->orderBy('tg.fechaIngreso', 'DESC');

        return $queryBuilder;
    }

    public function listaEntrega($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoDespachoFk = ' . $codigoDespacho)
            ->andWhere('g.estadoDespachado = 1')
            ->andWhere('g.estadoEntregado = 0')
            ->andWhere('g.estadoAnulado = 0');
        $queryBuilder->orderBy('g.codigoGuiaPk', 'DESC');
        return $queryBuilder;
    }

    public function listaSoporte($codigoDespacho)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'g');
        $queryBuilder
            ->select('g.codigoGuiaPk')
            ->addSelect('g.numero')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.unidades')
            ->addSelect('g.pesoReal')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('g.codigoDespachoFk = ' . $codigoDespacho)
        ->andWhere('g.estadoEntregado = 1')
        ->andWhere('g.estadoSoporte = 0')
        ->andWhere('g.estadoAnulado = 0');
        $queryBuilder->orderBy('g.codigoGuiaPk', 'DESC');
        return $queryBuilder;
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
        g.codigoGuiaTipoFk,
        g.numero, 
        g.fechaIngreso,           
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,             
        c.nombreCorto AS clienteNombreCorto, 
        g.codigoCiudadDestinoFk,
        cd.nombre AS ciudadDestino,
        g.nombreDestinatario,
        g.direccionDestinatario,
        g.codigoProductoFk,
        g.empaqueReferencia
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd        
        WHERE g.codigoDespachoFk = :codigoDespacho
        ORDER BY g.codigoCiudadDestinoFk, g.ordenRuta'
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

    }

    public function relacionEntrega($codigoDespacho): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.codigoGuiaTipoFk,
        g.numero, 
        g.documentoCliente,
        g.fechaIngreso,           
        g.unidades,
        g.fechaDespacho,
        g.pesoReal,
        g.pesoVolumen,             
        c.nombreCorto AS clienteNombreCorto, 
        g.codigoCiudadDestinoFk,
        cd.nombre AS ciudadDestino,
        g.nombreDestinatario,
        g.direccionDestinatario,
        g.codigoProductoFk,
        g.empaqueReferencia
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd        
        WHERE g.codigoDespachoFk = :codigoDespacho
        ORDER BY g.codigoCiudadDestinoFk, g.ordenRuta'
        )->setParameter('codigoDespacho', $codigoDespacho);

        return $query->execute();

    }

    public function despachoPendiente()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.codigoGuiaTipoFk')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('tg.numero')
            ->addSelect('tg.codigoOperacionIngresoFk')
            ->addSelect('tg.codigoOperacionCargoFk')
            ->addSelect('tg.unidades')
            ->addSelect('tg.codigoRutaFk')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->where('tg.estadoDespachado = 0')
            ->andWhere('tg.estadoEmbarcado = 0')
            ->andWhere('tg.estadoImpreso = 1')
            ->andWhere('tg.estadoAnulado = 0');
        if ($session->get('filtroTteDespachoGuiaNumero') != '') {
            $queryBuilder->andWhere("tg.numero =  {$session->get('filtroTteDespachoGuiaNumero')}");
        }
        if ($session->get('filtroTteDespachoGuiaCodigoGuiaTipo') != '') {
            $queryBuilder->andWhere("tg.codigoGuiaTipoFk =  '{$session->get('filtroTteDespachoGuiaCodigoGuiaTipo')}'");
        }
        if ($session->get('filtroTteDespachoGuiaCodigoRuta') != '') {
            $queryBuilder->andWhere("tg.codigoRutaFk =  '{$session->get('filtroTteDespachoGuiaCodigoRuta')}'");
        }
        $queryBuilder->orderBy('tg.codigoRutaFk, tg.codigoCiudadDestinoFk, tg.ordenRuta', 'ASC');
        return $queryBuilder;
    }

    public function pendienteEntrega()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = $this->getEntityManager()->createQueryBuilder()->from(TteGuia::class, 'tg')
            ->select('tg.codigoGuiaPk')
            ->addSelect('tg.codigoServicioFk')
            ->addSelect('tg.codigoGuiaTipoFk')
            ->addSelect('tg.numero')
            ->addSelect('tg.documentoCliente')
            ->addSelect('tg.fechaIngreso')
            ->addSelect('tg.codigoOperacionIngresoFk')
            ->addSelect('tg.codigoOperacionCargoFk')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('tg.unidades')
            ->addSelect('tg.pesoReal')
            ->addSelect('tg.pesoVolumen')
            ->addSelect('tg.vrFlete')
            ->addSelect('tg.vrManejo')
            ->addSelect('tg.vrRecaudo')
            ->addSelect('ct.nombreCorto')
            ->addSelect('ct.movil')
            ->addSelect(
                '(dg.numero) AS manifiesto'
            )
            ->leftJoin('tg.clienteRel', 'c')
            ->leftJoin('tg.ciudadDestinoRel', 'cd')
            ->leftJoin('tg.despachoRel', 'dg')
            ->leftJoin('dg.conductorRel', 'ct')
            ->where('tg.estadoEntregado = 0')
            ->andWhere('tg.estadoDespachado = 1')
            ->andWhere('tg.estadoAnulado = 0');
        $dql->orderBy('tg.codigoGuiaPk', 'DESC');
        if ($session->get('filtroNumeroGuia')) {
            $dql->andWhere("tg.codigoGuiaPk = '{$session->get('filtroNumeroGuia')}'");
        }
        if ($session->get('filtroConductor') != '') {
            $dql->andWhere("ct.nombreCorto LIKE '%{$session->get('filtroConductor')}%' ");
        }
        if ($session->get('filtroDocumentoCliente')) {
            $dql->andWhere("tg.documentoCliente = '{$session->get('filtroDocumentoCliente')}'");
        }

        $query = $em->createQuery($dql);

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
        g.vrDeclara,
        g.vrFlete,
        g.vrManejo,
        g.vrRecaudo,
        g.documentoCliente,               
        g.nombreDestinatario,
        g.empaqueReferencia,
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
        WHERE g.estadoFacturaGenerada = 0 AND g.estadoAnulado = 0 AND g.codigoClienteFk = :codigoCliente
        ORDER BY g.fechaIngreso ASC'
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
                    if($arGuia->getEstadoDespachado() == 1 && $arGuia->getEstadoEntregado() == 0) {
                        $fechaHora = date_create($arrControles['txtFechaEntrega' . $codigoGuia] . " " . $arrControles['txtHoraEntrega' . $codigoGuia]);
                        $arGuia->setFechaEntrega($fechaHora);
                        $arGuia->setEstadoEntregado(1);
                        $em->persist($arGuia);
                    }
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
        if ($arrGuias) {
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
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        g.unidades,
        g.pesoReal,
        g.documentoCliente,
        g.pesoVolumen,
        g.vrFlete,
        g.vrManejo,        
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.estadoCumplido = 0 AND g.estadoSoporte = 1 AND g.codigoClienteFk = :codigoCliente AND g.fechaIngreso >= :fechaIngreso 
        ORDER BY g.fechaIngreso DESC '
        )->setParameter('codigoCliente', $codigoCliente)
        ->setParameter('fechaIngreso', "2018-04-01");
        return $query->execute();
    }

    public function informeDespachoPendienteRuta(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = "SELECT g.codigoGuiaPk, 
        g.numero, 
        g.codigoGuiaTipoFk,
        g.codigoServicioFk,
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        g.codigoRutaFk,
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,
        g.vrDeclara,
        g.vrFlete,
        g.vrFlete,
        g.vrManejo,
        g.nombreDestinatario,
        r.nombre AS nombreRuta
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        LEFT JOIN g.rutaRel r
        WHERE g.estadoEmbarcado = 0 AND g.estadoAnulado = 0 ";
        if ($session->get('filtroTteCodigoRuta')) {
            $dql .= " AND g.codigoRutaFk = " . $session->get('filtroTteCodigoRuta');
        }
        if ($session->get('filtroTteCodigoServicio')) {
            $dql .= " AND g.codigoServicioFk = '" . $session->get('filtroTteCodigoServicio') . "'";
        }
        if(!$session->get('filtroTteMostrarDevoluciones')) {
            $dql .= " AND g.codigoServicioFk <> 'DEV'";
        }
        $dql .= " ORDER BY g.codigoRutaFk, g.codigoCiudadDestinoFk";

        $query = $em->createQuery($dql);


        return $query->execute();
    }

    public function pendienteConductor(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = "SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        g.codigoRutaFk,
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,
        g.vrDeclara,
        g.vrFlete,
        con.nombreCorto as nombreConductor,
        d.codigoConductorFk
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        LEFT JOIN g.despachoRel d
        LEFT JOIN d.conductorRel con     
        WHERE g.estadoDespachado = 1 AND g.estadoAnulado = 0 AND g.estadoSoporte = 0 AND g.codigoDespachoFk IS NOT NULL ";
        if ($session->get('filtroTteCodigoConductor')) {
            $dql .= " AND d.codigoConductorFk = " . $session->get('filtroTteCodigoConductor');
        }
        $dql .= "ORDER BY d.codigoConductorFk, g.codigoCiudadDestinoFk";

        $query = $em->createQuery($dql);


        return $query->execute();
    }

    public function pendiente(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = "SELECT g.codigoGuiaPk, 
        g.numero, 
        g.fechaIngreso,
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk, 
        g.codigoRutaFk,
        c.nombreCorto AS clienteNombreCorto,  
        cd.nombre AS ciudadDestino,
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,
        g.vrDeclara,
        g.vrFlete,
        con.nombreCorto as nombreConductor,
        d.codigoConductorFk
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        LEFT JOIN g.despachoRel d
        LEFT JOIN d.conductorRel con     
        WHERE g.estadoDespachado = 1 AND g.estadoAnulado = 0 AND g.estadoSoporte = 0 AND g.codigoDespachoFk IS NOT NULL ";
        /*if($session->get('filtroTteCodigoConductor')) {
            $dql .= " AND d.codigoConductorFk = " . $session->get('filtroTteCodigoConductor');
        }*/
        $dql .= "ORDER BY d.codigoConductorFk, g.codigoCiudadDestinoFk";

        $query = $em->createQuery($dql);


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

    public function imprimir($codigoGuia): bool
    {
        $em = $this->getEntityManager();
        $respuesta = false;
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if ($arGuia->getEstadoImpreso() == 0) {
            if ($arGuia->getNumero() == 0 || $arGuia->getNumero() == NULL) {
                $arGuiaTipo = $em->getRepository(TteGuiaTipo::class)->find($arGuia->getCodigoGuiaTipoFk());
                $arGuia->setNumero($arGuiaTipo->getConsecutivo());
                $arGuiaTipo->setConsecutivo($arGuiaTipo->getConsecutivo() + 1);
                $em->persist($arGuiaTipo);
            }
            $arGuia->setEstadoImpreso(1);
            $em->persist($arGuia);
            $respuesta = true;
        }
        return $respuesta;
    }

    public function periodoCierre($anio, $mes): array
    {
        $em = $this->getEntityManager();
        $ultimoDia = date("d", (mktime(0, 0, 0, $mes + 1, 1, $anio) - 1));
        $fechaDesde = $anio . "-" . $mes . "-" . "01 00:00";
        $fechaHasta = $anio . "-" . $mes . "-" . $ultimoDia . " 23:59";
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk,  
        g.vrFlete
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.fechaIngreso >= :fechaDesde AND g.fechaIngreso <= :fechaHasta  
        ORDER BY g.codigoGuiaPk DESC '
        )->setParameter('fechaDesde', $fechaDesde)
            ->setParameter('fechaHasta', $fechaHasta);
        return $query->execute();
    }

    public function procesoCosto($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.vrFlete        
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.codigoGuiaPk >= :guia'
        )->setParameter('guia', $codigoGuia);
        return $query->execute();
    }

    public function formatoFactura($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        g.numero,
        g.documentoCliente, 
        g.fechaIngreso,        
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk,     
        g.unidades,
        g.pesoReal,
        g.pesoVolumen,
        g.pesoFacturado,
        g.vrDeclara,
        g.vrFlete,
        g.vrManejo,
        g.vrFlete + g.vrManejo AS vrTotal,
        g.nombreDestinatario,             
        c.nombreCorto AS clienteNombreCorto, 
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteGuia g 
        LEFT JOIN g.clienteRel c
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE g.codigoFacturaFk = :codigoFactura'
        )->setParameter('codigoFactura', $codigoFactura);

        return $query->execute();

    }

    public function redespacho($codigoGuia): bool
    {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if($arGuia) {
            if($arGuia->getEstadoDespachado() == 1 && $arGuia->getCodigoDespachoFk() && $arGuia->getEstadoAnulado() == 0 ) {
                $arGuia->setFechaDespacho(NULL);
                $arGuia->setFechaCumplido(NULL);
                $arGuia->setFechaEntrega(NULL);
                $arGuia->setEstadoDespachado(0);
                $arGuia->setEstadoEmbarcado(0);
                $arGuia->setEstadoEntregado(0);
                $arGuia->setEstadoSoporte(0);
                $arGuia->setCodigoDespachoFk(NULL);
                $em->persist($arGuia);
                $em->flush();
                Mensajes::success("La guia se activo para redespacho");
            } else {
                Mensajes::error("La guia debe estar despachada y no puede estar anulada");
            }
        } else {
            Mensajes::error("No existe la guia");
        }
        return true;
    }

    /**
     * @param $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiEntrega($codigoGuia, $fecha, $hora) {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if($arGuia) {
            if($arGuia->getEstadoDespachado() == 1) {
                if($arGuia->getEstadoEntregado() == 0) {
                    $fechaHora = date_create($fecha . " " . $hora);
                    $arGuia->setFechaEntrega($fechaHora);
                    $arGuia->setEstadoEntregado(1);
                    $em->persist($arGuia);
                    $em->flush();
                    return [
                        'error' => false,
                        'mensaje' => '',
                    ];
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede estar entregada previamente',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia no esta despachada',
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " no existe " . $fecha . " " . $hora,
            ];
        }
    }

    /**
     * @param $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiSoporte($codigoGuia) {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        if($arGuia) {
            if($arGuia->getEstadoEntregado() == 1) {
                $arGuia->setEstadoSoporte(1);
                $arGuia->setFechaSoporte(new \DateTime('now'));
                $em->persist($arGuia);
                $em->flush();
                return [
                    'error' => false,
                    'mensaje' => '',
                ];
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia debe estar entregada',
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " no existe ",
            ];
        }
    }

    /**
     * @param $codigoDespacho $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiDespachoAdicionar($codigoDespacho, $codigoGuia) {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $arDespacho = $em->getRepository(TteDespacho::class)->find($codigoDespacho);
        if($arGuia && $arDespacho) {
            if($arGuia->getEstadoEmbarcado() == 0) {
                $arGuia->setDespachoRel($arDespacho);
                $arGuia->setEstadoEmbarcado(1);
                $em->persist($arGuia);

                $arDespachoDetalle = new TteDespachoDetalle();
                $arDespachoDetalle->setDespachoRel($arDespacho);
                $arDespachoDetalle->setGuiaRel($arGuia);
                $arDespachoDetalle->setVrDeclara($arGuia->getVrDeclara());
                $arDespachoDetalle->setVrFlete($arGuia->getVrFlete());
                $arDespachoDetalle->setVrManejo($arGuia->getVrManejo());
                $arDespachoDetalle->setVrRecaudo($arGuia->getVrRecaudo());
                $arDespachoDetalle->setUnidades($arGuia->getUnidades());
                $arDespachoDetalle->setPesoReal($arGuia->getPesoReal());
                $arDespachoDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                $em->persist($arDespachoDetalle);

                $arDespacho->setUnidades($arDespacho->getUnidades() + $arGuia->getUnidades());
                $arDespacho->setPesoReal($arDespacho->getPesoReal() + $arGuia->getPesoReal());
                $arDespacho->setPesoVolumen($arDespacho->getPesoVolumen() + $arGuia->getPesoVolumen());
                $arDespacho->setCantidad($arDespacho->getCantidad() + 1);
                $em->persist($arDespacho);
                $em->flush();
                return [
                    'error' => false,
                    'mensaje' => '',
                ];
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia ya esta embarcada en el despacho ' . $arGuia->getCodigoDespachoFk(),
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " o el despacho " . $codigoDespacho . " no existe ",
            ];
        }
    }

    /**
     * @param $codigoDespacho $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiFacturaAdicionar($codigoFactura, $codigoGuia, $documento, $tipo) {
        $em = $this->getEntityManager();
        $arFactura = $em->getRepository(TteFactura::class)->find($codigoFactura);
        $arGuia = NULL;

        if($tipo == 1) {
            $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        }
        if($tipo == 2) {
            $arGuiaDocumento = $em->getRepository(TteGuia::class)->findOneBy(array(
                'documentoCliente' => $documento,
                'codigoClienteFk' => $arFactura->getCodigoClienteFk(),
                'estadoFacturaGenerada' => 0));
            if($arGuiaDocumento) {
                $arGuia = $em->getRepository(TteGuia::class)->find($arGuiaDocumento->getCodigoGuiaPk());
            } else {
                $arGuia = "";
            }
        }

        if($arGuia && $arFactura) {
            if($arGuia->getEstadoFacturaGenerada() == 0) {
                if($arGuia->getFactura() == 0 && $arGuia->getEstadoAnulado() == 0) {
                    if($arGuia->getCodigoClienteFk() == $arFactura->getCodigoClienteFk()) {
                        $arGuia->setFacturaRel($arFactura);
                        $arGuia->setEstadoFacturaGenerada(1);
                        $em->persist($arGuia);

                        $arFacturaDetalle = new TteFacturaDetalle();
                        $arFacturaDetalle->setFacturaRel($arFactura);
                        $arFacturaDetalle->setGuiaRel($arGuia);
                        $arFacturaDetalle->setVrDeclara($arGuia->getVrDeclara());
                        $arFacturaDetalle->setVrFlete($arGuia->getVrFlete());
                        $arFacturaDetalle->setVrManejo($arGuia->getVrManejo());
                        $arFacturaDetalle->setUnidades($arGuia->getUnidades());
                        $arFacturaDetalle->setPesoReal($arGuia->getPesoReal());
                        $arFacturaDetalle->setPesoVolumen($arGuia->getPesoVolumen());
                        $em->persist($arFacturaDetalle);

                        $arFactura->setGuias($arFactura->getGuias()+1);
                        $arFactura->setVrFlete($arFactura->getVrFlete() + $arGuia->getVrFlete());
                        $arFactura->setVrManejo($arFactura->getVrManejo() + $arGuia->getVrManejo());
                        $subtotal = $arFactura->getVrSubtotal() + $arGuia->getVrFlete() + $arGuia->getVrManejo();
                        $arFactura->setVrSubtotal($subtotal);
                        $arFactura->setVrTotal($subtotal);
                        $em->persist($arFactura);
                        $em->flush();
                        return [
                            'error' => false,
                            'mensaje' => '',
                        ];
                    } else {
                        return [
                            'error' => true,
                            'mensaje' => 'La guia es de otro cliente y no se puede adicionar a la factura ',
                        ];
                    }
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede ser una factura de venta y no puede estar anulada',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia ya esta prefacturada o facturada en la factura ' . $arGuia->getCodigoFacturaFk(),
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " o la factura " . $codigoFactura . " no existe ",
            ];
        }
    }


    /**
     * @param $codigoCumplido $codigoGuia
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function apiCumplidoAdicionar($codigoCumplido, $codigoGuia, $documento, $tipo) {
        $em = $this->getEntityManager();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $arGuia = NULL;

        if($tipo == 1) {
            $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        }
        if($tipo == 2) {
            $arGuiaDocumento = $em->getRepository(TteGuia::class)->findOneBy(array(
                'documentoCliente' => $documento,
                'codigoClienteFk' => $arCumplido->getCodigoClienteFk(),
                'estadoCumplido' => 0));
            if($arGuiaDocumento) {
                $arGuia = $em->getRepository(TteGuia::class)->find($arGuiaDocumento->getCodigoGuiaPk());
            } else {
                $arGuia = "";
            }
        }

        if($arGuia && $arCumplido) {
            if($arGuia->getEstadoCumplido() == 0) {
                if($arGuia->getEstadoAnulado() == 0) {
                    if($arGuia->getCodigoClienteFk() == $arCumplido->getCodigoClienteFk()) {
                        $arGuia->setCumplidoRel($arCumplido);
                        $arGuia->setEstadoCumplido(1);
                        $em->persist($arGuia);

                        //$arCumplido->setUnidades($arCumplido->getUnidades() + $arGuia->getUnidades());
                        //$arCumplido->setPesoReal($arCumplido->getPesoReal() + $arGuia->getPesoReal());
                        //$arCumplido->setPesoVolumen($arCumplido->getPesoVolumen() + $arGuia->getPesoVolumen());
                        $arCumplido->setCantidad($arCumplido->getCantidad() + 1);
                        $em->persist($arCumplido);
                        $em->flush();
                        return [
                            'error' => false,
                            'mensaje' => '',
                        ];
                    } else {
                        return [
                            'error' => true,
                            'mensaje' => 'La guia es de otro cliente y no se puede adicionar al cumplido ',
                        ];
                    }
                } else {
                    return [
                        'error' => true,
                        'mensaje' => 'La guia no puede estar anulada',
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'mensaje' => 'La guia ya esta en los cumplidos ' . $arGuia->getCodigoCumplidoFk(),
                ];
            }
        } else {
            return [
                'error' => true,
                'mensaje' => "La guia " . $codigoGuia . " o el cumplido " . $codigoCumplido . " no existe pendiente para cumplido",
            ];
        }
    }

}