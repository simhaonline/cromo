<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCondicionManejo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TteCondicionManejoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
            ->addSelect('cd.lunes as ciudadDestinoLunes')
            ->addSelect('cd.martes as ciudadDestinoMartes')
            ->addSelect('cd.miercoles as ciudadDestinoMiercoles')
            ->addSelect('cd.jueves as ciudadDestinoJueves')
            ->addSelect('cd.viernes as ciudadDestinoViernes')
            ->addSelect('cd.sabado as ciudadDestinoSabado')
            ->addSelect('cd.domingo as ciudadDestinoDomingo')
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

    public function apiWindowsLiquidar($raw) {
        $em = $this->getEntityManager();
        $codigoCliente = $raw['codigoCliente']?? null;
        $codigoCiudadOrigen = $raw['origen']?? null;
        $codigoCiudadDestino = $raw['destino']?? null;
        $codigoZona = $raw['codigoZona']?? null;
        if($codigoCliente) {
            if($codigoCliente) {
                $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cm')
                    ->select('cm.codigoCondicionManejoPk')
                    ->where("cm.codigoClienteFk = {$codigoCliente}");
                $arCondicionesManejo = $queryBuilder->getQuery()->getResult();
                if($arCondicionesManejo) {
                    if($codigoCiudadOrigen && $codigoCiudadDestino) {
                        $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cm')
                            ->select('cm.codigoCondicionManejoPk')
                            ->addSelect('cm.porcentaje')
                            ->addSelect('cm.minimoUnidad')
                            ->addSelect('cm.minimoDespacho')
                            ->where("cm.codigoClienteFk = {$codigoCliente}")
                            ->andWhere("cm.codigoCiudadOrigenFk = {$codigoCiudadOrigen}")
                            ->andWhere("cm.codigoCiudadDestinoFk = {$codigoCiudadDestino}");
                        $arCondicionManejo = $queryBuilder->getQuery()->getResult();
                        if($arCondicionManejo) {
                            return $arCondicionManejo[0];
                        }
                    }

                    if($codigoZona) {
                        $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cm')
                            ->select('cm.codigoCondicionManejoPk')
                            ->addSelect('cm.porcentaje')
                            ->addSelect('cm.minimoUnidad')
                            ->addSelect('cm.minimoDespacho')
                            ->where("cm.codigoClienteFk = {$codigoCliente}")
                            ->andWhere("cm.codigoZonaFk = '{$codigoZona}'");
                        $arCondicionManejo = $queryBuilder->getQuery()->getResult();
                        if($arCondicionManejo) {
                            return $arCondicionManejo[0];
                        }
                    }

                    $queryBuilder = $em->createQueryBuilder()->from(TteCondicionManejo::class, 'cm')
                        ->select('cm.codigoCondicionManejoPk')
                        ->addSelect('cm.porcentaje')
                        ->addSelect('cm.minimoUnidad')
                        ->addSelect('cm.minimoDespacho')
                        ->where("cm.codigoClienteFk = {$codigoCliente}")
                        ->andWhere("cm.codigoCiudadOrigenFk = {$codigoCiudadOrigen}")
                        ->andWhere("cm.codigoCiudadDestinoFk IS NULL")
                        ->andWhere("cm.codigoZonaFk IS NULL");
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
