<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuEmbargoJuzgadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEmbargoJuzgado::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista(){
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargoJuzgado::class,'ej')
            ->select('ej.codigoEmbargoJuzgadoPk')
            ->addSelect('ej.nombre')
            ->addSelect('ej.cuenta')
            ->addSelect('ej.oficina')
            ->where('ej.codigoEmbargoJuzgadoPk IS NOT NULL');
        if($session->get('filtroRhuJuzgadoNombre')){
            $queryBuilder->andWhere("ej.nombre LIKE '%{$session->get('filtroRhuJuzgadoNombre')}%' ");
        }
        if($session->get('filtroRhuJuzgadoCodigo')){
            $queryBuilder->andWhere("ej.codigoEmbargoJuzgadoPk LIKE '%{$session->get('filtroRhuJuzgadoCodigo')}%' ");
        }
        return $queryBuilder;
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargoJuzgado::class,'ej')
            ->select('ej.codigoEmbargoJuzgadoPk AS ID')
            ->addSelect('ej.nombre')
            ->addSelect('ej.cuenta')
            ->addSelect('ej.oficina')
            ->where('ej.codigoEmbargoJuzgadoPk IS NOT NULL');
        return $queryBuilder;
    }
}