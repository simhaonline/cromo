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

    public function lista()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->leftJoin('cc.clienteRel','cl')
            ->leftJoin('cc.cuentaCobrarTipoRel','cct')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.numeroReferencia')
            ->addSelect('cct.nombre AS tipo')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cc.soporte')
            ->addSelect('cl.numeroIdentificacion')
            ->addSelect('cl.nombreCorto')
            ->addSelect('cc.plazo')
            ->addSelect('cc.vrTotal')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldo')
            ->where('cc.codigoCuentaCobrarPk <> 0')
            ->andWhere('cc.vrSaldo > 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'DESC');
        $fecha =  new \DateTime('now');
        if($session->get('filtroCarNumeroReferencia') != ''){
            $queryBuilder->andWhere("cc.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
        }
        if($session->get('filtroCarCuentaCobrarNumero') != ''){
            $queryBuilder->andWhere("cc.numeroDocumento = {$session->get('filtroCarCuentaCobrarNumero')}");
        }
        if($session->get('filtroCarCodigoCliente')){
            $queryBuilder->andWhere("cc.codigoClienteFk = {$session->get('filtroCarCodigoCliente')}");
        }
        if($session->get('filtroFecha') == true){
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("cc.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("cc.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("cc.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("cc.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        return $queryBuilder;
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

    public function prueba($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCuentaCobrar::class, 'cc')
            ->select('cc.codigoCuentaCobrarPk')
            ->addSelect('cl.codigoClientePk')
            ->addSelect('cct.codigoCuentaCobrarTipoPk')
            ->addSelect('cc.numeroDocumento')
            ->addSelect('cc.fecha')
            ->addSelect('cc.fechaVence')
            ->addSelect('cc.plazo')
            ->addSelect('cc.vrAbono')
            ->addSelect('cc.vrSaldo')
            ->addSelect('cc.vrSaldoOperado')
            ->addSelect('cc.soporte')
            ->where(TO_DAYS(NOW()) - TO_DAYS('cc.fechaVence AS diasVenciada'
                ))
            ->addSelect(CASE_WHEN(TO_DAYS(NOW()) - TO_DAYS('cc.fechaVence') < 1))
            ->join('cc.clienteRel','cl')
            ->join('cc.cuentaCobrarTipoRel', 'cct')
            ->where('cc.vrSaldo > 0')
            ->orderBy('cc.codigoCuentaCobrarPk', 'ASC');

        return $queryBuilder;
    }


}