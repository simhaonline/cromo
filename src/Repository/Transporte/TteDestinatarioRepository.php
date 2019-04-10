<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDestinatario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteDestinatarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteDestinatario::class);
    }

    public function apiWindowsBuscar($raw) {
        $em = $this->getEntityManager();
        $nombre = $raw['nombre']?? null;
        $queryBuilder = $em->createQueryBuilder()->from(TteDestinatario::class, 'd')
            ->select('d.codigoDestinatarioPk')
            ->addSelect('d.nombreCorto')
            ->setMaxResults(10);
        if($nombre) {
            $queryBuilder->andWhere("d.nombreCorto LIKE '%${nombre}%'");
        }
        $arDestinatarios = $queryBuilder->getQuery()->getResult();
        return $arDestinatarios;
    }

    public function apiWindowsDetalle($raw) {
        $em = $this->getEntityManager();
        $codigo = $raw['codigo']?? null;
        if($codigo) {
            $queryBuilder = $em->createQueryBuilder()->from(TteDestinatario::class, 'd')
                ->select('d.codigoDestinatarioPk')
                ->addSelect('d.nombreCorto')
                ->addSelect('d.direccion')
                ->addSelect('d.telefono')
                ->addSelect('d.codigoCiudadFk');
            if($codigo) {
                $queryBuilder->where("d.codigoDestinatarioPk=" . $codigo);
            }
            $arDestinatarios = $queryBuilder->getQuery()->getResult();
            if($arDestinatarios) {
                return $arDestinatarios[0];
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