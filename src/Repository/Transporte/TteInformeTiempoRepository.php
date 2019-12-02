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
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;

class TteInformeTiempoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoClienteFk')
            ->addSelect('g.codigoCiudadOrigenFk')
            ->addSelect('g.codigoCiudadDestinoFk')
            ->addSelect('c.nombreCorto AS cliente')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.fechaDespacho')
            ->addSelect('g.fechaEntrega')
            ->addSelect('g.fechaSoporte')
            ->addSelect('g.estadoNovedad')
            ->addSelect('g.estadoNovedadSolucion')
            ->addSelect('g.estadoDespachado')
            ->addSelect('g.estadoEntregado')
            ->addSelect('g.vrFlete')
            ->addSelect('g.vrManejo')
            ->addSelect('g.unidades')
            ->addSelect('cd.nombre as ciudadDestinoNombre')
            ->addSelect('co.nombre as ciudadOrigenNombre')
            ->addSelect('cd.lunes')
            ->addSelect('cd.martes')
            ->addSelect('cd.miercoles')
            ->addSelect('cd.jueves')
            ->addSelect('cd.viernes')
            ->addSelect('cd.sabado')
            ->addSelect('cd.domingo')
            ->leftJoin('g.clienteRel', 'c')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->leftJoin('g.ciudadOrigenRel', 'co')
            ->orderBy('g.fechaIngreso', 'DESC')
            ->andWhere('g.estadoAnulado = 0');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("g.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroTteFechaDesde')) {
            $queryBuilder->andWhere('g.fechaIngreso >= ' . "'{$session->get('filtroTteFechaDesde')} 00:00:00'");
        }
        if ($session->get('filtroTteFechaHasta')) {
            $queryBuilder->andWhere('g.fechaIngreso <= ' . "'{$session->get('filtroTteFechaHasta')} 23:59:59'");
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

            $fechaIngreso = date("Y-m-d", strtotime($arGuia['fechaIngreso']->format('Y-m-d') . "+ 1 days"));
            $fechaIngreso = date_create($fechaIngreso);
            $diaIngreso = $fechaIngreso->format('N');

            $diaTemporal = $diaIngreso;
            $dif = 0;
            for ($i=1;$i<=7;$i++) {
                if($arrDias) {
                    if(array_search($diaTemporal, $arrDias, false)) {
                        break;
                    } else {
                        $dif++;
                        $diaTemporal++;
                    }
                    if($diaTemporal==8) {
                        $diaTemporal = 1;
                    }
                }
            }
            //$fechaIngreso = $arGuia['fechaIngreso']->format('Y-m-d');
            $fechaIngreso = $fechaIngreso->format('Y-m-d');
            $fechaRuta = date("Y-m-d", strtotime($fechaIngreso . "+ {$dif} days"));
            $fechaRuta = date_create($fechaRuta);
            $dias = 0;
            if ($arGuia['fechaEntrega']) {
                $fechaEntrega = date_create($arGuia['fechaEntrega']->format('Y-m-d'));
                if ($fechaRuta && $fechaEntrega) {
                    if($fechaEntrega>=$fechaRuta) {
                        $interval = $fechaRuta->diff($fechaEntrega);
                        $dias = $interval->format('%R%a')+1;
                    }
                }
            }

            $arInformeTiempo = new TteInformeTiempo();
            $arInformeTiempo->setFechaIngreso($arGuia['fechaIngreso']);
            $arInformeTiempo->setFechaRuta($fechaRuta);
            $arInformeTiempo->setFechaEntrega($arGuia['fechaEntrega']);
            $arInformeTiempo->setCodigoGuiaFk($arGuia['codigoGuiaPk']);
            $arInformeTiempo->setCodigoOperacionIngresoFk($arGuia['codigoOperacionIngresoFk']);
            $arInformeTiempo->setCodigoCiudadOrigenFk($arGuia['codigoCiudadOrigenFk']);
            $arInformeTiempo->setCiudadOrigenNombre($arGuia['ciudadOrigenNombre']);
            $arInformeTiempo->setCodigoCiudadDestinoFk($arGuia['codigoCiudadDestinoFk']);
            $arInformeTiempo->setCiudadDestinoNombre($arGuia['ciudadDestinoNombre']);
            $arInformeTiempo->setEstadoEntregado($arGuia['estadoEntregado']);
            $arInformeTiempo->setEstadoNovedad($arGuia['estadoNovedad']);
            $arInformeTiempo->setEstadoNovedadSolucion($arGuia['estadoNovedadSolucion']);


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
            ->addSelect('it.estadoNovedad')
            ->addSelect('it.estadoNovedadSolucion')
            ->addSelect('it.codigoGuiaFk')
            ->addSelect('it.codigoOperacionIngresoFk')
            ->addSelect('it.codigoCiudadOrigenFk')
            ->addSelect('it.ciudadOrigenNombre')
            ->addSelect('it.codigoCiudadDestinoFk')
            ->addSelect('it.ciudadDestinoNombre');
        return $queryBuilder;
    }

    public function excel(){
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteInformeTiempo::class, 'it')
            ->select('it.codigoInformeTiempoPk')
            ->addSelect('it.fechaIngreso')
            ->addSelect('it.fechaEntrega')
            ->addSelect('it.fechaRuta')
            ->addSelect('it.dias')
            ->addSelect('it.estadoEntregado')
            ->addSelect('it.codigoGuiaFk')
            ->addSelect('it.codigoOperacionIngresoFk')
            ->addSelect('it.codigoCiudadOrigenFk')
            ->addSelect('it.ciudadOrigenNombre')
            ->addSelect('it.codigoCiudadDestinoFk')
            ->addSelect('it.ciudadDestinoNombre');
        return $queryBuilder->getQuery()->getResult();
    }
}

