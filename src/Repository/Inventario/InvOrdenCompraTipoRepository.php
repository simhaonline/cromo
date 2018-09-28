<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvFacturaTipo;
use App\Entity\Inventario\InvOrdenCompraTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvOrdenCompraTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvOrdenCompraTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvOrdenCompraTipo','ioct');
        $qb
            ->select('ioct.codigoOrdenCompraTipoPk AS ID')
            ->addSelect('ioct.nombre AS NOMBRE')
            ->addSelect('ioct.consecutivo AS CONSECUTIVO');
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
            'class' => 'App:Inventario\InvOrdenCompraTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('oct')
                    ->orderBy('oct.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvCodigoOrdenCompraTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvOrdenCompraTipo::class, $session->get('filtroInvCodigoOrdenCompraTipo'));
        }
        return $array;
    }
}