<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvCostoTipo;
use App\Entity\Inventario\InvImportacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvCostoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvCostoTipo::class);
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvCostoTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ct')
                    ->orderBy('ct.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvCostoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvCostoTipo::class, $session->get('filtroInvCostoTipo'));
        }
        return $array;
    }

}