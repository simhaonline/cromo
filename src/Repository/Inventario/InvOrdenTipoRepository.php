<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvOrdenTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvOrdenTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvOrdenCompraTipo','ot');
        $qb
            ->select('ot.codigoOrdenTipoPk AS ID')
            ->addSelect('ot.nombre AS NOMBRE')
            ->addSelect('ot.consecutivo AS CONSECUTIVO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvOrdenTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ot')
                    ->orderBy('ot.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvOrdenCodigoOrdenTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvOrdenTipo::class, $session->get('filtroInvOrdenCodigoOrdenTipo'));
        }
        return $array;
    }
}