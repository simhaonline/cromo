<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvRemisionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvRemisionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvRemisionTipo::class);
    }

    public function lista()
    {

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvRemisionTipo::class,'rt')
            ->select('rt.codigoRemisionTipoPk')
        ->addSelect('rt.nombre')
            ->where('rt.codigoRemisionTipoPk <> 0')
            ->orderBy('rt.codigoRemisionTipoPk','DESC');
        return $queryBuilder;
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvRemisionTipo','rt');
        $qb
            ->select('rt.codigoRemisionTipoPk AS ID')
            ->addSelect('rt.nombre AS NOMBRE');
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
            'class' => 'App:Inventario\InvRemisionTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvRemisionTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvRemisionTipo::class, $session->get('filtroInvRemisionTipo'));
        }
        return $array;
    }

}