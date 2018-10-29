<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodegaUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvBodegaUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvBodegaUsuario::class);
    }

    public function camposPredeterminados()
    {
        return $this->_em->createQueryBuilder()->from(InvBodegaUsuario::class, 'bd')
            ->select('bd.codigoBodegaUsuarioPk AS ID')
            ->addSelect('bd.codigoBodegaFk')
            ->addSelect('bd.usuario')
            ->where('bd.codigoBodegaUsuarioPk IS NOT NULL');
    }
}