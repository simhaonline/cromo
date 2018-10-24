<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRedespacho;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use SoapClient;
use Symfony\Component\HttpFoundation\Session\Session;

class TteRedespachoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRedespacho::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteDespacho::class, 'td')
            ->select('td.codigoDespachoPk')
            ->addSelect('td.fechaSalida')
            ->addSelect('td.numero')
            ->addSelect('td.codigoOperacionFk')
            ->addSelect('td.codigoVehiculoFk')
            ->addSelect('td.codigoRutaFk')
            ->addSelect('co.nombre AS ciudadOrigen')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('td.cantidad')
            ->addSelect('td.unidades')
            ->addSelect('td.pesoReal')
            ->addSelect('td.pesoVolumen')
            ->addSelect('td.vrFlete')
            ->addSelect('td.vrManejo')
            ->addSelect('td.vrDeclara')
            ->addSelect('td.vrFletePago')
            ->addSelect('td.vrAnticipo')
            ->addSelect('c.nombreCorto AS conductorNombre')
            ->addSelect('td.estadoAprobado')
            ->addSelect('td.estadoAutorizado')
            ->addSelect('td.estadoAnulado')
            ->addSelect('dt.nombre AS despachoTipo')
            ->addSelect('td.usuario')
            ->leftJoin('td.despachoTipoRel', 'dt')
            ->leftJoin('td.ciudadOrigenRel', 'co')
            ->leftJoin('td.ciudadDestinoRel ', 'cd')
            ->leftJoin('td.conductorRel', 'c')
            ->where('td.codigoDespachoPk <> 0');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteMovDespachoFiltroFecha') == true) {
            if ($session->get('filtroTteMovDespachoFechaDesde') != null) {
                $queryBuilder->andWhere("td.fechaSalida >= '{$session->get('filtroTteMovDespachoFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("td.fechaSalida >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroTteMovDespachoFechaHasta') != null) {
                $queryBuilder->andWhere("td.fechaSalida <= '{$session->get('filtroTteMovDespachoFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("td.fechaSalida <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        if ($session->get('filtroTteDespachoCodigoVehiculo') != '') {
            $queryBuilder->andWhere("td.codigoVehiculoFk = '{$session->get('filtroTteDespachoCodigoVehiculo')}'");
        }
        if ($session->get('filtroTteDespachoCodigo') != '') {
            $queryBuilder->andWhere("td.codigoDespachoPk = {$session->get('filtroTteDespachoCodigo')}");
        }
        if ($session->get('filtroTteDespachoNumero') != '') {
            $queryBuilder->andWhere("td.numero = {$session->get('filtroTteDespachoNumero')}");
        }
        if ($session->get('filtroTteDespachoCodigoCiudadOrigen')) {
            $queryBuilder->andWhere("td.codigoCiudadOrigenFk = {$session->get('filtroTteDespachoCodigoCiudadOrigen')}");
        }
        if ($session->get('filtroTteDespachoCodigoCiudadDestino')) {
            $queryBuilder->andWhere("td.codigoCiudadDestinoFk = {$session->get('filtroTteDespachoCodigoCiudadDestino')}");
        }
        if ($session->get('filtroTteDespachoTipo')) {
            $queryBuilder->andWhere("td.codigoDespachoTipoFk = '" . $session->get('filtroTteDespachoTipo') . "'");
        }
        if ($session->get('filtroTteDespachoOperacion')) {
            $queryBuilder->andWhere("td.codigoOperacionFk = '" . $session->get('filtroTteDespachoOperacion') . "'");
        }
        if ($session->get('filtroTteDespachoCodigoConductor')) {
            $queryBuilder->andWhere("td.codigoConductorFk = {$session->get('filtroTteDespachoCodigoConductor')}");
        }
        switch ($session->get('filtroTteDespachoEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("td.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("td.estadoAprobado = 1");
                break;
        }
        $queryBuilder->orderBy('td.fechaSalida', 'DESC');
        return $queryBuilder;

    }

}

