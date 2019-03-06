<?php

namespace App\Repository\General;

use App\Entity\General\GenTareaPrioridad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GenTareaPrioridadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenTareaPrioridad::class);
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo(){
        $session = new Session();
        $array = [
            'class' => GenTareaPrioridad::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('tp')
                    ->orderBy('tp.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroGenTareaPrioridad')) {
            $array['data'] = $this->getEntityManager()->getReference(GenTareaPrioridad::class, $session->get('filtroGenTareaPrioridad'));
        }
        return $array;
    }
}
