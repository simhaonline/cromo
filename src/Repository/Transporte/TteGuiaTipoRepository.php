<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteGuiaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuiaTipo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteGuiaTipo','gt')
            ->select('gt.codigoGuiaTipoPk AS ID')
            ->addSelect('gt.nombre AS NOMBRE')
            ->addSelect('gt.consecutivo AS CONSECUTIVO')
            ->addSelect('gt.exigeNumero AS EXIGE_NUMERO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}