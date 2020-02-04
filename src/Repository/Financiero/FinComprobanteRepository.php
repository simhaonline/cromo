<?php

namespace App\Repository\Financiero;

use App\Entity\Financiero\FinComprobante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class FinComprobanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinComprobante::class);
    }


    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;
        $consecutivo = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $consecutivo = $filtros['consecutivo'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(FinComprobante::class, 'fm')
            ->select('fm.codigoComprobantePk')
            ->addSelect('fm.nombre')
            ->addSelect('fm.consecutivo');

        if ($codigo) {
            $queryBuilder->andWhere("fm.codigoComprobantePk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("fm.nombre LIKE '%{$nombre}%'");
        }

        if($consecutivo){
            $queryBuilder->andWhere("fm.consecutivo LIKE '%{$consecutivo}%'");
        }



        $queryBuilder->addOrderBy('fm.codigoComprobantePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(FinComprobante::class)->find($codigo);
                    if ($arRegistro) {
                        $em->remove($arRegistro);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }

}
