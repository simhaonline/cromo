<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteNovedadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TteNovedadTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteNovedadTipo::class);
    }
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteNovedadTipo','nt')
            ->select('nt.codigoNovedadTipoPk AS ID')
            ->addSelect('nt.nombre');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Transporte\TteNovedadTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('nt')
                    ->orderBy('nt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroTteCodigoNovedadTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TteNovedadTipo::class, $session->get('filtroTteCodigoNovedadTipo'));
        }
        return $array;
    }
}