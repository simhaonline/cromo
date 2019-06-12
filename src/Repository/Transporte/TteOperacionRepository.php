<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteOperacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteOperacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteOperacion::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteOperacion','op')
            ->select('op.codigoOperacionPk AS ID')
            ->addSelect('op.nombre AS NOMBRE');
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
            'class' => TteOperacion::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('o')
                    ->orderBy('o.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteOperacion')) {
            $array['data'] = $this->getEntityManager()->getReference(TteOperacion::class, $session->get('filtroTteOperacion'));
        }
        return $array;
    }


    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TteOperacion::class,'o')
            ->select('o');
        if ($session->get('filtroTteOperacionCodigo') != '') {
            $queryBuilder->andWhere("o.codigoOperacionPk = '{$session->get('filtroTteOperacionCodigo')}'");
        }
        if ($session->get('filtroTteOperacionNombre') != '') {
            $queryBuilder->andWhere("o.nombre LIKE '%{$session->get('filtroTteOperacionNombre')}%'");
        }
        return $queryBuilder;

    }
}