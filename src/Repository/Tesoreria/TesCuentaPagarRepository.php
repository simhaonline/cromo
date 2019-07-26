<?php

namespace App\Repository\Tesoreria;

use App\Entity\Tesoreria\TesCuentaPagar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesCuentaPagarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCuentaPagar::class);
    }

    public function pendiente()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesCuentaPagar::class, 'cp')
            ->select('cp.codigoCuentaPagarPk')
            ->addSelect('cp.numeroDocumento')
            ->addSelect('cpt.nombre AS tipo')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('cp.fecha')
            ->addSelect('cp.fechaVence')
            ->addSelect('cp.vrAbono')
            ->addSelect('cp.vrSaldo')
            ->leftJoin('cp.terceroRel', 't')
            ->leftJoin('cp.cuentaPagarTipoRel', 'cpt')
            ->where('cp.vrSaldo > 0');
        if ($session->get('filtroTesCuentaPagarCodigoPendiente') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarPk = '" . $session->get('filtroTesCuentaPagarCodigoPendiente') . "'");
        }
        if ($session->get('filtroTesCuentaPagarTipo') != "") {
            $queryBuilder->andWhere("cp.codigoCuentaPagarTipoFk = '" . $session->get('filtroTesCuentaPagarTipo') . "'");
        }
        if ($session->get('filtroGenBanco') != "") {
            $queryBuilder->andWhere("cp.codigoBancoFk = '" . $session->get('filtroGenBanco') . "'");
        }
        if ($session->get('filtroTesFechaDesde') != null) {
            $queryBuilder->andWhere("cp.fecha >= '{$session->get('filtroTesFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTesFechaHasta') != null) {
            $queryBuilder->andWhere("cp.fecha <= '{$session->get('filtroTesFechaHasta')} 23:59:59'");
        }
        return $queryBuilder;
    }

}
