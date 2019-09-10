<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSeleccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuSeleccionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuSeleccion::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSeleccion::class, 's')
            ->select('s.codigoSeleccionPk')
            ->addSelect('s.numeroIdentificacion')
            ->addSelect('s.nombreCorto')
            ->addSelect('s.telefono')
            ->addSelect('s.celular')
            ->addSelect('s.correo')
            ->addSelect('s.direccion');

        if ($session->get('RhuSeleccion_codigoSeleccionPk')) {
            $queryBuilder->andWhere("s.codigoSeleccionPk = '{$session->get('RhuSeleccion_codigoSeleccionPk')}'");
        }

        if ($session->get('RhuSeleccion_nombreCorto')) {
            $queryBuilder->andWhere("s.nombreCorto like '%{$session->get('RhuSeleccion_nombreCorto')}%'");
        }

        switch ($session->get('RhuSeleccion_estadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("s.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAutorizado = 1");
                break;
        }

        switch ($session->get('RhuSeleccion_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("s.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAprobado = 1");
                break;
        }
        return $queryBuilder;

    }
    
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSeleccion','s')
            ->select('s');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}