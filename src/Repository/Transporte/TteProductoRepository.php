<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteProducto;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TteProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteProducto::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteProducto','p')
            ->select('p.codigoProductoPk AS ID')
            ->addSelect('p.nombre AS NOMBRE')
            ->addSelect('p.orden AS ORDEN');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function apiWindowsLista($raw) {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteProducto::class, 'p')
            ->select('p.codigoProductoPk')
            ->addSelect('p.nombre')
            ->orderBy('p.orden');
        $arProducto = $queryBuilder->getQuery()->getResult();
        return $arProducto;
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

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteProducto::class, 'p')
            ->select('p.codigoProductoPk')
            ->addSelect('p.nombre')
            ->addSelect('p.codigoTransporte')
            ->addSelect('p.orden');

        if ($codigo) {
            $queryBuilder->andWhere("p.codigoProductoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("p.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('p.codigoProductoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteProducto::class)->find($codigo);
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