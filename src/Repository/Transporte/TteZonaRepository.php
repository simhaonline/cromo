<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteZona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TteZonaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteZona::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteZona','z')
            ->select('z.codigoZonaPk AS ID')
            ->addSelect('z.nombre AS NOMBRE');
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
            'class' => TteZona::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteDespachoGuiaCodigoZona')) {
            $array['data'] = $this->getEntityManager()->getReference(TteZona::class, $session->get('filtroTteDespachoGuiaCodigoZona'));
        }
        return $array;
    }
}