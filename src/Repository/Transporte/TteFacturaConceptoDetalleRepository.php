<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFacturaConceptoDetalle;
use App\Entity\Transporte\TteFacturaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class TteFacturaConceptoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteFacturaConceptoDetalle::class);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TteFacturaConceptoDetalle::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('fcd')
                    ->orderBy('fcd.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteFacturaCodigoFacturaConceptoDetalle')) {
            $array['data'] = $this->getEntityManager()->getReference(TteFacturaConceptoDetalle::class, $session->get('filtroTteFacturaCodigoFacturaConceptoDetalle'));
        }
        return $array;
    }

}