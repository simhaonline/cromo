<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class InvItemRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvItem::class);
    }

    /**
     * @param $arrSeleccionados array
     * @return string
     */
    public function eliminar($arrSeleccionados)
    {
        try{
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $arRegistro = $this->getEntityManager()->getRepository(InvItem::class)->find($arrSeleccionado);
                if ($arRegistro) {
                    $this->getEntityManager()->remove($arRegistro);
                }
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $ex) {
            Mensajes::error("El registro tiene registros relacionados");
        }
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

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoItem = null;
        $referenciaItem = null;
        $existenciaItem = null;
        $disponibilidadItem = null;
        $nombreItem = null;
        $marcaItem = null;

        if ($filtros) {
            $codigoItem = $filtros['codigoItem'] ?? null;
            $referenciaItem = $filtros['referenciaItem'] ?? null;
            $nombreItem = $filtros['nombreItem'] ?? null;
            $marcaItem = $filtros['marcaItem'] ?? null;
            $existenciaItem = $filtros['existenciaItem'] ?? null;
            $disponibilidadItem = $filtros['disponibilidadItem'] ?? null;
        }
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

        if($existenciaItem){
            $queryBuilder->andWhere("i.cantidadExistencia > 0");
        }

        if($disponibilidadItem){
            $queryBuilder->andWhere("i.cantidadDisponible > 0");
        }

        if ($codigoItem) {
            $queryBuilder->andWhere("i.codigoItemPk = {$codigoItem}");
        }

        if ($nombreItem) {
            $queryBuilder->andWhere("i.nombre LIKE '%{$nombreItem}%'");
        }
        if ($referenciaItem) {
            $queryBuilder->andWhere("i.referencia LIKE '%{$referenciaItem}%'");
        }
        if ($marcaItem) {
            $queryBuilder->andWhere("m.nombre LIKE '%{$marcaItem}%'");
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
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

    public function stockBajo()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvItem::class, 'i')
            ->select('i.codigoItemPk')
            ->addSelect('i.nombre')
            ->addSelect('i.cantidadDisponible')
            ->addSelect('i.stockMinimo')
            ->where('i.stockMinimo < i.cantidadDisponible')
            ->andWhere('i.validarStock = 1');
        if ($session->get('filtroInvInformeStockBajoItemCodigo') != '') {
            $queryBuilder->andWhere("i.codigoItemPk = {$session->get('filtroInvInformeStockBajoItemCodigo')}");
        }

        return $queryBuilder;
    }

}

