<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TtePrecioDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TtePrecioDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TtePrecioDetalle::class);
    }

    public function lista($id)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TtePrecioDetalle::class, 'prd')
            ->select('prd.codigoPrecioDetallePk')
            ->addSelect('co.nombre as ciudadOrigen')
            ->addSelect('prd.codigoCiudadOrigenFk')
            ->addSelect('cd.nombre as ciudadDestino')
            ->addSelect('prd.codigoCiudadDestinoFk')
            ->addSelect('p.nombre AS producto')
            ->addSelect('prd.codigoProductoFk')
            ->addSelect('prd.vrPeso')
            ->addSelect('prd.vrUnidad')
            ->addSelect('prd.pesoTope')
            ->addSelect('prd.vrPesoTope')
            ->addSelect('prd.vrPesoTopeAdicional')
            ->addSelect('prd.minimo')
            ->addSelect('z.nombre as zonaNombre')
            ->addSelect('cdz.nombre as zonaCiudadDestino')
            ->leftJoin('prd.productoRel', 'p')
            ->leftJoin('prd.ciudadDestinoRel','cd')
            ->leftJoin('prd.ciudadOrigenRel','co')
            ->leftJoin('cd.zonaRel', 'cdz')
            ->leftJoin('prd.zonaRel', 'z')
            ->where('prd.codigoPrecioFk = ' . $id )
            ->orderBy('prd.codigoPrecioDetallePk', 'ASC')
            ->getQuery();
        $result = $queryBuilder->getResult();

        return $result;
    }

    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(TtePrecioDetalle::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param $lista
     * @param $producto
     * @param $origen
     * @param $destino
     * @return |null
     */
    public function precio($precio, $producto, $origen, $destino)
    {
        $em = $this->getEntityManager();
        $arPrecioDetalle = $em->getRepository(TtePrecioDetalle::class)->findOneBy(array(
            'codigoPrecioFk' => $precio,
            'codigoProductoFk' => $producto,
            'codigoCiudadOrigenFk' => $origen,
            'codigoCiudadDestinoFk' => $destino));
        return $arPrecioDetalle;
    }

    public function apiWindowsDetalle($raw) {
        $em = $this->getEntityManager();
        $precio = $raw['precio']?? null;
        $origen = $raw['origen']?? null;
        $destino = $raw['destino']?? null;
        if($precio && $origen && $destino) {
            $queryBuilder = $em->createQueryBuilder()->from(TtePrecioDetalle::class, 'pd')
                ->select('pd.codigoPrecioDetallePk')
                ->addSelect('pd.minimo')
                ->addSelect('pd.vrPeso')
                ->addSelect('pd.vrUnidad')
                ->addSelect('pd.vrPesoTope')
                ->addSelect('pd.vrPesoTopeAdicional')
                ->addSelect('pd.pesoTope')
                ->addSelect('p.nombre as productoNombre')
                ->addSelect('pro.omitirDescuento')
                ->leftJoin('pd.productoRel', 'p')
                ->leftJoin('pd.precioRel', 'pro')
                ->where('pd.codigoPrecioFk=' . $precio)
                ->andWhere('pd.codigoCiudadOrigenFk=' . $origen)
                ->andWhere('pd.codigoCiudadDestinoFk=' . $destino);
            $arPrecios = $queryBuilder->getQuery()->getResult();
            if($arPrecios) {
                return $arPrecios;
            } else {
                return [
                    "error" => "No se encontraron resultados"
                ];
            }
        } else {
            return [
                "error" => "Faltan datos para la api"
            ];
        }
    }

    public function apiWindowsDetalleProducto($raw) {
        $em = $this->getEntityManager();
        $precio = $raw['precio']?? null;
        $origen = $raw['origen']?? null;
        $destino = $raw['destino']?? null;
        $producto = $raw['producto']?? null;
        $zona = $raw['zona']?? null;
        if($precio && $origen && $destino && $producto) {
            $queryBuilder = $em->createQueryBuilder()->from(TtePrecioDetalle::class, 'pd')
                ->select('pd.codigoPrecioDetallePk')
                ->addSelect('pd.minimo')
                ->addSelect('pd.vrPeso')
                ->addSelect('pd.vrUnidad')
                ->addSelect('pd.vrPesoTope')
                ->addSelect('pd.vrPesoTopeAdicional')
                ->addSelect('pd.pesoTope')
                ->addSelect('p.nombre as productoNombre')
                ->addSelect('pro.omitirDescuento')
                ->leftJoin('pd.productoRel', 'p')
                ->leftJoin('pd.precioRel', 'pro')
                ->where("pd.codigoPrecioFk='" . $precio . "'")
                ->andWhere("pd.codigoCiudadOrigenFk='" . $origen . "'")
                ->andWhere("pd.codigoCiudadDestinoFk='" . $destino . "'")
            ->andWhere("pd.codigoProductoFk='" . $producto . "'");
            $arPrecios = $queryBuilder->getQuery()->getResult();
            if($arPrecios) {
                return $arPrecios[0];
            } else {
                $queryBuilder = $em->createQueryBuilder()->from(TtePrecioDetalle::class, 'pd')
                    ->select('pd.codigoPrecioDetallePk')
                    ->addSelect('pd.minimo')
                    ->addSelect('pd.vrPeso')
                    ->addSelect('pd.vrUnidad')
                    ->addSelect('pd.vrPesoTope')
                    ->addSelect('pd.vrPesoTopeAdicional')
                    ->addSelect('pd.pesoTope')
                    ->addSelect('p.nombre as productoNombre')
                    ->addSelect('pro.omitirDescuento')
                    ->leftJoin('pd.productoRel', 'p')
                    ->leftJoin('pd.precioRel', 'pro')
                    ->where("pd.codigoPrecioFk='" . $precio . "'")
                    ->andWhere("pd.codigoCiudadOrigenFk='" . $origen . "'")
                    ->andWhere("pd.codigoZonaFk='" . $zona . "'")
                    ->andWhere("pd.codigoProductoFk='" . $producto . "'");
                $arPrecios = $queryBuilder->getQuery()->getResult();
                if($arPrecios) {
                    return $arPrecios[0];
                } else {
                    $queryBuilder = $em->createQueryBuilder()->from(TtePrecioDetalle::class, 'pd')
                        ->select('pd.codigoPrecioDetallePk')
                        ->addSelect('pd.minimo')
                        ->addSelect('pd.vrPeso')
                        ->addSelect('pd.vrUnidad')
                        ->addSelect('pd.vrPesoTope')
                        ->addSelect('pd.vrPesoTopeAdicional')
                        ->addSelect('pd.pesoTope')
                        ->addSelect('p.nombre as productoNombre')
                        ->addSelect('pro.omitirDescuento')
                        ->leftJoin('pd.productoRel', 'p')
                        ->leftJoin('pd.precioRel', 'pro')
                        ->where("pd.codigoPrecioFk='" . $precio . "'")
                        ->andWhere("pd.codigoCiudadOrigenFk='" . $origen . "'")
                        ->andWhere("pd.codigoZonaFk IS NULL")
                        ->andWhere("pd.codigoProductoFk='" . $producto . "'");
                    $arPrecios = $queryBuilder->getQuery()->getResult();
                    if($arPrecios) {
                        return $arPrecios[0];
                    } else {
                        return [
                            "error" => "No se encontraron resultados"
                        ];
                    }
                }
            }
        } else {
            return [
                "error" => "Faltan datos para la api"
            ];
        }
    }

}
