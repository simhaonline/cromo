<?php

namespace App\Repository\General;

use App\Entity\General\GenAsesor;
use App\Entity\General\GenIdentificacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenAsesorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenAsesor::class);
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:General\GenAsesor',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                    ->orderBy('a.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroGenAsesor')) {
            $array['data'] = $this->getEntityManager()->getReference(GenAsesor::class, $session->get('filtroGenAsesor'));
        }
        return $array;
    }

    public function apiWindowsLista($raw) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(GenAsesor::class, 'a')
            ->select('a.codigoAsesorPk')
            ->addSelect('a.nombre');
        $ar = $queryBuilder->getQuery()->getResult();
        return $ar;
    }
}