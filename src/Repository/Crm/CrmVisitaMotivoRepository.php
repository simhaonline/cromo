<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmVisitaMotivo;
use App\Entity\Crm\CrmVisitaTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class CrmVisitaMotivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrmVisitaMotivo::class);
    }


    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmVisitaMotivo::class, 'vm')
            ->select('vm.codigoVisitaMotivoPk')
            ->addSelect('vm.nombre');

        if ($codigo) {
            $queryBuilder->andWhere("vm.codigoVisitaMotivoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("vm.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('vm.codigoVisitaMotivoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(CrmVisitaMotivo::class)->find($codigo);
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
