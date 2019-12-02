<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAnticipoTipo;
use App\Entity\Cartera\CarReciboTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class CarAnticipoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarAnticipoTipo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Cartera\CarReciboTipo', 'rt')
            ->select('rt.codigoReciboTipoPk AS ID')
            ->addSelect('rt.nombre')
            ->addSelect('rt.orden');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => CarReciboTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroCarReciboCodigoReciboTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(CarReciboTipo::class, $session->get('filtroCarReciboCodigoReciboTipo'));
        }
        return $array;
    }
}