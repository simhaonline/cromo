<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteFrecuencia;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteFrecuenciaRepository extends ServiceEntityRepository
{
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $nombre = null;
        if ($filtros) {
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFrecuencia::class, 'f')
            ->select('f.codigoFrecuenciaPk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('f.lunes')
            ->addSelect('f.martes')
            ->addSelect('f.miercoles')
            ->addSelect('f.jueves')
            ->addSelect('f.viernes')
            ->addSelect('f.sabado')
            ->addSelect('f.domingo')
            ->leftJoin('f.ciudadOrigenRel', 'co')
            ->leftJoin('f.ciudadDestinoRel', 'cd');

//        if ($nombre) {
//            $queryBuilder->andWhere("c.nombre like '%{$nombre}%' ");
//        }

        $queryBuilder->orderBy('f.codigoFrecuenciaPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteFrecuencia::class);
    }
}