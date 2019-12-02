<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvSolicitudTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvSolicitudTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->getEntityManager()->createQueryBuilder()->from('App:Inventario\InvSolicitudTipo','ist')
            ->select('ist.codigoSolicitudTipoPk as ID')
            ->addSelect('ist.nombre AS NOMBRE')
            ->addSelect('ist.consecutivo AS CONSECUTIVO')
            ->orderBy('ist.codigoSolicitudTipoPk','DESC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvSolicitudTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('st')
                    ->orderBy('st.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvCodigoSolicitudTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvSolicitudTipo::class, $session->get('filtroInvCodigoSolicitudTipo'));
        }
        return $array;
    }
}