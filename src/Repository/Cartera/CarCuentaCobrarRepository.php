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

    public function lista(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc');
        $qb->select('cc.codigoCuentaCobrarPk')
            ->leftJoin('cc.clienteRel','cl')
            ->leftJoin('cc.cuentaCobrarTipoRel','cct')
            ->addSelect('cct.nombre AS tipo')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cc.plazo')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.soporte')
            ->addSelect('cl.nombreCorto')
            ->addSelect('cl.numeroIdentificacion')
            ->where('cc.codigoCuentaCobrarPk <> 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'DESC');
        if($session->get('filtroCarCuentaCobrarNumero') != ''){
            $qb->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if($session->get('filtroCarCodigoCliente')){
            $qb->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        $query = $qb->getDQL();
        $query = $em->createQuery($query);

        return $query->execute();
    }

    public function cuentasCobrar($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre')
            ->join('cc.clienteRel','cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo <> 0')
            ->andWhere('cc.operacion = 1')
            ->andWhere('cc.codigoClienteFk = ' . $codigoCliente)
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

    public function cuentasCobrarAplicar($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.plazo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cct.nombre')
            ->join('cc.clienteRel','cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo > 0')
            ->andWhere('cc.operacion = -1')
            ->andWhere('cc.codigoClienteFk = ' . $codigoCliente)
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }

}