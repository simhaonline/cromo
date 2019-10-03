<?php

namespace App\Repository\Transporte;

use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenIdentificacion;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteOperacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteClienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCliente::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteCliente','c')
            ->select('c.codigoClientePk AS ID')
            ->addSelect('c.nombreCorto AS NOMBRE')
            ->addSelect('c.numeroIdentificacion AS NIT')
            ->addSelect('c.direccion AS DIRECCION')
            ->addSelect('c.telefono AS TELEFONO')
            ->addSelect('c.plazoPago AS PLAZO_PAGO');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCliente::class, 'c')
            ->select('c.codigoClientePk')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.telefono')
            ->addSelect('c.movil')
            ->addSelect('c.direccion')
            ->addSelect('c.codigoOperacionFk')
            ->addSelect('a.nombre AS asesorNombre')
            ->addSelect('ciu.nombre AS ciudadNombre')
            ->addSelect('con.nombre AS condicion')
            ->addSelect('c.codigoCondicionFk')
            ->addSelect('con.codigoPrecioFk')
            ->addSelect('p.nombre as precioNombre')
            ->addSelect('c.guiaPagoCredito as CRE')
            ->addSelect('c.guiaPagoContado as CON')
            ->addSelect('c.guiaPagoDestino as DES')
            ->addSelect('c.guiaPagoCortesia as COR')
            ->addSelect('c.guiaPagoRecogida as REC')
            ->leftJoin('c.asesorRel', 'a')
            ->leftJoin('c.ciudadRel', 'ciu')
            ->leftJoin('c.condicionRel', 'con')
            ->leftJoin('con.precioRel', 'p')
            ->where('c.codigoClientePk IS NOT NULL')
            ->orderBy('c.codigoClientePk', 'ASC');
        if ($session->get('filtroTteNombreCliente') != '') {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroTteNombreCliente')}%' ");
        }
        if ($session->get('filtroTteNitCliente') != '') {
            $queryBuilder->andWhere("c.numeroIdentificacion = {$session->get('filtroTteNitCliente')} ");
        }
        if ($session->get('filtroTteCodigoCliente') != '') {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')} ");
        }

        return $queryBuilder;
    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arCliente = $em->getRepository(TteCliente::class)->find($codigo);
        if($arCliente) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arCliente->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arCliente->getNumeroIdentificacion()));
            if(!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arCliente->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arCliente->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arCliente->getNombreCorto());
                $arTercero->setDireccion($arCliente->getDireccion());
                $arTercero->setTelefono($arCliente->getTelefono());
                $arTercero->setCiudadRel($arCliente->getCiudadRel());
                //$arTercero->setEmail($arCliente->getCorreo());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

    public function apiWindowsBuscar($raw) {
        $em = $this->getEntityManager();
        $nombre = $raw['nombre']?? null;
        $queryBuilder = $em->createQueryBuilder()->from(TteCliente::class, 'c')
            ->select('c.codigoClientePk')
            ->addSelect('c.nombreCorto')
            ->setMaxResults(10);
        if($nombre) {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%${nombre}%'");
        }
        $arClientes = $queryBuilder->getQuery()->getResult();
        return $arClientes;
    }

    public function apiWindowsDetalle($raw) {
        $em = $this->getEntityManager();
        $codigo = $raw['codigo']?? null;
        if($codigo) {
            $queryBuilder = $em->createQueryBuilder()->from(TteCliente::class, 'c')
                ->select('c.codigoClientePk')
                ->addSelect('c.nombreCorto')
                ->addSelect('c.codigoCondicionFk')
                ->addSelect('c.estadoInactivo')
                ->addSelect('c.guiaPagoContado')
                ->addSelect('c.guiaPagoCortesia')
                ->addSelect('c.guiaPagoCredito')
                ->addSelect('c.guiaPagoDestino')
                ->addSelect('c.guiaPagoRecogida');
            if($codigo) {
                $queryBuilder->where("c.codigoClientePk=" . $codigo);
            }
            $arClientes = $queryBuilder->getQuery()->getResult();
            if($arClientes) {
                return $arClientes[0];
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
                $arCliente = $em->getRepository(TteCliente::class)->findOneBy(['codigoIdentificacionFk' => $raw['codigoIdentificacionFk'], 'numeroIdentificacion' => $raw['numeroIdentificacion']]);
                if(!$arCliente) {
                    $arCliente = new TteCliente();
                    $arCliente->setIdentificacionRel($em->getReference(GenIdentificacion::class, $raw['codigoIdentificacionFk']));
                    $arCliente->setNumeroIdentificacion($raw['numeroIdentificacion']);
                    $arCliente->setNombreCorto($raw['nombreCorto']);
                    $arCliente->setNombreExtendido($raw['nombreCorto']);
                    $arCliente->setNombre1($raw['nombre1']);
                    $arCliente->setNombre2($raw['nombre2']);
                    $arCliente->setApellido1($raw['apellido1']);
                    $arCliente->setApellido2($raw['apellido2']);
                    $arCliente->setTelefono($raw['telefono']);
                    $arCliente->setDireccion($raw['direccion']);
                    $arCliente->setCorreo($raw['correo']);
                    $arCliente->setCiudadRel($em->getReference(TteCiudad::class, $raw['codigoCiudadFk']));
                    $arCliente->setCondicionRel($em->getReference(TteCondicion::class, $raw['codigoCondicionFk']));
                    $arCliente->setOperacionRel($em->getReference(TteOperacion::class, $raw['codigoOperacionFk']));
                    $arCliente->setGuiaPagoContado(1);
                    $arCliente->setGuiaPagoDestino(1);
                    $em->persist($arCliente);
                    $em->flush();
                    $em->getConnection()->commit();
                    return [
                        "codigoClientePk" => $arCliente->getCodigoClientePk()
                    ];
                } else {
                    return ["error" => 'El cliente con esta identificacion ya existe'];
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
