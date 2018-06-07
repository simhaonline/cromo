<?php

namespace App\Repository\General;


use App\Entity\General\GenDepartamento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GenDepartamentoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenDepartamento::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:General\GenDepartamento','d')
            ->select('d.codigoDepartamentoPk AS ID')
            ->addSelect('d.nombre AS NOMBRE')
            ->addSelect('d.codigoDane AS CODIGO_DANE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}