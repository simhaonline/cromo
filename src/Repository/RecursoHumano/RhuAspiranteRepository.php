<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAspirante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAspiranteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAspirante::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAspirante::class, 'a')
            ->select('a.codigoAspirantePk')
            ->addSelect('a.numeroIdentificacion')
            ->addSelect('a.nombreCorto')
            ->addSelect('a.telefono')
            ->addSelect('a.celular')
            ->addSelect('a.correo')
            ->addSelect('a.direccion');

        if ($session->get('RhuAspirante_codigoAspirantePk')) {
            $queryBuilder->andWhere("a.codigoAspirantePk = '{$session->get('RhuAspirante_codigoAspirantePk')}'");
        }
        if ($session->get('RhuAspirante_nombreCorto')) {
            $queryBuilder->andWhere("a.nombreCorto like '{$session->get('RhuAspirante_nombreCorto')}'");
        }

        switch ($session->get('RhuAspirante_estadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("doc.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("doc.estadoAutorizado = 1");
                break;
        }

        switch ($session->get('RhuAspirante_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("doc.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("doc.estadoAprobado = 1");
                break;
        }

        switch ($session->get('RhuAspirante_estadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("doc.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("doc.estadoAnulado = 1");
                break;
        }
        
        return $queryBuilder;
    }
    
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuAspirante','a')
            ->select('a');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}