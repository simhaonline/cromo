<?php

namespace App\Repository\Transporte;

use App\Entity\Financiero\FinTercero;
use App\Entity\Transporte\TteCliente;
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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCliente::class, 'tc')
            ->select('tc.codigoClientePk')
            ->addSelect('tc.nombreCorto')
            ->addSelect('tc.numeroIdentificacion')
            ->addSelect('tc.telefono')
            ->addSelect('tc.movil')
            ->addSelect('tc.direccion')
            ->addSelect('tc.codigoOperacionFk')
            ->addSelect('a.nombre AS asesorNombre')
            ->addSelect('c.nombre AS ciudadNombre')
            ->addSelect('con.nombre AS condicion')
            ->addSelect('tc.codigoCondicionFk')
            ->addSelect('con.codigoPrecioFk')
            ->addSelect('p.nombre as precioNombre')
            ->leftJoin('tc.asesorRel', 'a')
            ->leftJoin('tc.ciudadRel', 'c')
            ->leftJoin('tc.condicionRel', 'con')
            ->leftJoin('con.precioRel', 'p')
            ->where('tc.codigoClientePk IS NOT NULL')
            ->orderBy('tc.codigoClientePk', 'ASC');
        if ($session->get('filtroTteNombreCliente') != '') {
            $queryBuilder->andWhere("tc.nombreCorto LIKE '%{$session->get('filtroTteNombreCliente')}%' ");
        }
        if ($session->get('filtroTteNitCliente') != '') {
            $queryBuilder->andWhere("tc.numeroIdentificacion = {$session->get('filtroTteNitCliente')} ");
        }
        if ($session->get('filtroTteCodigoCliente') != '') {
            $queryBuilder->andWhere("tc.codigoClientePk = {$session->get('filtroTteCodigoCliente')} ");
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
                $arTercero->setTelefono($arTercero->getTelefono());
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
}
