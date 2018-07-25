<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvConfiguracion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvConfiguracion::class);
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvConfiguracion','ic')
            ->select('ic.codigoConfiguracionPk as ID')
            ->addSelect('ic.codigoFormatoMovimiento')
            ->addSelect('ic.informacionContactoMovimiento')
            ->addSelect('ic.informacionLegalMovimiento')
            ->addSelect('ic.informacionPagoMovimiento')
            ->addSelect('ic.informacionResolucionDianMovimiento');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function liquidarMovimiento(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.vrBaseRetencionFuenteVenta,
        c.vrBaseRetencionIvaVenta, 
        c.porcentajeRetencionFuente, 
        c.porcentajeRetencionIva
        FROM App\Entity\Inventario\InvConfiguracion c 
        WHERE c.codigoConfiguracionPk = :codigoConfiguracion'
        )->setParameter('codigoConfiguracion', 1);
        return $query->getSingleResult();
    }

}