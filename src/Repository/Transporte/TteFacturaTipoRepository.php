<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFacturaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteFacturaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaTipo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteFacturaTipo','ft')
            ->select('ft.codigoFacturaTipoPk AS ID')
            ->addSelect('ft.nombre AS NOMBRE')
            ->addSelect('ft.consecutivo AS CONSECUTIVO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}