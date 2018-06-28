<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvPrecio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvPrecioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPrecio::class);
    }

    public function lista(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from(InvPrecio::class,'p')
//            ->join('p.terceroRel', 't')
            ->select('p.codigoPrecioPk')
            ->addSelect('p.nombre')
            ->addSelect('p.fechaVence')
//            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->where('p.codigoPrecioPk <> 0')
            ->orderBy('p.codigoPrecioPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();

    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Inventario\InvPrecio','p')
            ->select('p.codigoPrecioPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}