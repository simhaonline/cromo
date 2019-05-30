<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCondicionManejo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteCondicionManejoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCondicionManejo::class);
    }

    public function cliente($id){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCondicionManejo::class, 'cm')
            ->select('cm.codigoCondicionManejoPk')
            ->addSelect('cm.porcentaje')
            ->addSelect('cm.minimoUnidad')
            ->addSelect('cm.minimoDespacho')
            ->addSelect('z.nombre as zonaNombre')
            ->addSelect('co.nombre as ciudadOrigenNombre')
            ->addSelect('cd.nombre as ciudadDestinoNombre')
            ->leftJoin('cm.zonaRel', 'z')
            ->leftJoin('cm.ciudadOrigenRel', 'co')
            ->leftJoin('cm.ciudadDestinoRel', 'cd')
            ->where('cm.codigoClienteFk = ' . $id);
        $arCondicionesManejo = $queryBuilder->getQuery()->getResult();
        return $arCondicionesManejo;

    }

    public function eliminar($arrSeleccionados)
    {
        if($arrSeleccionados) {
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $ar = $this->getEntityManager()->getRepository(TteCondicionManejo::class)->find($arrSeleccionado);
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
            $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cf')
                ->select('cf.codigoCondicionManejoPk')
                ->addSelect('co.nombre as ciudadOrigenNombre')
                ->addSelect('cd.nombre as ciudadDestinoNombre')
                ->addSelect('z.nombre as zonaNombre')
                ->addSelect('cf.descuentoPeso')
                ->addSelect('cf.descuentoUnidad')
                ->leftJoin('cf.ciudadOrigenRel', 'co')
                ->leftJoin('cf.ciudadDestinoRel', 'cd')
                ->leftJoin('cf.zonaRel', 'z')
                ->setMaxResults(20);
            $arCondicionesManejo = $queryBuilder->getQuery()->getResult();
            return $arCondicionesManejo;
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
                $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cf')
                    ->select('cf.codigoCondicionManejoPk')
                    ->where("cf.codigoClienteFk = {$codigoCliente}");
                $arCondicionesManejo = $queryBuilder->getQuery()->getResult();
                if($arCondicionesManejo) {
                    if($codigoCiudadOrigen && $codigoCiudadDestino) {
                        $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cf')
                            ->select('gc.codigoGuiaCargaPk')
                            ->select('cf.codigoCondicionManejoPk')
                            ->addSelect('cf.descuentoPeso')
                            ->addSelect('cf.descuentoUnidad')
                            ->addSelect('cf.pesoMinimo')
                            ->addSelect('cf.pesoMinimoGuia')
                            ->addSelect('cf.manejoMinimo')
                            ->addSelect('cf.manejoMinimoGuia')
                            ->where("cf.codigoClienteFk = {$codigoCliente}")
                            ->andWhere("cf.codigoCiudadOrigenFk = {$codigoCiudadOrigen}")
                            ->andWhere("cf.codigoCiudadDestinoFk = {$codigoCiudadDestino}");
                        $arCondicionManejo = $queryBuilder->getQuery()->getResult();
                        if($arCondicionManejo) {
                            return $arCondicionManejo[0];
                        }
                    }

                    if($codigoZona) {
                        $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cf')
                            ->select('gc.codigoGuiaCargaPk')
                            ->select('cf.codigoCondicionManejoPk')
                            ->addSelect('cf.descuentoPeso')
                            ->addSelect('cf.descuentoUnidad')
                            ->addSelect('cf.pesoMinimo')
                            ->addSelect('cf.pesoMinimoGuia')
                            ->addSelect('cf.manejoMinimo')
                            ->addSelect('cf.manejoMinimoGuia')
                            ->where("cf.codigoClienteFk = {$codigoCliente}")
                            ->andWhere("cf.codigoZonaFk = '{$codigoZona}'");
                        $arCondicionManejo = $queryBuilder->getQuery()->getResult();
                        if($arCondicionManejo) {
                            return $arCondicionManejo[0];
                        }
                    }

                    $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cf')
                        ->select('gc.codigoGuiaCargaPk')
                        ->select('cf.codigoCondicionManejoPk')
                        ->addSelect('cf.descuentoPeso')
                        ->addSelect('cf.descuentoUnidad')
                        ->addSelect('cf.pesoMinimo')
                        ->addSelect('cf.pesoMinimoGuia')
                        ->addSelect('cf.manejoMinimo')
                        ->addSelect('cf.manejoMinimoGuia')
                        ->where("cf.codigoClienteFk = {$codigoCliente}");
                    $arCondicionManejo = $queryBuilder->getQuery()->getResult();
                    if($arCondicionManejo) {
                        return $arCondicionManejo[0];
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
