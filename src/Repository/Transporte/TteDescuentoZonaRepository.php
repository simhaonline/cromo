<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDescuentoZona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteDescuentoZonaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDescuentoZona::class);
    }

    public function condicion($id){

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDescuentoZona::class, 'dz')
            ->select('dz.codigoDescuentoZonaPk')
            ->addSelect('dz.descuentoPeso')
            ->addSelect('z.nombre as zonaNombre')
            ->addSelect('co.nombre as ciudadOrigenNombre')
            ->leftJoin('dz.zonaRel', 'z')
            ->leftJoin('dz.ciudadOrigenRel', 'co')
        ->where('dz.codigoCondicionFk = ' . $id);
        $arDescuentosZona = $queryBuilder->getQuery()->getResult();
        return $arDescuentosZona;

    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        foreach ($arrSeleccionados as $codigo) {
            $ar = $em->getRepository(TteDescuentoZona::class)->find($codigo);
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
            $queryBuilder = $em->createQueryBuilder()->from(TteDescuentoZona::class, 'dz')
                ->select('dz.descuentoPeso')
                ->where('dz.codigoCiudadOrigenFk=' . $origen)
                ->andWhere("dz.codigoCondicionFk=" . $condicion)
                ->andWhere("dz.codigoZonaFk='" . $zona . "'");
            $arDescuentosZona = $queryBuilder->getQuery()->getResult();
            if($arDescuentosZona) {
                return $arDescuentosZona[0];
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
