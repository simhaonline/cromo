<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenFormaPago;
use App\Entity\General\GenIdentificacion;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Transporte\TteCondicionManejo;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteConsecutivo;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteDesembarco;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDestinatario;
use App\Entity\Transporte\TteDocumental;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteInformeTiempo;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Entity\Transporte\TteProducto;
use App\Entity\Transporte\TteRedespacho;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteServicio;
use App\Entity\Transporte\TteZona;
use App\Entity\Turno\TurDistribucion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;

class TteInformeTiempoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteInformeTiempo::class);
    }

    public function informe()
    {
        $em = $this->getEntityManager();
        $em->createQueryBuilder()->delete(TteInformeTiempo::class, 'it')
            ->where("it.codigoInformeTiempoPk > 0")->getQuery()->execute();
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(TteGuia::class, 'g')
            ->select('g.codigoGuiaPk')
            ->addSelect('g.codigoClienteFk')
            ->addSelect('g.codigoCiudadDestinoFk')
            ->addSelect('c.nombreCorto AS cliente')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.fechaDespacho')
            ->addSelect('g.fechaEntrega')
            ->addSelect('g.fechaSoporte')
            ->addSelect('g.estadoNovedad')
            ->addSelect('g.estadoDespachado')
            ->addSelect('g.estadoEntregado')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.unidades')
            ->addSelect('cd.nombre as ciudadDestinoNombre')
            ->addSelect('cd.lunes')
            ->addSelect('cd.martes')
            ->addSelect('cd.miercoles')
            ->addSelect('cd.jueves')
            ->addSelect('cd.viernes')
            ->addSelect('cd.sabado')
            ->addSelect('cd.domingo')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->orderBy('g.fechaIngreso', 'DESC')
            ->andWhere('g.estadoAnulado = 0');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("g.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroTteFechaDesde')) {
            $queryBuilder->andWhere('g.fechaIngreso >= ' . "'{$session->get('filtroTteFechaDesde')}'");
        }
        if ($session->get('filtroTteFechaHasta')) {
            $queryBuilder->andWhere('g.fechaIngreso <= ' . "'{$session->get('filtroTteFechaHasta')}'");
        }
        $arGuias = $queryBuilder->getQuery()->getResult();
        foreach ($arGuias as $arGuia) {
            $arrDias = [];
            if ($arGuia['lunes']) {
                $arrDias[] = '1';
            }
            if ($arGuia['martes']) {
                $arrDias[] = '2';
            }
            if ($arGuia['miercoles']) {
                $arrDias[] = '3';
            }
            if ($arGuia['jueves']) {
                $arrDias[] = '4';
            }
            if ($arGuia['viernes']) {
                $arrDias[] = '5';
            }
            if ($arGuia['sabado']) {
                $arrDias[] = '6';
            }
            if ($arGuia['domingo']) {
                $arrDias[] = '7';
            }
            $diaIngreso = $arGuia['fechaIngreso']->format('N');
            $diasDespacho = 0;
            $dif = 0;
            foreach ($arrDias as $dia) {
                if ($dia >= $diaIngreso) {
                    $dif = $dia - $diaIngreso;
                    break;
                }
            }
            $fechaIngreso = $arGuia['fechaIngreso']->format('Y-m-d');
            $fechaRuta = date("Y-m-d", strtotime($fechaIngreso . "+ {$dif} days"));
            $fechaRuta = date_create($fechaRuta);
            $dias = 0;
            if ($arGuia['fechaEntrega']) {
                $fechaEntrega = date_create($arGuia['fechaEntrega']->format('Y-m-d'));
                if ($fechaRuta && $fechaEntrega) {
                    $interval = $fechaRuta->diff($fechaEntrega);
                    $dias = $interval->format('%R%a');
                }
            }

            $arInformeTiempo = new TteInformeTiempo();
            $arInformeTiempo->setFechaIngreso($arGuia['fechaIngreso']);
            $arInformeTiempo->setFechaRuta($fechaRuta);
            $arInformeTiempo->setFechaEntrega($arGuia['fechaEntrega']);
            $arInformeTiempo->setEstadoEntregado($arGuia['estadoEntregado']);
            $arInformeTiempo->setCodigoGuiaFk($arGuia['codigoGuiaPk']);
            $arInformeTiempo->setCodigoCiudadDestinoFk($arGuia['codigoCiudadDestinoFk']);
            $arInformeTiempo->setCiudadDestinoNombre($arGuia['ciudadDestinoNombre']);


            $arInformeTiempo->setDias($dias);
            $em->persist($arInformeTiempo);
        }
        $em->flush();
        $queryBuilder = $em->createQueryBuilder()->from(TteInformeTiempo::class, 'it')
            ->select('it.codigoInformeTiempoPk')
            ->addSelect('it.fechaIngreso')
            ->addSelect('it.fechaEntrega')
            ->addSelect('it.fechaRuta')
            ->addSelect('it.dias')
            ->addSelect('it.estadoEntregado')
            ->addSelect('it.codigoGuiaFk')
            ->addSelect('it.codigoCiudadDestinoFk')
            ->addSelect('it.ciudadDestinoNombre');
        return $queryBuilder;
    }
}

