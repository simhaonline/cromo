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
        $em = $this->getEntityManager();
        foreach ($arrSeleccionados as $codigo) {
            $ar = $em->getRepository(TteCondicionFlete::class)->find($codigo);
            if ($ar) {
                $em->remove($ar);
            }
        }
        $em->flush();
    }

    public function apiWindowsDetalle($raw) {
        $em = $this->getEntityManager();
        $condicion = $raw['codigoCondicion']?? null;
        $origen = $raw['origen']?? null;
        $zona = $raw['codigoZona']?? null;
        if($condicion && $origen && $zona) {
            $queryBuilder = $em->createQueryBuilder()->from(TteCondicionFlete::class, 'dz')
                ->select('dz.descuentoPeso')
                ->where('dz.codigoCiudadOrigenFk=' . $origen)
                ->andWhere("dz.codigoCondicionFk=" . $condicion)
                ->andWhere("dz.codigoZonaFk='" . $zona . "'");
            $arCondicionsZona = $queryBuilder->getQuery()->getResult();
            if($arCondicionsZona) {
                return $arCondicionsZona[0];
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

}
