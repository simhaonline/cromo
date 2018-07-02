<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteConductor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteConductorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteConductor::class);
    }

    public function listaDql()
    {
        $session = new Session();
        $qb = $this->getEntityManager()->createQueryBuilder()->from(TteConductor::class, 'c')
            ->select('c.codigoConductorPk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.nombreCorto')
            ->where('c.codigoConductorPk <> 0')
            ->orderBy('c.nombreCorto');
        if ($session->get('filtroTteConductorNombre') != '') {
            $qb->andWhere("c.nombreCorto LIKE '%{$session->get('filtroTteConductorNombre')}%'");
        }
        return $qb->getDQL();
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteConductor','c')
            ->select('c.codigoConductorPk AS ID')
            ->addSelect('c.numeroIdentificacion AS NUMERO_IDENTIFICACION')
            ->addSelect('c.nombreCorto AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
    public function dqlRndc($codigoConductor): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        c.codigoIdentificacionFk,
        c.numeroIdentificacion,
        c.nombre1,
        c.apellido1,
        c.apellido2,
        c.telefono,
        c.movil,
        c.direccion, 
        c.fechaVenceLicencia,
        c.numeroLicencia,
        c.categoriaLicencia,
        cd.codigoInterface AS codigoCiudad
        FROM App\Entity\Transporte\TteConductor c          
        LEFT JOIN c.ciudadRel cd 
        WHERE c.codigoConductorPk = :codigoConductor'
        )->setParameter('codigoConductor', $codigoConductor);
        $arConductor =  $query->getSingleResult();
        return $arConductor;

    }

    public function dqlRndcManifiesto($codigoConductor): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        c.codigoIdentificacionFk,
        c.numeroIdentificacion
        FROM App\Entity\Transporte\TteConductor c          
        WHERE c.codigoConductorPk = :codigoConductor'
        )->setParameter('codigoConductor', $codigoConductor);
        $arConductor =  $query->getSingleResult();
        return $arConductor;

    }


}