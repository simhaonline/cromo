<?php

namespace App\Repository\Inventario;


use App\Entity\Financiero\FinContacto;
use App\Entity\Inventario\InvContacto;
use App\Entity\Inventario\InvCotizacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvContactoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvContacto::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvContacto::class, 'ct')
            ->select('ct.codigoContactoPk')
            ->addSelect('ct.nombreCorto')
            ->addSelect('ct.numeroIdentificacion')
            ->addSelect('ct.cargo')
            ->addSelect('ct.area')
            ->addSelect('ct.telefono')
            ->addSelect('ct.celular')
            ->addSelect('t.nombreCorto AS tercero')
            ->leftJoin('ct.terceroRel', 't')
            ->where('ct.codigoContactoPk <> 0');
        return $queryBuilder;
    }

    public function listaTercero($codigoTercero)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvContacto::class,'c')
            ->select('c.codigoContactoPk')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.telefono')
            ->addSelect('c.celular')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->where("c.codigoTerceroFk = $codigoTercero")
            ->join('c.terceroRel', 't');
        if($session->get('filtroInvBuscarContactoNombre')){
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroInvBuscarContactoNombre')}%'");
            $session->set('filtroInvBuscarContactoNombre',null);
        }
        return $queryBuilder;
    }
}
