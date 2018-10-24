<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComCuentaPagar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method ComCuentaPagar|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComCuentaPagar|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComCuentaPagar[]    findAll()
 * @method ComCuentaPagar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComCuentaPagarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComCuentaPagar::class);
    }

    public function lista()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(ComCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->leftJoin('cp.proveedorRel', 'p')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->addSelect('cp.numeroReferencia')
            ->addSelect('cpt.nombre AS tipo')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
//            ->addSelect('cp.soporte')
            ->addSelect('p.nombreCorto')
            ->addSelect('p.numeroIdentificacion')
            ->addSelect('p.nombreCorto')
            ->addSelect('cp.plazo')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.vrSaldo')
            ->addSelect('cp.vrSaldoOperado')
            ->where('cp.codigoCuentaPagarPk <> 0')
            ->orderBy('cp.codigoCuentaPagarPk', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroComCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk = '" . $session->get('filtroComCuentaPAgarTipo') . "'");
        }
//        if ($session->get('filtroComNumeroReferencia') != '') {
//            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
//        }
        if ($session->get('filtroComCuentaPagarNumero') != '') {
            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroComCuentaPagarNumero')}");
        }
        if ($session->get('filtroComCodigoProveedor')) {
            $queryBuilder->andWhere("cp.codigoProveedorFk = {$session->get('filtroComCodigoProveedor')}");
        }
//        if ($session->get('filtroCarCuentaCobrarTipo')) {
//            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo') . "'");
//        }
//        if ($session->get('filtroFecha') == true) {
//            if ($session->get('filtroFechaDesde') != null) {
//                $queryBuilder->andWhere("cc.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
//            } else {
//                $queryBuilder->andWhere("cc.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
//            }
//            if ($session->get('filtroFechaHasta') != null) {
//                $queryBuilder->andWhere("cc.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
//            } else {
//                $queryBuilder->andWhere("cc.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
//            }
//        }
        return $queryBuilder;
    }

    public function pendiente()
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(ComCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->leftJoin('cp.proveedorRel', 'p')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cp.codigoCuentaPagarTipoFk')
            ->addSelect('cp.numeroReferencia')
            ->addSelect('cpt.nombre AS tipo')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
//            ->addSelect('cp.soporte')
            ->addSelect('p.nombreCorto')
            ->addSelect('p.numeroIdentificacion')
            ->addSelect('p.nombreCorto')
            ->addSelect('cp.plazo')
            ->addSelect('cp.vrTotal')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.vrSaldo')
            ->addSelect('cp.vrSaldoOperado')
            ->where('cp.codigoCuentaPagarPk <> 0')
            ->andWhere('cp.vrSaldo > 0')
            ->orderBy('cp.codigoCuentaPagarPk', 'DESC');
        $fecha = new \DateTime('now');
        if ($session->get('filtroComCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk = '" . $session->get('filtroComCuentaPAgarTipo') . "'");
        }
//        if ($session->get('filtroComNumeroReferencia') != '') {
//            $queryBuilder->andWhere("cp.numeroReferencia = {$session->get('filtroCarNumeroReferencia')}");
//        }
        if ($session->get('filtroComCuentaPagarNumero') != '') {
            $queryBuilder->andWhere("cp.numeroDocumento = {$session->get('filtroComCuentaPagarNumero')}");
        }
        if ($session->get('filtroComCodigoProveedor')) {
            $queryBuilder->andWhere("cp.codigoProveedorFk = {$session->get('filtroComCodigoProveedor')}");
        }
//        if ($session->get('filtroCarCuentaCobrarTipo')) {
//            $queryBuilder->andWhere("cc.codigoCuentaCobrarTipoFk = '" . $session->get('filtroCarCuentaCobrarTipo') . "'");
//        }
//        if ($session->get('filtroFecha') == true) {
//            if ($session->get('filtroFechaDesde') != null) {
//                $queryBuilder->andWhere("cc.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
//            } else {
//                $queryBuilder->andWhere("cc.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
//            }
//            if ($session->get('filtroFechaHasta') != null) {
//                $queryBuilder->andWhere("cc.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
//            } else {
//                $queryBuilder->andWhere("cc.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
//            }
//        }
        return $queryBuilder;
    }

}
