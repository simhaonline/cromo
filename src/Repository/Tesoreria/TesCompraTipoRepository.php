<?php

namespace App\Repository\Tesoreria;

use App\Entity\Tesoreria\TesCompraTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class TesCompraTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesCompraTipo::class);
    }


    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TesCompraTipo::class,
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
        if ($session->get('filtroComCompraTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TesCompraTipo::class, $session->get('filtroComCompraTipo'));
        }
        return $array;
    }

    public function camposPredeterminados()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(TesCompraTipo::class, 'et')
            ->select('et.codigoCompraTipoPk as ID')
            ->addSelect('et.nombre')
            ->where('et.codigoCompraTipoPk IS NOT NULL');
        return $queryBuilder->getQuery()->execute();
    }
}
