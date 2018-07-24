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
        $queryBuilder = $this->_em->createQueryBuilder()->from('App:Inventario\InvItem', 'ii')
            ->select('ii.codigoItemPk')
            ->addSelect('ii.nombre')
            ->addSelect('ii.cantidadExistencia')
            ->addSelect('ii.cantidadOrdenCompra')
            ->addSelect('ii.cantidadSolicitud')
            ->addSelect('ii.stockMinimo')
            ->addSelect('ii.stockMaximo')
            ->where('ii.codigoItemPk <> 0');
        if ($session->get('filtroInvItemCodigo') != '') {
            $queryBuilder->andWhere("ii.codigoItemPk = {$session->get('filtroInvItemCodigo')}");
        }
        if ($session->get('filtroInvItemNombre') != '') {
            $queryBuilder->andWhere("ii.nombre LIKE '%{$session->get('filtroInvItemNombre')}%' ");
        }
        $queryBuilder->orderBy('ii.codigoItemPk', 'ASC');
        return $queryBuilder;
    }

    public function listaRegenerar(){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvItem::class,'i')
            ->select('i.codigoItemPk');
        //->andWhere('i.codigoItemPk = 85')
        return $queryBuilder->getQuery()->execute();
    }


}