<?php

namespace App\Repository\Tesoreria;

use App\Entity\Tesoreria\TesCuentaPagarTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TesCuentaPagarTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCuentaPagarTipo::class);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TesCuentaPagarTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cp')
                    ->orderBy('cp.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTesCuentaPagarTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TesCuentaPagarTipo::class, $session->get('filtroTesCuentaPagarTipo'));
        }
        return $array;
    }

    public function selectCodigoNombre()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()->from(TesCuentaPagarTipo::class, 'cpt');
        $qb->select('cpt.codigoCuentaPagarTipoPk')
            ->addSelect('cpt.nombre');
        return $qb->getQuery()->getResult();
    }

}
