<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteAuxiliar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteAuxiliarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteAuxiliar::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteAuxiliar','au')
            ->select('au.codigoAuxiliarPk AS ID')
            ->addSelect('au.nombreCorto AS NOMBRE_COMPLETO')
            ->addSelect('au.numeroIdentificacion AS IDENTIFICACION');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoAxuiliar = null;
        $numeroIdentificacion = null;
        $nombre = null;

        if ($filtros) {
            $codigoAxuiliar = $filtros['codigoAxuiliar'] ?? null;
            $numeroIdentificacion = $filtros['numeroIdentificacion'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteAuxiliar::class, 'aux')
            ->select('aux.codigoAuxiliarPk')
            ->addSelect('aux.nombreCorto')
            ->addSelect('aux.numeroIdentificacion')
            ->where('aux.codigoAuxiliarPk <> 0');

        if ($numeroIdentificacion) {
            $queryBuilder->andWhere("aux.numeroIdentificacion = {$numeroIdentificacion}");
        }
        if ($codigoAxuiliar) {
            $queryBuilder->andWhere("aux.codigoAuxiliarPk = {$codigoAxuiliar}");
        }
        if ($nombre) {
            $queryBuilder->andWhere("aux.nombreCorto LIKE '%{$nombre}%'");
        };
        $queryBuilder->addOrderBy('aux.codigoAuxiliarPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(TteAuxiliar::class)->find($codigo);
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
}