<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteConductor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteConductorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteConductor::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteConductor::class, 'c')
            ->select('c.codigoConductorPk')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.telefono')
            ->addSelect('c.movil')
            ->where('c.codigoConductorPk IS NOT NULL')
            ->orderBy('c.codigoConductorPk');

        if ($session->get('TteConductor_nombreCorto') != "") {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%" . $session->get('TteConductor_nombreCorto') . "%'");
        }
        if ($session->get('TteConductor_numeroIdentificacion') != '') {
            $queryBuilder->andWhere("c.numeroIdentificacion = '{$session->get('TteConductor_numeroIdentificacion')}'");
        }

        return $queryBuilder;
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
        cd.codigoInterface AS codigoCiudad,
        i.codigoInterface AS tipoIdentificacion
        FROM App\Entity\Transporte\TteConductor c   
        LEFT JOIN c.identificacionRel i        
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
        c.numeroIdentificacion,
        i.codigoInterface AS tipoIdentificacion
        FROM App\Entity\Transporte\TteConductor c
        LEFT JOIN c.identificacionRel i          
        WHERE c.codigoConductorPk = :codigoConductor'
        )->setParameter('codigoConductor', $codigoConductor);
        $arConductor =  $query->getSingleResult();
        return $arConductor;

    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo(){
        $session = new Session();
        $array = [
            'class' => TteConductor::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');
            },
            'choice_label' => 'nombreCorto',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroTteDespachoCodigoConductor')) {
            $array['data'] = $this->getEntityManager()->getReference(TteConductor::class, $session->get('filtroTteDespachoCodigoConductor'));
        }
        return $array;
    }
}