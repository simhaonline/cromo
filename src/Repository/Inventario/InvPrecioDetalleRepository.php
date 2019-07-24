<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvPrecioDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class InvPrecioDetalleRepository extends ServiceEntityRepository
{
    /**
     * InvPrecioDetalleRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPrecioDetalle::class);
    }

    /**
     * @param $codigoPrecioVenta
     * @param $codigoItem
     * @return float
     */
    public function precioVenta($codigoPrecioVenta, $codigoItem): float
    {
        $precio = 0;
        if ($codigoPrecioVenta && $codigoItem) {
            $arPrecioDetalle = $this->getEntityManager()->getRepository(InvPrecioDetalle::class)->findOneBy(array('codigoPrecioDetallePk' => $codigoPrecioVenta, 'codigoItemFk' => $codigoItem));
            if ($arPrecioDetalle) {
                $precio = $arPrecioDetalle->getVrPrecio();
            }
        }

        return $precio;
    }

    /**
     * @param $id
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($id)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPrecioDetalle::class, 'pd');
        $queryBuilder
            ->select('pd.codigoPrecioDetallePk')
            ->addSelect('pd.codigoItemFk')
            ->addSelect('i.nombre')
            ->addSelect('m.nombre AS marca')
            ->addSelect('i.porcentajeIva')
            ->addSelect('pd.vrPrecio')
            ->addSelect('pd.diasPromedioEntrega')
            ->leftJoin('pd.itemRel', 'i')
            ->leftJoin('i.marcaRel', 'm')
            ->where('pd.codigoPrecioFk = ' . $id);

        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function informePrecioDetalle()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPrecioDetalle::class, 'pd')
            ->select('pd.codigoPrecioDetallePk')
            ->addSelect('p.nombre AS precio')
            ->addSelect('m.nombre AS marca')
            ->addSelect('i.nombre')
            ->addSelect('pd.codigoItemFk')
            ->addSelect('p.compra')
            ->addSelect('p.venta')
            ->addSelect('pd.codigoItemFk')
            ->addSelect('i.porcentajeIva')
            ->addSelect('pd.vrPrecio')
            ->addSelect('pd.diasPromedioEntrega')
            ->addSelect('pd.vrPrecio')
            ->leftJoin('pd.itemRel', 'i')
            ->leftJoin('i.marcaRel', 'm')
            ->leftJoin('pd.precioRel', 'p');
        if ($session->get('filtroInvInformeItemCodigo') != '') {
            $queryBuilder->andWhere("i.codigoItemPk = '{$session->get('filtroInvInformeItemCodigo')}' ");
        }
        if ($session->get('filtroInvInformeNombreListaPrecio') != '') {
            $queryBuilder->andWhere("p.nombre = '{$session->get('filtroInvInformeNombreListaPrecio')}' ");
        }
        return $queryBuilder;
    }

    /**
     * @param $arrSeleccionados
     * @return string
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(InvPrecioDetalle::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }

    /**
     * @param $codigoPrecio integer
     * @param $codigoItem integer
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function obtenerPrecio($codigoPrecio, $codigoItem)
    {
        $precio = 0;
        if ($codigoPrecio) {
            $qb = $this->getEntityManager()->createQueryBuilder()->from(InvPrecioDetalle::class, 'pd')
                ->select('pd.vrPrecio as precio')
                ->where("pd.codigoPrecioFk = {$codigoPrecio}")
                ->andWhere("pd.codigoItemFk = {$codigoItem}");
            $arPrecioDetalle = $qb->getQuery()->getResult();
            if($arPrecioDetalle) {
                $precio = $arPrecioDetalle[0]['precio'];
            }
        }
        return $precio;
    }
}