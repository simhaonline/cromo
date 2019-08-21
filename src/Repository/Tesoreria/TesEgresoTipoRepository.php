<?php

namespace App\Repository\Tesoreria;

use App\Entity\Tesoreria\TesEgresoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TesEgresoTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesEgresoTipo::class);
    }


    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TesEgresoTipo::class,
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
        if ($session->get('filtroComEgresoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TesEgresoTipo::class, $session->get('filtroComEgresoTipo'));
        }
        return $array;
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesEgresoTipo::class, 'et')
            ->select('et.codigoEgresoTipoPk as ID')
            ->addSelect('et.nombre')
            ->where('et.codigoEgresoTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
