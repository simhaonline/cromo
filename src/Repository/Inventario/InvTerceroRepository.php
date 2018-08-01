<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvTercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvTerceroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvTercero::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvTercero', 'it');
        $qb
            ->select('it.codigoTerceroPk AS ID')
            ->addSelect('it.apellido1 AS APELLIDO1')
            ->addSelect('it.apellido2 AS APELLIDO2')
            ->addSelect('it.nombres AS NOMBRES')
            ->addSelect('it.numeroIdentificacion AS IDENTIFICACION')
            ->addSelect('it.direccion AS DIRECCION')
            ->where("it.codigoTerceroPk <> 0")
            ->orderBy('it.codigoTerceroPk', 'DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @param $tipo  integer
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($tipo)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvTercero::class, 't')
            ->select('t.codigoTerceroPk')
            ->addSelect('t.nombres')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->addSelect('t.direccion')
            ->where('t.codigoTerceroPk <> 0');
        if ($tipo == 1) {
            if ($session->get('filtroInvTerceroCodigo') != '') {
                $queryBuilder->andWhere("t.codigoTerceroPk = {$session->get('filtroInvTerceroCodigo')}");
            }
            if ($session->get('filtroInvTerceroNombre') != '') {
                $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroInvTerceroNombre')}%'");
            }
            if ($session->get('filtroInvTerceroIdentificacion') != '') {
                $queryBuilder->andWhere("t.numeroIdentificacion = {$session->get('filtroInvTerceroIdentificacion')}");
            }
        } elseif ($tipo == 0) {
            if ($session->get('filtroInvBuscarTerceroCodigo') != '') {
                $queryBuilder->andWhere("t.codigoTerceroPk = {$session->get('filtroInvBuscarTerceroCodigo')}");
            }
            if ($session->get('filtroInvBuscarTerceroNombre') != '') {
                $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroInvBuscarTerceroNombre')}%'");
            }
            if ($session->get('filtroInvBuscarTerceroIdentificacion') != '') {
                $queryBuilder->andWhere("t.numeroIdentificacion = {$session->get('filtroInvBuscarTerceroIdentificacion')}");
            }
        }
        return $queryBuilder;
    }
}