<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvPrecio;
use App\Entity\Inventario\InvSucursal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InvSucursalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSucursal::class);
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
            ->from(InvSucursal::class,'s')
            ->select('s.codigoSucursalPk AS ID')
            ->addSelect('s.contacto')
            ->addSelect('s.direccion')
            ->addSelect('t.nombreCorto')
            ->leftJoin('s.terceroRel','t');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}