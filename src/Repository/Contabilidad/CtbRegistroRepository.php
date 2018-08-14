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