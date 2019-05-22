<?php


namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuDotacionElemento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuDotacionElementoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuDotacionElemento::class);
    }

    public function lista (){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuDotacionElemento::class, 'e')
            ->select('e.codigoDotacionElementoPk')
            ->addSelect('e.nombre');
        $queryBuilder->orderBy('e.codigoDotacionElementoPk', 'DESC');

        if ($session->get('filtroClave') != '') {
            $queryBuilder->andWhere("e.codigoDotacionElementoPk = '{$session->get('filtroClave')}'");
        }
        if ($session->get('filtroNombre') != '') {
            $queryBuilder->andWhere("e.nombre LIKE '%{$session->get('filtroNombre')}%'");
        }
        return $queryBuilder;
    }

}