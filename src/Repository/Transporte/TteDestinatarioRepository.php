<?php

namespace App\Repository\Transporte;

use App\Entity\General\GenIdentificacion;
use App\Entity\Transporte\TteCiudad;
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
            ->addSelect('d.numeroIdentificacion')
            ->addSelect('d.nombreCorto')
            ->addSelect('c.nombre as ciudadNombre')
            ->addSelect('d.direccion')
            ->leftJoin('d.ciudadRel', 'c')
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

    public function apiWindowsNuevo($raw)
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();
        try {
            $codigoIdentificacion = $raw['codigoIdentificacionFk']?? null;
            $numeroIdentificacion = $raw['numeroIdentificacion']?? null;
            if($codigoIdentificacion && $numeroIdentificacion) {
                $arDestinatario = $em->getRepository(TteDestinatario::class)->findOneBy(['codigoIdentificacionFk' => $raw['codigoIdentificacionFk'], 'numeroIdentificacion' => $raw['numeroIdentificacion']]);
                if(!$arDestinatario) {
                    $arDestinatario = new TteDestinatario();
                    $arDestinatario->setIdentificacionRel($em->getReference(GenIdentificacion::class, $raw['codigoIdentificacionFk']));
                    $arDestinatario->setNumeroIdentificacion($raw['numeroIdentificacion']);
                    $arDestinatario->setNombreCorto($raw['nombreCorto']);
                    $arDestinatario->setNombre1($raw['nombre1']);
                    $arDestinatario->setNombre2($raw['nombre2']);
                    $arDestinatario->setApellido1($raw['apellido1']);
                    $arDestinatario->setApellido2($raw['apellido2']);
                    $arDestinatario->setTelefono($raw['telefono']);
                    $arDestinatario->setDireccion($raw['direccion']);
                    $arDestinatario->setCorreo($raw['correo']);
                    $arDestinatario->setCiudadRel($em->getReference(TteCiudad::class, $raw['codigoCiudadFk']));
                    $em->persist($arDestinatario);
                    $em->flush();
                    $em->getConnection()->commit();
                    return [
                        "codigoDestinatarioPk" => $arDestinatario->getCodigoDestinatarioPk()
                    ];
                } else {
                    return ["error" => 'El destinatario con esta identificacion ya existe'];
                }
            } else {
                return ["error" => 'Faltan datos para la api'];
            }
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            return ["error" => $e->getMessage()];
        }

    }

}