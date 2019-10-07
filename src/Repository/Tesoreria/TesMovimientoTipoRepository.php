<?php

namespace App\Repository\Tesoreria;

use App\Entity\Tesoreria\TesMovimientoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TesMovimientoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesMovimientoTipo::class);
    }


    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TesMovimientoTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroComMovimientoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TesMovimientoTipo::class, $session->get('filtroComMovimientoTipo'));
        }
        return $array;
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesMovimientoTipo::class, 'et')
            ->select('et.codigoMovimientoTipoPk as ID')
            ->addSelect('et.nombre')
            ->where('et.codigoMovimientoTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
