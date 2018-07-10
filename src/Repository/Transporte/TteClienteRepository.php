<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

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

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCliente::class, 'tc')
            ->select('tc.codigoClientePk')
            ->addSelect('tc.nombreCorto')
            ->addSelect('tc.numeroIdentificacion')
            ->addSelect('tc.telefono')
            ->addSelect('tc.movil')
            ->addSelect('tc.direccion')
            ->where('tc.codigoClientePk IS NOT NULL')
            ->orderBy('tc.codigoClientePk', 'ASC');

        return $queryBuilder;
    }
}