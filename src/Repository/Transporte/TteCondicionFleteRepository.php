<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCondicionFlete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteCondicionFleteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCondicionFlete::class);
    }

    public function cliente($id){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCondicionFlete::class, 'cf')
            ->select('cf.codigoCondicionFletePk')
            ->addSelect('cf.descuentoPeso')
            ->addSelect('cf.descuentoUnidad')
            ->addSelect('cf.pesoMinimo')
            ->addSelect('cf.pesoMinimoGuia')
            ->addSelect('cf.fleteMinimo')
            ->addSelect('cf.fleteMinimoGuia')
            ->addSelect('cf.vrPeso')
            ->addSelect('cf.vrUnidad')
            ->addSelect('z.nombre as zonaNombre')
            ->addSelect('co.nombre as ciudadOrigenNombre')
            ->addSelect('cd.nombre as ciudadDestinoNombre')
            ->leftJoin('cf.zonaRel', 'z')
            ->leftJoin('cf.ciudadOrigenRel', 'co')
            ->leftJoin('cf.ciudadDestinoRel', 'cd')
            ->where('cf.codigoClienteFk = ' . $id);
        $arCondicionesFlete = $queryBuilder->getQuery()->getResult();
        return $arCondicionesFlete;

    }

    public function eliminar($arrSeleccionados)
    {
        if($arrSeleccionados) {
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $ar = $this->getEntityManager()->getRepository(TteCondicionFlete::class)->find($arrSeleccionado);
                if ($ar) {
                    $this->getEntityManager()->remove($ar);
                }
            }
            $this->getEntityManager()->flush();
        }
    }

    public function apiWindowsCliente($raw) {
        $em = $this->getEntityManager();
        $codigoCliente = $raw['codigoCliente']?? null;
        if($codigoCliente) {
            $queryBuilder = $em->createQueryBuilder()->from(TteCondicionFlete::class, 'cf')
                ->select('cf.codigoCondicionFletePk')
                ->addSelect('co.nombre as ciudadOrigenNombre')
                ->addSelect('cd.nombre as ciudadDestinoNombre')
                ->addSelect('z.nombre as zonaNombre')
                ->addSelect('cf.descuentoPeso')
                ->addSelect('cf.descuentoUnidad')
                ->leftJoin('cf.ciudadOrigenRel', 'co')
                ->leftJoin('cf.ciudadDestinoRel', 'cd')
                ->leftJoin('cf.zonaRel', 'z')
                ->setMaxResults(20);
            $arCondicionesFlete = $queryBuilder->getQuery()->getResult();
            return $arCondicionesFlete;
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }

    public function apiWindowsLiquidar($raw) {
        $em = $this->getEntityManager();
        $codigoCliente = $raw['codigoCliente']?? null;
        $codigoCiudadOrigen = $raw['origen']?? null;
        $codigoCiudadDestino = $raw['destino']?? null;
        $codigoZona = $raw['codigoZona']?? null;
        if($codigoCliente) {
            if($codigoCliente) {
                $queryBuilder = $em->createQueryBuilder()->from(TteCondicionFlete::class, 'cf')
                    ->select('cf.codigoCondicionFletePk')
                    ->where("cf.codigoClienteFk = {$codigoCliente}");
                $arCondicionesFlete = $queryBuilder->getQuery()->getResult();
                if($arCondicionesFlete) {
                    if($codigoCiudadOrigen && $codigoCiudadDestino) {
                        $queryBuilder = $em->createQueryBuilder()->from(TteCondicionFlete::class, 'cf')
                            ->select('gc.codigoGuiaCargaPk')
                            ->select('cf.codigoCondicionFletePk')
                            ->addSelect('cf.descuentoPeso')
                            ->addSelect('cf.descuentoUnidad')
                            ->addSelect('cf.pesoMinimo')
                            ->addSelect('cf.pesoMinimoGuia')
                            ->addSelect('cf.fleteMinimo')
                            ->addSelect('cf.fleteMinimoGuia')
                            ->where("cf.codigoClienteFk = {$codigoCliente}")
                            ->andWhere("cf.codigoCiudadOrigenFk = {$codigoCiudadOrigen}")
                            ->andWhere("cf.codigoCiudadDestinoFk = {$codigoCiudadDestino}");
                        $arCondicionFlete = $queryBuilder->getQuery()->getResult();
                        if($arCondicionFlete) {
                            return $arCondicionFlete[0];
                        }
                    }

                    if($codigoZona) {
                        $queryBuilder = $em->createQueryBuilder()->from(TteCondicionFlete::class, 'cf')
                            ->select('gc.codigoGuiaCargaPk')
                            ->select('cf.codigoCondicionFletePk')
                            ->addSelect('cf.descuentoPeso')
                            ->addSelect('cf.descuentoUnidad')
                            ->addSelect('cf.pesoMinimo')
                            ->addSelect('cf.pesoMinimoGuia')
                            ->addSelect('cf.fleteMinimo')
                            ->addSelect('cf.fleteMinimoGuia')
                            ->where("cf.codigoClienteFk = {$codigoCliente}")
                            ->andWhere("cf.codigoZonaFk = '{$codigoZona}'");
                        $arCondicionFlete = $queryBuilder->getQuery()->getResult();
                        if($arCondicionFlete) {
                            return $arCondicionFlete[0];
                        }
                    }

                    $queryBuilder = $em->createQueryBuilder()->from(TteCondicionFlete::class, 'cf')
                        ->select('gc.codigoGuiaCargaPk')
                        ->select('cf.codigoCondicionFletePk')
                        ->addSelect('cf.descuentoPeso')
                        ->addSelect('cf.descuentoUnidad')
                        ->addSelect('cf.pesoMinimo')
                        ->addSelect('cf.pesoMinimoGuia')
                        ->addSelect('cf.fleteMinimo')
                        ->addSelect('cf.fleteMinimoGuia')
                        ->where("cf.codigoClienteFk = {$codigoCliente}");
                    $arCondicionFlete = $queryBuilder->getQuery()->getResult();
                    if($arCondicionFlete) {
                        return $arCondicionFlete[0];
                    } else {
                        return [
                            "error" => "No se encontraron resultados"
                        ];
                    }
                } else {
                    return [
                        "error" => "No se encontraron resultados"
                    ];
                }
            }
        } else {
            return ["error" => "Faltan datos para la api"];
        }
    }

}
