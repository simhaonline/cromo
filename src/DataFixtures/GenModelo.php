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
        $arrModelos = array(
            ['codigo' => 'TurCliente',              'modulo'=> 'Turno',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'TurContrato',              'modulo'=> 'Turno',     'funcion' => 'Movimiento',      'grupo' => 'Varios'],
            ['codigo' =>'TurCotizacion',            'modulo'=> 'Turno',     'funcion' => 'Movimiento',      'grupo' => 'Varios'],
            ['codigo' =>'TurPuesto',                'modulo'=> 'Turno',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'TurSoporte',               'modulo'=> 'Turno',     'funcion' => 'Movimiento',      'grupo' => 'Varios'],
            ['codigo' =>'TurPedido',                'modulo'=> 'Turno',     'funcion' => 'Movimiento',      'grupo' => 'Varios'],
            ['codigo' =>'TurItem',                  'modulo'=> 'Turno',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'TurTurno',                 'modulo'=> 'Turno',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'TurFactura',               'modulo'=> 'Turno',     'funcion' => 'Movimiento',      'grupo' => 'Varios'],
            ['codigo' =>'TurSecuencia',             'modulo'=> 'Turno',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' =>'CarCliente',               'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarCuentaCobrar',          'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarCompromiso',            'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarCuentaCobrarTipo',      'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarRecibo',                'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarReciboTipo',            'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarAnticipo',              'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarAnticipoTipo',          'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'CarAplicacion',            'modulo'=> 'Cartera',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' =>'DocArchivo',               'modulo'=> 'Documental',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' =>'FinAsiento',               'modulo'=> 'Financiero',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'FinCentroCosto',           'modulo'=> 'Financiero',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'FinComprobante',           'modulo'=> 'Financiero',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'FinCuenta',                'modulo'=> 'Financiero',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'FinRegistro',              'modulo'=> 'Financiero',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'FinTercero',               'modulo'=> 'Financiero',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' =>'GenBanco',                 'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenCiudad',                'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenCuenta',                'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenDepartamento',          'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenEntidad',               'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenEstadoCivil',           'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenEstudioTipo',           'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenFormaPago',             'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenIdentificacion',        'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenLog',                   'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenModelo',                'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenNotificacionTipo',      'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenNotificacion',          'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenPais',                  'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenReligion',              'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenSexo',                  'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenTarea',                 'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'GenCalidadFormato',        'modulo'=> 'General',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' =>'InvBodega',                'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvBodegaUsuario',         'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvCotizacion',            'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvCotizacionDetalle',     'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvCotizacionTipo',        'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvDocumento',             'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvDocumentoTipo',         'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvFacturaTipo',           'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvGrupo',                 'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvItem',                  'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvImportacion',           'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvLinea',                 'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvMarca',                 'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvMovimiento',            'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvOrden',                 'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvOrdenTipo',             'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvPedido',                'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvPedidoTipo',            'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvPrecio',                'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvRemision',              'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvRemisionTipo',          'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvServicio',              'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvServicioTipo',          'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvSolicitud',             'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvSolicitudTipo',         'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvSubgrupo',              'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvSucursal',              'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvTercero',               'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'InvContacto',              'modulo'=> 'Inventario',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' =>'RhuProgramacion',          'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuIncapacidad',           'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuLicencia',              'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuVacacion',              'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuAporte',                'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuAdicional',             'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuAdicionalPeriodo',      'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuAspirante',             'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuCredito',               'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuEmbargo',               'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuExamen',                'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuLiquidacion',           'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuPago',                  'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuRequisito',             'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuReclamo',               'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSeleccion',             'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuProvision',             'modulo'=> 'RecursoHumano',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' =>'RhuCargo',                 'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuCentroTrabajo',         'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuCierreSeleccionMotivo', 'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuClasificacionRiesgo',   'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuConcepto',              'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuContrato',              'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuContratoMotivo',        'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuContratoTipo',          'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuCostoClase',            'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuCreditoTipo',           'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuEmbargoJuzgado',        'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuEmbargoPago',           'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuEmbargoTipo',           'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuEmpleado',              'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuEntidadExamen',         'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuEntidad',               'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuGrupo',                 'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuPagoTipo',              'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuPension',               'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuRh',                    'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSalud',                 'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSeleccionEntrevistaTipo',   'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSeleccionPruebaTipo',       'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSeleccionReferenciaTipo',   'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSeleccionSeleccionTipo',    'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSolicitud',             'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSolicitudExperiencia',  'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSolicitudMotivo',       'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuAportePeriodo',         'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSubtipoCotizante',      'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuSucursal',              'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuTiempo',                'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' =>'RhuTipoCotizacion',        'modulo'=> 'RecursoHumano',     'funcion' => 'Administracion',  'grupo' => 'Varios'],


            ['codigo' => 'TesTercero',              'modulo'=> 'Tesoreria',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TesCuentaPagar',          'modulo'=> 'Tesoreria',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TesCuentaPagarTipo',      'modulo'=> 'Tesoreria',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TesEgreso',               'modulo'=> 'Tesoreria',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TesMovimiento',           'modulo'=> 'Tesoreria',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TesMovimientoTipo',       'modulo'=> 'Tesoreria',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' => 'TteCumplido',             'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteDocumental',           'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteDespacho',             'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteDespachoRecogida',     'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteFactura',              'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteGuia',                 'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteMonitoreo',            'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteRecogida',             'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteIntermediacion',       'modulo'=> 'Transporte',     'funcion' => 'Movimiento',  'grupo' => 'Varios'],
            ['codigo' => 'TteAseguradora',          'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteAuxiliar',             'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteCiudad',               'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteCliente',              'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteColor',                'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteCondicion',            'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteConductor',            'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteCumplidoTipo',         'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteDepartamento',         'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteDespachoAdicional',    'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteDespachoRecogidaTipo', 'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteDespachoTipo',         'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteDestinatario',         'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteEmpaque',              'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteFacturaTipo',          'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteGuiaTipo',             'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteLinea',                'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteMarca',                'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteNovedadTipo',          'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteOperacion',            'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TtePoseedor',             'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TtePrecio',               'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteProducto',             'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteRecaudoCobro',         'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteRecaudoDevolucion',    'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteRecogidaProgramada',   'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteRedespacho',           'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteRelacionCaja',         'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteRuta',                 'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteRutaRecogida',         'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteServicio',             'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteTipoCarroceria',       'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteTipoCombustible',      'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteVehiculo',             'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'TteZona',                 'modulo'=> 'Transporte',     'funcion' => 'Administracion',  'grupo' => 'Varios'],

            ['codigo' => 'CrmVisita',               'modulo'=> 'Crm',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'CrmVisitaTipo',           'modulo'=> 'Crm',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'CrmFase',                 'modulo'=> 'Crm',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'CrmNegocio',              'modulo'=> 'Crm',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
            ['codigo' => 'CrmContacto',             'modulo'=> 'Crm',     'funcion' => 'Administracion',  'grupo' => 'Varios'],
        );
        foreach ($arrModelos as $arrModelo){
            $arModelo = $manager->getRepository(\App\Entity\General\GenModelo::class)->find($arrModelo['codigo']);
            if(!$arModelo) {
                $arModelo = new \App\Entity\General\GenModelo();
                $arModelo->setCodigoModeloPk($arrModelo['codigo']);
                $arModelo->setCodigoModuloFk($arrModelo['modulo']);
                $arModelo->setCodigoFuncionFk($arrModelo['funcion']);
                $arModelo->setCodigoGrupoFk($arrModelo['grupo']);
                $manager->persist($arModelo);
            }
        }
        $manager->flush();
    }
}

