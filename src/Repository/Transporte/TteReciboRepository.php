<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCliente;
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

}