<?php

namespace App\Repository\Cartera;


use App\Entity\Cartera\CarCuentaCobrar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CarCuentaCobrarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarCuentaCobrar::class);
    }

    public function cuentaCobrar()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->where('cc.vrSaldo <> 0')
            ->andWhere('cc.operacion = 1')
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    public function cuentasCobrar1($codigCliente = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT cc FROM BrasaCarteraBundle:CarCuentaCobrar cc where cc.saldo <> 0 AND cc.operacion = 1 AND cc.codigoClienteFk = {$codigCliente}";
        $query = $em->createQuery($dql);
        $arCuentasCobro = $query->getResult();

        return $arCuentasCobro;

    }

}