<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class InvItemRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvItem::class);
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
            ->addSelect("ii.vrCostoPromedio AS C_PROM")
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

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvItem::class, 'i')
            ->select('i.codigoItemPk')
            ->addSelect('i.nombre')
            ->addSelect('i.referencia')
            ->addSelect('i.cantidadExistencia')
            ->addSelect('i.cantidadOrden')
            ->addSelect('i.cantidadSolicitud')
            ->addSelect('i.cantidadRemisionada')
            ->addSelect('i.cantidadPedido')
            ->addSelect('i.cantidadDisponible')
            ->addSelect('i.stockMinimo')
            ->addSelect('i.stockMaximo')
            ->addSelect('i.afectaInventario')
            ->addSelect('i.vrCostoPredeterminado')
            ->addSelect('i.vrPrecioPromedio')
            ->addSelect('m.nombre AS marcaNombre')
            ->addSelect('i.codigoImpuestoIvaVentaFk')
            ->where('i.codigoItemPk <> 0')
            ->leftJoin('i.marcaRel', 'm')
        ->addOrderBy('i.codigoItemPk', 'ASC');
        if($session->get('itemConExistencia') == true){
            $queryBuilder->andWhere("i.cantidadExistencia > 0");
        }
        if($session->get('filtroItemConDisponibilidad') == true){
            $queryBuilder->andWhere("i.cantidadDisponible > 0");
        }
        if ($session->get('filtroInvBucarItemCodigo') != '') {
            $queryBuilder->andWhere("i.codigoItemPk = {$session->get('filtroInvBucarItemCodigo')}");
        }
        if ($session->get('filtroInvBuscarItemNombre') != '') {
            $queryBuilder->andWhere("i.nombre LIKE '%{$session->get('filtroInvBuscarItemNombre')}%'");
        }
        if ($session->get('filtroInvBuscarItemReferencia') != '') {
            $queryBuilder->andWhere("i.referencia LIKE '%{$session->get('filtroInvBuscarItemReferencia')}%'");
        }
        if ($session->get('filtroInvMarcaItem') != '') {
            $queryBuilder->andWhere("m.nombre LIKE '%{$session->get('filtroInvMarcaItem')}%'");
        }

        return $queryBuilder;
    }

    public function listaRegenerar($codigoItem = null){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvItem::class,'i')
            ->select('i.codigoItemPk')
        ->andWhere('i.afectaInventario = 1');
        if($codigoItem) {
            $queryBuilder->andWhere('i.codigoItemPk =' . $codigoItem);
        }
        return $queryBuilder->getQuery()->execute();
    }

    public function existencia()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvItem::class, 'i')
            ->select('i.codigoItemPk')
            ->addSelect('i.nombre')
            ->addSelect('i.referencia')
            ->addSelect('m.nombre AS marca')
            ->addSelect('i.cantidadExistencia')
            ->addSelect('i.cantidadRemisionada')
            ->addSelect('i.cantidadDisponible')
            ->leftJoin('i.marcaRel', 'm')
            ->where('i.cantidadExistencia > 0')
            ->orderBy('i.nombre', "ASC");
        if ($session->get('filtroInvInformeItemCodigo') != '') {
            $queryBuilder->andWhere("i.codigoItemPk = {$session->get('filtroInvInformeItemCodigo')}");
        }
        return $queryBuilder;
    }

    public function stockMinimo()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvItem::class, 'i')
            ->select('i.codigoItemPk')
            ->addSelect('i.nombre')
            ->addSelect('i.cantidadDisponible')
            ->addSelect('i.stockMinimo')
            ->where('i.cantidadDisponible < i.stockMinimo')
        ->andWhere('i.validarStock = 1');
        $arItemes = $queryBuilder->getQuery()->getResult();
        return $arItemes;
    }

}

