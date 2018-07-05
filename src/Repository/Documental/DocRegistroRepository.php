<?php

namespace App\Repository\Documental;

use App\Entity\Documental\DocRegistro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class DocRegistroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocRegistro::class);
    }
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(DocRegistro::class, 'r')
            ->select('r.codigoRegistroPk')
            ->where('r.codigoRegistroPk <> 0');
        if ($session->get('filtroDocRegistroIdentificador') != '') {
            $queryBuilder->andWhere("r.identificador = {$session->get('filtroDocRegistroIdentificador')}");
        }
        return $queryBuilder;
    }
}