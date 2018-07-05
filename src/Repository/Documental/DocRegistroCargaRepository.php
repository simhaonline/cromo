<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocRegistroCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocRegistroCargaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocRegistroCarga::class);
    }
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocRegistroCarga::class, 'rc')
            ->select('rc.codigoRegistroCargaPk')
            ->addSelect('rc.identificador')
            ->where('rc.codigoRegistroCargaPk <> 0');
        return $queryBuilder;
    }
}