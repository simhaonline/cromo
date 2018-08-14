<?php

namespace App\Repository\Contabilidad;

use App\Entity\Contabilidad\CtbRegistro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CtbRegistroRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CtbRegistro::class);
    }

    public function registros(){

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CtbRegistro::class, 'r')
            ->select('r.codigoRegistroPk')
            ->addSelect('r.numero')
            ->addSelect('r.numeroReferencia')
            ->addSelect('r.fecha')
            ->addSelect('r.vrDebito')
            ->addSelect('r.vrCredito')
            ->addSelect('r.vrBase')
            ->addSelect('r.naturaleza')
            ->addSelect('r.codigoCentroCostoFk')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.codigoComprobanteFk')
            ->addSelect('c.nombre')
            ->addSelect('r.descripcion')
            ->addSelect('r.codigoTerceroFk')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.nombreCorto')
            ->leftJoin('r.terceroRel', 't')
            ->leftJoin('r.comprobanteRel', 'c')
            ->where('r.codigoRegistroPk <> 0');
        $fecha =  new \DateTime('now');
        if($session->get('filtroCtbCodigoTercero')){
            $queryBuilder->andWhere("r.codigoTerceroFk = {$session->get('filtroCtbCodigoTercero')}");
        }
        if($session->get('filtroCtbComprobante') != ''){
            $queryBuilder->andWhere("r.codigoComprobanteFk = {$session->get('filtroCtbComprobante')}");
        }
        if($session->get('filtroCtbNumeroDesde') != ''){
            $queryBuilder->andWhere("r.numero >= {$session->get('filtroCtbNumeroDesde')}");
        }
        if($session->get('filtroCtbNumeroHasta') != ''){
            $queryBuilder->andWhere("r.numero <= {$session->get('filtroCtbNumeroHasta')}");
        }
        if($session->get('filtroCtbCuenta') != ''){
            $queryBuilder->andWhere("r.codigoCuentaFk = {$session->get('filtroCtbCuenta')}");
        }
        if($session->get('filtroCtbCentroCosto') != ''){
            $queryBuilder->andWhere("r.codigoCentroCostoFk = {$session->get('filtroCtbCentroCosto')}");
        }
        if($session->get('filtroCtbNumeroReferencia') != ''){
            $queryBuilder->andWhere("r.numeroReferencia = {$session->get('filtroCtbNumeroReferencia')}");
        }
        if($session->get('filtroFecha') == true){
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("r.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("r.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("r.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("r.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
    }

    public function listaIntercambio()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CtbRegistro::class, 'r')
            ->select('r.codigoRegistroPk')
            ->addSelect('r.numero')
            ->addSelect('r.fecha')
            ->addSelect('r.vrDebito')
            ->addSelect('r.vrCredito')
            ->addSelect('r.vrBase')
            ->addSelect('r.naturaleza')
            ->addSelect('r.codigoCentroCostoFk')
            ->addSelect('r.codigoCuentaFk')
            ->addSelect('r.codigoComprobanteFk')
            ->addSelect('r.descripcion')
            ->addSelect('r.codigoTerceroFk')
            ->addSelect('t.numeroIdentificacion')
            ->leftJoin('r.terceroRel', 't')
            ->where('r.estadoIntercambio = 0');
        return $queryBuilder;
    }

    public function aplicarIntercambio()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('UPDATE App\Entity\Contabilidad\CtbRegistro r set r.estadoIntercambio = 1 
                      WHERE r.estadoIntercambio = 0');
        $query->execute();

    }

}