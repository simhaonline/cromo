<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRutaRecogida;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TteRutaRecogidaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRutaRecogida::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteRutaRecogida','rr')
            ->select('rr.codigoRutaRecogidaPk AS ID')
            ->addSelect('rr.nombre AS NOMBRE');
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
            'class' => TteRutaRecogida::class,
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
        if ($session->get('filtroTteRutaRecogida')) {
            $array['data'] = $this->getEntityManager()->getReference(TteRutaRecogida::class, $session->get('filtroTterutaRecogida'));
        }
        return $array;
    }

}