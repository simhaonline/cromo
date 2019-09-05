<?php

namespace App\DataFixtures;

use App\Entity\Crm\CrmContacto;
use App\Entity\RecursoHumano\RhuAdicionalPeriodo;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\Turno\TurItem;
use App\Entity\Turno\TurPedidoTipo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenModelo extends Fixture
{
    public function load(ObjectManager $manager )
    {
        //$manager->createQueryBuilder()->delete(\App\Entity\General\GenModelo::class,'g')->getQuery()->execute();
        $arrayGenModulo= array(
            array('modulo'=>'Turno','modelo'=>array(
                'TurCliente',
                'TurContrato',
                'TurCotizacion',
                'TurPuesto',
                'TurSoporte',
                'TurSoporteDetalle',
                'TurPedido',
                'TurItem',
                'TurTurno',
                'TurFactura',
                'TurFacturaDetalle',
                'TurSecuencia',
                'TurPedidoTipo',
            )),
            array('modulo'=>'Cartera','modelo'=>array(
                'CarCliente',
                'CarConsecutivo',
                'CarCuentaCobrar',
                'CarCompromiso',
                'CarCuentaCobrarTipo',
                'CarNotaCredito',
                'CarNotaCreditoConcepto',
                'CarNotaCreditoDetalle',
                'CarNotaDebito',
                'CarNotaDebitoConcepto',
                'CarNotaDebitoDetalle',
                'CarRecibo',
                'CarReciboDetalle',
                'CarReciboTipo',
                'CarAnticipo',
                'CarAnticipoTipo',
                'CarAplicacion',
            )),
            array('modulo'=>'Compra','modelo'=>array(
                'ComCompra',
                'ComCompraDetalle',
                'ComCompraTipo',
                'ComConcepto',
                'ComConceptoTipo',
                'ComCuentaPagar',
                'ComCuentaPagarTipo',
                'ComEgreso',
                'ComEgresoDetalle',
                'ComEgresoTipo',
                'ComProveedor',
            )),
            array('modulo'=>'Documental','modelo'=>array(
                'DocArchivo',
                'DocConfiguracion',
                'DocDirectorio',
                'DocMasivo',
                'DocMasivoCarga',
                'DocMasivoTipo',
            )),
            array('modulo'=>'Financiero','modelo'=>array(
                'FinAsiento',
                'FinAsientoDetalle',
                'FinCentroConsto',
                'FinComprobante',
                'FinCuenta',
                'FinRegistro',
                'FinTercero',
            )),
            array('modulo'=>'General','modelo'=>array(
                'GenBanco',
                'GenCiudad',
                'GenConfiguracion',
                'GenCubo',
                'GenCuenta',
                'GenDepartamento',
                'GenEntidad',
                'GenEstadoCivil',
                'GenEstudioTipo',
                'GenFormaPago',
                'GenIdentificacion',
                'GenLog',
                'GenModelo',
                'GenModulo',
                'GenNotificacionTipo',
                'GenNotificacion',
                'GenPais',
                'GenReligion',
                'GenReporte',
                'GenRetencionTipo',
                'GenSexo',
                'GenTarea',
                'GenCalidadFormato',
            )),
            array('modulo'=>'Inventario','modelo'=>array(
                'InvBodega',
                'InvBodegaUsuario',
                'InvConfiguracion',
                'InvCotizacion',
                'InvCotizacionDetalle',
                'InvCotizacionTipo',
                'InvDocumento',
                'InvDocumentoTipo',
                'InvFacturaTipo',
                'InvGrupo',
                'InvInventarioValorizado',
                'InvItem',
                'InvImportacion',
                'InvLinea',
                'InvLote',
                'InvMarca',
                'InvMovimiento',
                'InvMovimientoDetalle',
                'InvOrden',
                'InvOrdenDetalle',
                'InvOrdenTipo',
                'InvPedido',
                'InvPedidoDetalle',
                'InvPedidoTipo',
                'InvPrecio',
                'InvPrecioDetalle',
                'InvRemision',
                'InvRemisionDetalle',
                'InvRemisionTipo',
                'InvServicio',
                'InvServicioTipo',
                'InvSolicitud',
                'InvSolicitudDetalle',
                'InvSolicitudTipo',
                'InvSubgrupo',
                'InvSucursal',
                'InvTercero',
                'InvImportacion',
                'InvCosto',
                'InvContacto',
            )),
            array('modulo'=>'RecursoHumano','modelo'=>array(
                'RhuAdicional',
                'RhuAdicionalPeriodo',
                'RhuAspirante',
                'RhuBanco',
                'RhuCargo',
                'RhuCentroTrabajo',
                'RhuCierreSeleccionMotivo',
                'RhuClasificacionRiesgo',
                'RhuConcepto',
                'RhuConfiguracion',
                'RhuContrato',
                'RhuContratoClase',
                'RhuContratoMotivo',
                'RhuContratoTipo',
                'RhuCostoClase',
                'RhuCostoGrupo',
                'RhuCredito',
                'RhuCreditoPago',
                'RhuCreditoTipo',
                'RhuEmbargo',
                'RhuEmbargoJuzgado',
                'RhuEmbargoPago',
                'RhuEmbargoTipo',
                'RhuEmpleado',
                'RhuExamen',
                'RhuEntidadExamen',
                'RhuEntidad',
                'RhuGrupo',
                'RhuLiquidacion',
                'RhuNovedad',
                'RhuNovedadTipo',
                'RhuPago',
                'RhuPagoDetalle',
                'RhuPagoTipo',
                'RhuPension',
                'RhuProgramacion',
                'RhuProgramacionDetalle',
                'RhuRequisito',
                'RhuRequisitoDetalle',
                'RhuRecaudo',
                'RhuReclamo',
                'RhuRh',
                'RhuSalud',
                'RhuSeleccion',
                'RhuSeleccionEntrevistaTipo',
                'RhuSeleccionPruebaTipo',
                'RhuSeleccionReferenciaTipo',
                'RhuSeleccionSeleccionTipo',
                'RhuSolicitud',
                'RhuSolicitudExperiencia',
                'RhuSolicitudMotivo',
                'RhuAportePeriodo',
                'RhuSubtipoCotizante',
                'RhuSucursal',
                'RhuTiempo',
                'RhuTipoCotizacion',
                'RhuVacacion',
                'RhuAporte',
                'RhuIncapacidad',
                'RhuLicencia',
                'RhuProvision',
                'RhuProvisionDetalle'
            )),
            array('modulo'=>'Financiero','modelo'=>array(
                'FinAsiento',
                'FinAsientoDetalle',
                'FinCentroConsto',
                'FinComprobante',
                'FinCuenta',
                'FinRegistro',
                'FinTercero',
            )),
            array('modulo'=>'Tesoreria','modelo'=>array(
                'TesTercero',
                'TesCuentaPagar',
                'TesCuentaPagarTipo',
                'TesEgreso',
                'TesMovimiento',
                'TesMovimientoTipo',
            )),
            array('modulo'=>'Seguridad','modelo'=>array(
                'SegUsuarioMOdelo',
                'Usuario',
            )),
            array('modulo'=>'Transporte','modelo'=>array(
                'TteAseguradora',
                'TteAuxiliar',
                'TteCierre',
                'TteCiudad',
                'TteCliente',
                'TteClienteCondicion',
                'TteColor',
                'TteCondicion',
                'TteConductor',
                'TteConfiguracion',
                'TteConsecutivo',
                'TteCosto',
                'TteCumplido',
                'TteCumplidoTipo',
                'TteDepartamento',
                'TteDocumental',
                'TteDespacho',
                'TteDespachoAdicional',
                'TteDespachoAdicionalConcepto',
                'TteDespachoDetalle',
                'TteDespachoRecogida',
                'TteDespachoRecogidaAuxiliar',
                'TteDespachoRecogidaTipo',
                'TteDespachoTipo',
                'TteDestinatario',
                'TteEmpaque',
                'TteFactura',
                'TteFacturaDetalle',
                'TteFacturaOtro',
                'TteFacturaPlantilla',
                'TteFacturaTipo',
                'TteGuia',
                'TteGuiaCarga',
                'TteGuiaCliente',
                'TteGuiaDetalle',
                'TteGuiaTipo',
                'TteLinea',
                'TteMarca',
                'TteMonitoreo',
                'TteMonitoreoDetalle',
                'TteMonitoreoRegistro',
                'TteNovedad',
                'TteNovedadTipo',
                'TteOperacion',
                'TtePoseedor',
                'TtePrecio',
                'TtePrecioDetalle',
                'TteProducto',
                'TteRecaudoCobro',
                'TteRecaudoDevolucion',
                'TteRecibo',
                'TteRecogida',
                'TteRecogidaProgramada',
                'TteRedespacho',
                'TteRelacionCaja',
                'TteRuta',
                'TteRutaRecogida',
                'TteServicio',
                'TteTipoCarroceria',
                'TteTipoCombustible',
                'TteVehiculo',
                'TteZona',
                'TteIntermediacion',
            )),
            array('modulo'=>'Crm','modelo'=>array(
               'CrmVisita',
               'CrmVisitaTipo',
               'CrmFase',
               'CrmNegocio',
               'CrmContacto'
            )),
        );
        foreach ($arrayGenModulo as $arrGenModulo){
            foreach ($arrGenModulo['modelo'] as $arrGenModelo){
                $arModelo = $manager->getRepository(\App\Entity\General\GenModelo::class)->find($arrGenModelo);
                if(!$arModelo) {
                    $arModelo = new \App\Entity\General\GenModelo();
                    $arModelo->setCodigoModeloPk($arrGenModelo);
                    $arModelo->setCodigoModuloFk($arrGenModulo['modulo']);
                    $manager->persist($arModelo);
                }
            }
        }
        $manager->flush();
    }
}

