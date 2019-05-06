<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCondicionZona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteCondicionZonaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCondicionZona::class);
    }

    public function condicion($id){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCondicionZona::class, 'cz')
            ->select('cz.codigoCondicionZonaPk')
            ->addSelect('cz.descuentoPeso')
            ->addSelect('z.nombre as zonaNombre')
            ->addSelect('co.nombre as ciudadOrigenNombre')
            ->leftJoin('cz.zonaRel', 'z')
            ->leftJoin('cz.ciudadOrigenRel', 'co')
        ->where('cz.codigoCondicionFk = ' . $id);
        $arCondicionsZona = $queryBuilder->getQuery()->getResult();
        return $arCondicionsZona;

    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        foreach ($arrSeleccionados as $codigo) {
            $ar = $em->getRepository(TteCondicionZona::class)->find($codigo);
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
            $queryBuilder = $em->createQueryBuilder()->from(TteCondicionZona::class, 'dz')
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
