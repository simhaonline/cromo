<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteClienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCliente::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteCliente','c')
            ->select('c.codigoClientePk AS ID')
            ->addSelect('c.nombreCorto AS NOMBRE')
            ->addSelect('c.numeroIdentificacion AS NIT')
            ->addSelect('c.direccion AS DIRECCION')
            ->addSelect('c.telefono AS TELEFONO')
            ->addSelect('c.plazoPago AS PLAZO_PAGO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}