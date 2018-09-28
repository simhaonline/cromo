<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvCotizacionTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvCotizacionTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvCotizacionTipo::class);
    }

    public function camposPredeterminados(){
        $qb = $this->getEntityManager()->createQueryBuilder()->from(InvCotizacionTipo::class,'ict')
            ->select('ict.codigoCotizacionTipoPk as ID')
            ->addSelect('ict.nombre AS NOMBRE')
            ->addSelect('ict.consecutivo AS CONSECUTIVO')
            ->orderBy('ict.codigoCotizacionTipoPk','DESC');
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
            'class' => InvCotizacionTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('st')
                    ->orderBy('st.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvCotizacionTipoCodigo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvCotizacionTipo::class, $session->get('filtroInvCotizacionTipoCodigo'));
        }
        return $array;
    }
}