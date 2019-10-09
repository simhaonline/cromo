<?php

namespace App\Repository\Tesoreria;

use App\Entity\Tesoreria\TesMovimientoClase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TesMovimientoClaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesMovimientoClase::class);
    }


    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TesMovimientoClase::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroComMovimientoClase')) {
            $array['data'] = $this->getEntityManager()->getReference(TesMovimientoClase::class, $session->get('filtroComMovimientoClase'));
        }
        return $array;
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesMovimientoClase::class, 'et')
            ->select('et.codigoMovimientoClasePk as ID')
            ->addSelect('et.nombre')
            ->where('et.codigoMovimientoClasePk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
