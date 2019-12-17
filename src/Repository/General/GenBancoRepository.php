<?php

namespace App\Repository\General;

use App\Entity\General\GenBanco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenBancoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenBanco::class);
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => GenBanco::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('g')
                    ->orderBy('g.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroGenBanco')) {
            $array['data'] = $this->getEntityManager()->getReference(GenBanco::class, $session->get('filtroGenBanco'));
        }
        return $array;
    }

    public function lista($raw)
    {
        $filtros = $raw['filtros'] ?? null;
        $codigoBanco = null;
        $nombre = null;
        if ($filtros) {
            $codigoBanco = $filtros['codigoBanco'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenBanco::class, 'b')
            ->select('b.codigoBancoPk')
            ->addSelect('b.nombre');
        if ($codigoBanco) {
            $queryBuilder->andWhere("b.codigoBancoPk = '{$codigoBanco}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("b.nombre like '%{$nombre}%'");
        }
        $queryBuilder->addOrderBy('b.codigoBancoPk', 'DESC');
        return $queryBuilder->getQuery()->getResult();

    }

}