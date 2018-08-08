<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteFacturaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteFacturaTipo','ft')
            ->select('ft.codigoFacturaTipoPk AS ID')
            ->addSelect('ft.nombre AS NOMBRE')
            ->addSelect('ft.consecutivo AS CONSECUTIVO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function controlFactura($fecha)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaTipo::class, 'ft')
            ->select('ft.codigoFacturaTipoPk')
            ->addSelect('ft.nombre');
        $arFacturaTipos = $queryBuilder->getQuery()->execute();
        $pos = 0;
        foreach ($arFacturaTipos as $arFacturaTipo) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
                ->select('MIN(f.numero) as desde')
                ->addSelect('MAX(f.numero) as hasta')
            ->where("f.codigoFacturaTipoFk = '" . $arFacturaTipo['codigoFacturaTipoPk'] . "'")
            ->andWhere("f.fecha >= '$fecha 00:00:00' AND f.fecha <= '$fecha 23:59:59'")
            ->andWhere("f.estadoAprobado = 1");
            $arFactura = $queryBuilder->getQuery()->getSingleResult();
            if($arFactura['desde']) {
                $arFacturaTipos[$pos]['desde'] = $arFactura['desde'];
                $arFacturaTipos[$pos]['hasta'] = $arFactura['hasta'];
            } else {
                $arFacturaTipos[$pos]['desde'] = "Sin registros";
                $arFacturaTipos[$pos]['hasta'] = "Sin registros";
            }
            $pos++;
        }

        return $arFacturaTipos;
    }

}