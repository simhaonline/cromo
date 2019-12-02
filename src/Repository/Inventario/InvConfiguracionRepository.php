<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvConfiguracion;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvConfiguracionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function liquidarMovimiento()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvConfiguracion::class, 'c')
            ->select('c.vrBaseRetencionIvaVenta')
            ->addSelect('c.porcentajeRetencionIva')
            ->where('c.codigoConfiguracionPk = 1');

        return $queryBuilder->getQuery()->getSingleResult();
    }

    public function aprobarMovimiento()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvConfiguracion::class, 'c')
            ->select('c.impuestoRecaudo')
            ->where('c.codigoConfiguracionPk = 1');

        return $queryBuilder->getQuery()->getSingleResult();
    }

    public function validarDetalles()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvConfiguracion::class, 'c')
            ->select('c.validarBodegaUsuario')
            ->where('c.codigoConfiguracionPk = 1');
        return $queryBuilder->getQuery()->getSingleResult();
    }
}