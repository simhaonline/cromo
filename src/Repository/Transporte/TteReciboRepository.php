<?php

namespace App\Repository\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteRecibo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteReciboRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRecibo::class);
    }

    public function relacionCaja($codigoRelacionCaja): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoReciboPk, 
        r.fecha,
        r.vrFlete,
        r.vrManejo,
        r.vrTotal,
        g.fechaIngreso,
        g.codigoGuiaTipoFk,
        g.numeroFactura as numeroFactura,
        g.documentoCliente,
        r.codigoGuiaFk,
        c.nombreCorto AS clienteNombre         
        FROM App\Entity\Transporte\TteRecibo r 
        LEFT JOIN r.guiaRel g
        LEFT JOIN r.clienteRel c
        WHERE r.codigoRelacionCajaFk = :codigoRelacionCaja'
        )->setParameter('codigoRelacionCaja', $codigoRelacionCaja);

        return $query->execute();

    }

    public function relacionPendiente(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT r.codigoReciboPk,
            r.fecha,
            r.vrFlete,
            r.vrManejo,
            r.vrTotal
        FROM App\Entity\Transporte\TteRecibo r 
        WHERE r.estadoRelacion = 0  
        ORDER BY r.codigoReciboPk'
        );
        return $query->execute();
    }

    public function guia($id){
        $em = $this->getEntityManager();
        $arRecibo = $em->createQueryBuilder()
            ->from('App:Transporte\TteRecibo','r')
            ->select('r.codigoReciboPk')
            ->addSelect("r.codigoGuiaFk")
            ->addSelect('r.codigoReciboTipoFk')
            ->addSelect('r.fecha')
            ->andWhere("r.codigoGuiaFk='{$id}'")
            ->getQuery()->getResult();

        return $arRecibo;
    }

    public function apiWindowsNuevo($raw) {
        $em = $this->getEntityManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($raw['codigoGuiaFk']);
        $arOperacion = $em->getRepository(TteOperacion::class)->find($raw['codigoOperacionFk']);
        $arCliente = $em->getRepository(TteCliente::class)->find($raw['codigoClienteFk']);
        $abono = $raw['vrTotal'];
        $arRecibo = new TteRecibo();
        $arRecibo->setClienteRel($arCliente);
        $arRecibo->setOperacionRel($arOperacion);
        $arRecibo->setGuiaRel($arGuia);
        $arRecibo->setVrFlete($raw['vrFlete']);
        $arRecibo->setVrManejo($raw['vrManejo']);
        $arRecibo->setVrTotal($raw['vrTotal']);
        $arRecibo->setFecha(new \DateTime('now'));
        $em->persist($arRecibo);

        $arGuia->setVrAbono($arGuia->getVrAbono() + $abono);
        $arGuia->setVrCobroEntrega($arGuia->getVrRecaudo() + $arGuia->getVrFlete() + $arGuia->getVrManejo() - $arGuia->getVrAbono());
        $em->persist($arGuia);
        $em->flush();

        return [
            "codigoReciboPk" => $arRecibo->getCodigoReciboPk()
        ];
    }

    public function apiWindowsDetalle($raw) {
        $em = $this->getEntityManager();
        $codigoGuia = $raw['codigoGuia']?? null;
        if($codigoGuia) {
            $queryBuilder = $em->createQueryBuilder()->from(TteRecibo::class, 'r')
                ->select('r.codigoReciboPk')
                ->addSelect('r.vrFlete')
                ->addSelect('r.vrManejo')
                ->addSelect('r.vrTotal')
                ->where("r.codigoGuiaFk = {$codigoGuia}")
                ->setMaxResults(10);
            $arRecibos = $queryBuilder->getQuery()->getResult();
            return $arRecibos;
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }

    public function apiWindowsImprimir($raw) {
        $em = $this->getEntityManager();
        $codigo = $raw['codigoGuia']?? null;
        if($codigo) {
            $queryBuilder = $em->createQueryBuilder()->from(TteGuia::class, 'g')
                ->select('g.codigoGuiaPk')
                ->addSelect('g.numero')
                ->addSelect('g.factura')
                ->addSelect('g.numeroFactura')
                ->addSelect('gt.codigoFacturaTipoFk')
                ->addSelect('gt.mensajeFormato')
                ->addSelect('gt.nombre as guiaTipoNombre')
                ->addSelect('g.fechaIngreso')
                ->addSelect('co.nombre as ciudadOrigenNombre')
                ->addSelect('cd.nombre as ciudadDestinoNombre')
                ->addSelect('g.nombreDestinatario')
                ->addSelect('g.telefonoDestinatario')
                ->addSelect('g.direccionDestinatario')
                ->addSelect('c.nombreCorto as clienteNombre')
                ->addSelect('c.direccion as clienteDireccion')
                ->addSelect('c.telefono as clienteTelefono')
                ->addSelect('g.documentoCliente')
                ->addSelect('g.vrDeclara')
                ->addSelect('g.vrFlete')
                ->addSelect('g.vrManejo')
                ->addSelect('g.vrCobroEntrega')
                ->addSelect('g.vrAbono')
                ->addSelect('g.unidades')
                ->addSelect('g.pesoReal')
                ->leftJoin('g.guiaTipoRel', 'gt')
                ->leftJoin('g.ciudadOrigenRel', 'co')
                ->leftJoin('g.ciudadDestinoRel', 'cd')
                ->leftJoin('g.clienteRel', 'c');
            $queryBuilder->where("g.codigoGuiaPk=" . $codigo);
            $arGuias = $queryBuilder->getQuery()->getResult();
            if($arGuias) {
                $arGuia = $arGuias[0];
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
                $numeroFactura = "";
                $fleteFactura = 0;
                $manejoFactura = 0;
                $totalFactura = 0;
                $tituloFactura = "";
                $resolucionFactura = "";
                if($arGuia['factura']) {
                    if($arGuia['codigoFacturaTipoFk']) {
                        $arFacturaTipo = $em->getRepository(TteFacturaTipo::class)->find($arGuia['codigoFacturaTipoFk']);
                        if($arFacturaTipo) {
                            $numeroFactura = $arFacturaTipo->getPrefijo() . $arGuia['numeroFactura'];
                            $tituloFactura = $arFacturaTipo->getNombre();
                        }
                    }
                    $fleteFactura = $arGuia['vrFlete'];
                    $manejoFactura = $arGuia['vrManejo'];
                    $totalFactura = $arGuia['vrFlete'] + $arGuia['vrManejo'];
                    $resolucionFactura = $arGuia['mensajeFormato'];
                }
                $queryBuilder = $em->createQueryBuilder()->from(TteRecibo::class, 'r')
                    ->select('r.codigoReciboPk')
                    ->addSelect('r.numero')
                    ->addSelect('r.vrFlete')
                    ->addSelect('r.vrManejo')
                    ->addSelect('r.vrTotal')
                    ->where('r.codigoGuiaFk =' . $codigo);
                $arRecibos = $queryBuilder->getQuery()->getResult();
                $arrRecibos = null;
                $i = 0;
                foreach ($arRecibos as $arRecibo) {
                    $arrRecibos[$i] = [
                        "empresaNit" => $arConfiguracion->getNit() . "-" . $arConfiguracion->getDigitoVerificacion(),
                        "empresaNombre" => $arConfiguracion->getNombre(),
                        "empresaDireccion" => $arConfiguracion->getDireccion(),
                        "empresaTelefono" => $arConfiguracion->getTelefono(),
                        "codigoGuiaPk" => $arGuia['codigoGuiaPk'],
                        "numero" => $arGuia['numero'],
                        "codigoBarras" => "*" . $arGuia['codigoGuiaPk'] . "*",
                        "numeroFactura" => $numeroFactura,
                        "tituloFactura" => $tituloFactura,
                        "resolucionFactura" => $resolucionFactura,
                        "codigoFacturaTipoFk" => $arGuia['codigoFacturaTipoFk'],
                        "factura" => $arGuia['factura'],
                        "guiaTipoNombre" => $arGuia['guiaTipoNombre'],
                        "fechaIngreso" => $arGuia['fechaIngreso'],
                        "ciudadOrigenNombre" => $arGuia['ciudadOrigenNombre'],
                        "ciudadDestinoNombre" => $arGuia['ciudadDestinoNombre'],
                        "nombreDestinatario" => $arGuia['nombreDestinatario'],
                        "telefonoDestinatario" => $arGuia['telefonoDestinatario'],
                        "direccionDestinatario" => $arGuia['direccionDestinatario'],
                        "clienteNombre" => $arGuia['clienteNombre'],
                        "clienteDireccion" => $arGuia['clienteDireccion'],
                        "clienteTelefono" => $arGuia['clienteTelefono'],
                        "documentoCliente" => $arGuia['documentoCliente'],
                        "vrDeclara" => $arGuia['vrDeclara'],
                        "vrFlete" => $arGuia['vrFlete'],
                        "vrManejo" => $arGuia['vrManejo'],
                        "vrTotal" => $arGuia['vrFlete'] + $arGuia['vrManejo'],
                        "vrCobroEntrega" => $arGuia['vrCobroEntrega'],
                        "vrAbono" => $arGuia['vrAbono'],
                        "vrFleteFactura" => $fleteFactura,
                        "vrManejoFactura" => $manejoFactura,
                        "vrTotalFactura" => $totalFactura,
                        "unidades" => $arGuia['unidades'],
                        "pesoReal" => $arGuia['pesoReal'],
                        "vrFleteRecibo" => $arRecibo['vrFlete'],
                        "vrManejoRecibo" => $arRecibo['vrManejo'],
                        "vrTotalRecibo" => $arRecibo['vrTotal']
                    ];
                    $i++;
                }
                return $arrRecibos;
            } else {
                return [
                    "error" => "No se encontraron resultados"
                ];
            }
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }

}