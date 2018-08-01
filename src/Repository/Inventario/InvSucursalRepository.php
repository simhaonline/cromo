<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvPrecio;
use App\Entity\Inventario\InvSucursal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvSucursalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvSucursal::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSucursal::class,'s')
            ->select('s.codigoSucursalPk')
            ->leftJoin('s.ciudadRel','c')
            ->addSelect('s.direccion')
            ->addSelect('s.contacto')
            ->addSelect('c.nombre AS ciudad')
            ->addSelect('s.nombre')
            ->where("s.codigoTerceroFk ={$session->get('filtroInvBuscarSucursalCodigoTercero')}");
        if($session->get('filtroInvBuscarSucursalDireccion')){
            $queryBuilder->andWhere("s.direccion LIKE '%{$session->get('filtroInvBuscarSucursalDireccion')}%'");
            $session->set('filtroInvBuscarSucursalDireccion',null);
        }
        if($session->get('filtroInvBuscarSucursalContacto')){
            $queryBuilder->andWhere("s.contacto LIKE '%{$session->get('filtroInvBuscarSucursalContacto')}%'");
            $session->set('filtroInvBuscarSucursalContacto',null);
        }
        return $queryBuilder;
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from(InvSucursal::class,'s')
            ->select('s.codigoSucursalPk AS ID')
            ->addSelect('s.nombre')
            ->addSelect('s.contacto')
            ->addSelect('s.direccion')
            ->addSelect('t.nombreCorto')
            ->leftJoin('s.terceroRel','t');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}