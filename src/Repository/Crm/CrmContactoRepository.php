<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmContacto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CrmContactoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CrmContacto::class);
    }
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmContacto::class, 'c')
            ->select('c.codigoContactoPk')
            ->addSelect('c.numeroIdentificacion ')
            ->addSelect('c.nombreCorto ')
            ->addSelect('c.direccion ')
            ->addSelect('c.telefono ')
            ->addSelect('c.saludo ')
            ->addSelect('c.correo ')
            ->addSelect('c.cargo ')
            ->addSelect('c.especialidad ')
            ->addSelect('c.horarioVisita ')
            ->addSelect('c.secretaria')
            ->addSelect('c.codigoClienteFk')
            ->addSelect('cli.nombreCorto as cliente')
            ->leftJoin('c.clienteRel', 'cli');
        if ($session->get('filtroContactoCodigoCliente') != '') {
            $queryBuilder->andWhere("c.codigoClienteFk  = '{$session->get('filtroContactoCodigoCliente')}' ");
        }


        return $queryBuilder;
    }
}
