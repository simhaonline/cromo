<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuSolicitudRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSolicitud::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSolicitud::class, 's')
            ->select('s.codigoSolicitudPk')
            ->addSelect('s.cantidadSolicitada')
            ->addSelect('s.nombre')
            ->addSelect('s.salarioFijo')
            ->addSelect('s.salarioVariable')
            ->addSelect('s.edadMinima')
            ->addSelect('s.edadMaxima');

        if ($session->get('RhuSolicitud_codigoSolicitudPk')) {
            $queryBuilder->andWhere("RhuSolicitud_codigoSolicitudPk = '{$session->get('RhuSolicitud_codigoSolicitudPk')}'");
        }

        if ($session->get('RhuSolicitud_nombre')) {
            $queryBuilder->andWhere("RhuSolicitud_nombre = '{$session->get('RhuSolicitud_nombre')}'");
        }

        return $queryBuilder;
    }
    
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSolicitud','s')
            ->select('s.codigoSolicitudPk AS ID')
            ->addSelect('s.fecha AS FECHA')
            ->addSelect('s.nombre AS NOMBRE')
            ->addSelect('s.cantidadSolicitada AS CANTIDAD')
            ->addSelect('s.estadoAutorizado AS AUTORIZADO')
            ->addSelect('s.estadoCerrado AS CERRADO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}