<?php

namespace App\Repository\General;

use App\Entity\General\GenCalidadFormato;
use App\Entity\General\GenCubo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\Session;

class GenCalidadFormatoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenCalidadFormato::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $nombre = null;
        if ($filtros) {
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(GenCalidadFormato::class, 'cf')
            ->select('cf.codigoFormatoPk')
            ->addSelect('cf.nombre')
            ->addSelect('cf.fecha')
            ->addSelect('cf.version')
            ->addSelect('cf.codigo')
            ->addSelect('cf.codigoModeloFk')
            ->where('cf.codigoFormatoPk IS NOT NULL')
            ->orderBy('cf.codigoFormatoPk', 'DESC');
        if ($nombre) {
            $queryBuilder->andWhere("cf.nombre LIKE '%{$nombre}%' ");
        }

        $queryBuilder->addOrderBy('cf.codigoFormatoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

}