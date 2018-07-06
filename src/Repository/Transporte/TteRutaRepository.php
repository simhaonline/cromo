<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRuta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TteRutaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRuta::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteRuta','r')
            ->select('r.codigoRutaPk AS ID')
            ->addSelect('r.nombre AS NOMBRE');
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
            'class' => TteRuta::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->orderBy('r.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteDespachoGuiaCodigoRuta')) {
            $array['data'] = $this->getEntityManager()->getReference(TteRuta::class, $session->get('filtroTteDespachoGuiaCodigoRuta'));
        }
        return $array;
    }
}