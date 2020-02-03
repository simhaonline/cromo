<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRedespacho;
use App\Entity\Transporte\TteRedespachoMotivo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRedespachoMotivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteRedespachoMotivo::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;


        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteRedespachoMotivo::class, 'r')
            ->select('r.codigoRedespachoMotivoPk')
            ->addSelect('r.nombre');;

        if ($codigo) {
            $queryBuilder->andWhere("r.codigoRedespachoMotivoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("r.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('r.codigoRedespachoMotivoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteRedespachoMotivo::class)->find($codigo);
                if ($arRegistro) {
                    $em->remove($arRegistro);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $e) {
                Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }

}

