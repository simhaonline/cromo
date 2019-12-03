<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuEmbargoJuzgadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEmbargoJuzgado::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw){
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoEmbargoJuzgado = null;
        $nombre = null;
        if ($filtros) {
            $codigoEmbargoJuzgado = $filtros['codigoEmbargoJuzgado'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargoJuzgado::class,'ej')
            ->select('ej.codigoEmbargoJuzgadoPk')
            ->addSelect('ej.nombre')
            ->addSelect('ej.cuenta')
            ->addSelect('ej.oficina')
            ->where('ej.codigoEmbargoJuzgadoPk IS NOT NULL');


        if($codigoEmbargoJuzgado){
            $queryBuilder->andWhere("ej.codigoEmbargoJuzgadoPk LIKE '%{$codigoEmbargoJuzgado}%' ");
        }
        if($nombre){
            $queryBuilder->andWhere("ej.nombre LIKE '%{$nombre}%' ");
        }
        $queryBuilder->addOrderBy('ej.codigoEmbargoJuzgadoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuEmbargoJuzgado::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }

    public function camposPredeterminados(){
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmbargoJuzgado::class,'ej')
            ->select('ej.codigoEmbargoJuzgadoPk AS ID')
            ->addSelect('ej.nombre')
            ->addSelect('ej.cuenta')
            ->addSelect('ej.oficina')
            ->where('ej.codigoEmbargoJuzgadoPk IS NOT NULL');
        return $queryBuilder;
    }
}