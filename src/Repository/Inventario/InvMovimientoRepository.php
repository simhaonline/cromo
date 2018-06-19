<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMovimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;

class InvMovimientoRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvMovimiento::class);
    }

    /**
     * @param $arrSeleccionados array
     * @return string
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if (count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoItem) {
                $arItem = $this->_em->getRepository($this->_entityName)->find($codigoItem);
                if ($arItem) {
                    $this->_em->remove($arItem);
                }
            }
        }
        return $respuesta;
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ii.codigoItemPk AS ID')
            ->addSelect("ii.nombre AS NOMBRE")
            ->addSelect("ii.cantidadExistencia AS EXISTENCIAS")
            ->addSelect("ii.afectaInventario AS A_I")
            ->addSelect("ii.stockMinimo AS STOCK_MINIMO")
            ->addSelect("ii.stockMaximo AS STOCK_MAXIMO")
            ->addSelect("ii.vrPrecioPredeterminado AS PRECIO_PREDETERMINADO")
            ->from("App:Inventario\InvItem", "ii")
            ->where('ii.codigoItemPk <> 0');
//        if ($nombre != '') {
//            $qb->andWhere("ii.nombre LIKE '%{$nombre}%'");
//        }
//        if ($codigoBarras != '') {
//            $qb->andWhere("ii.codigoBarras = {$codigoBarras}");
//        }
        $qb->orderBy('ii.codigoItemPk', 'ASC');
        $dql = $this->getEntityManager()->createQuery($qb->getDQL());
        return $dql->execute();
    }

    public function listarItems($nombreItem = '', $codigoItem = '')
    {
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvItem', 'ii')
            ->select('ii.codigoItemPk')
            ->addSelect('ii.nombre')
            ->addSelect('ii.cantidadExistencia')
            ->addSelect('ii.cantidadOrdenCompra')
            ->addSelect('ii.cantidadSolicitud')
            ->addSelect('ii.stockMinimo')
            ->addSelect('ii.stockMaximo')
            ->where('ii.codigoItemPk <> 0');
        if ($codigoItem != '') {
            $qb->andWhere("ii.codigoItemPk = {$codigoItem}");
        }
        if ($nombreItem != '') {
            $qb->andWhere("ii.nombre LIKE '%{$nombreItem}%' ");
        }
        $qb->orderBy('ii.codigoItemPk', 'ASC');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
}