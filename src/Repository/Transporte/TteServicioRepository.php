<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteServicioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteServicio::class);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo(){
        $session = new Session();
        $array = [
            'class' => TteServicio::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteGuiaCodigoServicio')) {
            $array['data'] = $this->getEntityManager()->getReference(TteServicio::class, $session->get('filtroTteGuiaCodigoServicio'));
        }
        return $array;
    }


    public function apiWindowsLista($raw) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteServicio::class, 's')
            ->select('s.codigoServicioPk')
            ->addSelect('s.nombre');
        $arServicio = $queryBuilder->getQuery()->getResult();
        return $arServicio;
    }
}