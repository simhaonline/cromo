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
            ['codigo' =>'TurSoporte',                           'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Operacion',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'TurFactura',                           'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'TurContrato',                          'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Juridico',            'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'TurCotizacion',                        'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'TurCierre',                            'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Financiero',          'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'TurPedido',                            'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'TurPuesto',                            'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'TurCliente',                           'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'TurItem',                              'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'TurTurno',                             'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Operacion',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'TurSecuencia',                         'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Operacion',           'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'TurPedidoTipo',                        'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 6],

            ['codigo' =>'CarMovimiento',                        'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Movimiento',          'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'CarCuentaCobrar',                      'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'CuentaCobrar',        'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'CarCompromiso',                        'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Compromiso',          'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'CarAnticipo',                          'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Anticipo',            'descontinuado' => '1',  'orden' => 4],
            ['codigo' =>'CarRecibo',                            'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Recibo',              'descontinuado' => '1',  'orden' => 5],
            ['codigo' =>'CarAplicacion',                        'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Aplicacion',          'descontinuado' => '1',  'orden' => 6],
            ['codigo' =>'CarCliente',                           'modulo'=> 'Cartera',                 'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'CarCuentaCobrarTipo',                  'modulo'=> 'Cartera',                 'funcion' => 'administracion',           'grupo' => 'CuentaCobrar',        'descontinuado' => '0',  'orden' => 2],

            ['codigo' =>'DocArchivo',                           'modulo'=> 'Documental',              'funcion' => 'administracion',           'grupo' => 'Documental',           'descontinuado' => '0',  'orden' => 1],

            ['codigo' =>'FinRegistro',                          'modulo'=> 'Financiero',              'funcion' => 'movimiento',               'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'FinAsiento',                           'modulo'=> 'Financiero',              'funcion' => 'movimiento',               'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'FinCentroCosto',                       'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'CentroCosto',         'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'FinComprobante',                       'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'FinCuenta',                            'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'FinTercero',                           'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'Tercero',             'descontinuado' => '0',  'orden' => 4],



            ['codigo' =>'GenBanco',                             'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'GenCiudad',                            'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'GenCuenta',                            'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'GenDepartamento',                      'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'GenEntidad',                           'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'GenEstadoCivil',                       'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'GenEstudioTipo',                       'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'GenFormaPago',                         'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'GenIdentificacion',                    'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'GenLog',                               'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Log',                 'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'GenModelo',                            'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'GenNotificacionTipo',                  'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'NotificacionTipo',    'descontinuado' => '0',  'orden' => 12],
            ['codigo' =>'GenNotificacion',                      'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Notificacion',        'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'GenPais',                              'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'GenReligion',                          'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'GenSexo',                              'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 16],
            ['codigo' =>'GenTarea',                             'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Tarea',               'descontinuado' => '0',  'orden' => 17],
            ['codigo' =>'GenFormato',                           'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Calidad',             'descontinuado' => '0',  'orden' => 18],

            ['codigo' =>'InvServicio',                          'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Control',             'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'InvRemisionTipo',                      'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'InvRemision',                          'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'InvPedidoTipo',                        'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'InvPedido',                            'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'InvOrdenTipo',                         'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Compra',              'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'InvOrden',                             'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Compra',              'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'InvImportacion',                       'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Extranjero',          'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'InvCotizacionTipo',                    'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'InvCotizacion',                        'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'InvCotizacionDetalle',                 'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'InvBodega',                            'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'InvBodegaUsuario',                     'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'InvDocumento',                         'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'InvDocumentoTipo',                     'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'InvFacturaTipo',                       'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'InvGrupo',                             'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'InvItem',                              'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'InvLinea',                             'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'InvMarca',                             'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'InvMovimiento',                        'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'InvPrecio',                            'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'InvServicioTipo',                      'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Control',             'descontinuado' => '0',  'orden' => 12],
            ['codigo' =>'InvSubgrupo',                          'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'InvSucursal',                          'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'InvTercero',                           'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'InvContacto',                          'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 16],

            ['codigo' =>'RhuSolicitud',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'RhuAspirante',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'RhuSeleccion',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'RhuRequisito',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Contratacion',        'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'RhuExamen',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Contratacion',        'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'RhuProgramacion',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'RhuPago',                              'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'RhuLiquidacion',                       'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'RhuAdicional',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'RhuAdicionalPeriodo',                  'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'RhuVacacion',                          'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'RhuEmbargo',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 12],
            ['codigo' =>'RhuCredito',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'RhuIncapacidad',                       'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'RhuLicencia',                          'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'RhuReclamo',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 16],
            ['codigo' =>'RhuAporte',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'SeguridadSocial',     'descontinuado' => '0',  'orden' => 17],
            ['codigo' =>'RhuAccidente',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Ocupacional',         'descontinuado' => '0',  'orden' => 18],
            ['codigo' =>'RhuIncidente',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 19],
            ['codigo' =>'RhuDisciplinario',                     'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 20],
            ['codigo' =>'RhuCapacitacion',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 21],
            ['codigo' =>'RhuDesempeno',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 22],
            ['codigo' =>'RhuPermiso',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 23],
            ['codigo' =>'RhuAcreditacion',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 24],
            ['codigo' =>'RhuEstudio',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 25],
            ['codigo' =>'RhuVisita',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 26],
            ['codigo' =>'RhuPsicotecnica',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 27],
            ['codigo' =>'RhuPoligrafia',                        'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 28],
            ['codigo' =>'RhuInduccion',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 29],
            ['codigo' =>'RhuCierre',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Financiero',          'descontinuado' => '0',  'orden' => 30],
            ['codigo' =>'RhuCargo',                             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'RhuCentroTrabajo',                     'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'RhuCierreSeleccionMotivo',             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'RhuClasificacionRiesgo',               'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'SeguridadSocial',     'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'RhuConcepto',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'RhuContrato',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'RhuContratoMotivo',                    'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'RhuContratoTipo',                      'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'RhuCostoClase',                        'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Costo',               'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'RhuCreditoTipo',                       'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'RhuEmbargoJuzgado',                    'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'RhuEmbargoPago',                       'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 12],
            ['codigo' =>'RhuEmbargoTipo',                       'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'RhuEmpleado',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'RhuEntidadExamen',                     'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Examen',              'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'RhuEntidad',                           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 16],
            ['codigo' =>'RhuGrupo',                             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 17],
            ['codigo' =>'RhuPagoTipo',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 18],
            ['codigo' =>'RhuPension',                           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 19],
            ['codigo' =>'RhuRh',                                'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 20],
            ['codigo' =>'RhuSalud',                             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 21],
            ['codigo' =>'RhuSeleccionEntrevistaTipo',           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 22],
            ['codigo' =>'RhuSeleccionPruebaTipo',               'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 23],
            ['codigo' =>'RhuSeleccionReferenciaTipo',           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 24],
            ['codigo' =>'RhuSeleccionSeleccionTipo',            'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 25],
            ['codigo' =>'RhuSolicitud',                         'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 26],
            ['codigo' =>'RhuSolicitudExperiencia',              'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 27],
            ['codigo' =>'RhuSolicitudMotivo',                   'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 28],
            ['codigo' =>'RhuAportePeriodo',                     'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 29],
            ['codigo' =>'RhuSubTipoCotizante',                  'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 30],
            ['codigo' =>'RhuSucursal',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seguridad Social',    'descontinuado' => '0',  'orden' => 31],
            ['codigo' =>'RhuTiempo',                            'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 32],
            ['codigo' =>'RhuTipoCotizacion',                    'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 33],

            ['codigo' => 'TesCuentaPagar',                      'modulo'=> 'Tesoreria',               'funcion' => 'movimiento',               'grupo' => 'Pagar',               'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TesMovimiento',                       'modulo'=> 'Tesoreria',               'funcion' => 'movimiento',               'grupo' => 'Movimiento',          'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'TesTercero',                          'modulo'=> 'Tesoreria',               'funcion' => 'administracion',           'grupo' => 'Tercero',             'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TesCuentaPagarTipo',                  'modulo'=> 'Tesoreria',               'funcion' => 'administracion',           'grupo' => 'Administracion',      'descontinuado' => '0',  'orden' => 2],

            ['codigo' => 'TteRecogida',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TteDespachoRecogida',                 'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'TteGuia',                             'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 3],
            ['codigo' => 'TteDespacho',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 4],
            ['codigo' => 'TteCumplido',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 5],
            ['codigo' => 'TteDocumental',                       'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 6],
            ['codigo' => 'TteNovedad',                          'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 7],
            ['codigo' => 'TteRelacionCaja',                     'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Control',             'descontinuado' => '0',  'orden' => 8],
            ['codigo' => 'TteMonitoreo',                        'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Monitoreo',           'descontinuado' => '0',  'orden' => 9],
            ['codigo' => 'TteFactura',                          'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 10],
            ['codigo' => 'TteIntermediacion',                   'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Financiero',          'descontinuado' => '0',  'orden' => 11],
            ['codigo' => 'TteCumplidoTipo',                     'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 12],
            ['codigo' => 'TteRecaudoCobro',                     'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 13],
            ['codigo' => 'TteRecaudoDevolucion',                'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 14],
            ['codigo' => 'TteProducto',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento'    ,           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 15],
            ['codigo' => 'TteAseguradora',                      'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TteAuxiliar',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'TteCiudad',                           'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 3],
            ['codigo' => 'TteCliente',                          'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' => 'TteColor',                            'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 5],
            ['codigo' => 'TteCondicion',                        'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 6],
            ['codigo' => 'TteConductor',                        'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Conductor',           'descontinuado' => '0',  'orden' => 7],
            ['codigo' => 'TteDepartamento',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 8],
            ['codigo' => 'TteDespachoAdicional',                'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 9],
            ['codigo' => 'TteDespachoRecogidaTipo',             'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 10],
            ['codigo' => 'TteDespachoTipo',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 11],
            ['codigo' => 'TteDestinatario',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Guia',                'descontinuado' => '0',  'orden' => 12],
            ['codigo' => 'TteEmpaque',                          'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Guia',                'descontinuado' => '0',  'orden' => 13],
            ['codigo' => 'TteFacturaTipo',                      'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 14],
            ['codigo' => 'TteGuiaTipo',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 15],
            ['codigo' => 'TteLinea',                            'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 16],
            ['codigo' => 'TteMarca',                            'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 17],
            ['codigo' => 'TteNovedadTipo',                      'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 18],
            ['codigo' => 'TteOperacion',                        'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Operacion',           'descontinuado' => '0',  'orden' => 19],
            ['codigo' => 'TtePoseedor',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 20],
            ['codigo' => 'TtePrecio',                           'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 21],
            ['codigo' => 'TteRecogidaProgramada',               'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 22],
            ['codigo' => 'TteRedespacho',                       'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Despacho',            'descontinuado' => '0',  'orden' => 23],
            ['codigo' => 'TteRelacionCaja',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Control',             'descontinuado' => '0',  'orden' => 24],
            ['codigo' => 'TteRuta',                             'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 25],
            ['codigo' => 'TteRutaRecogida',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Trasnporte',          'descontinuado' => '0',  'orden' => 26],
            ['codigo' => 'TteServicio',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 27],
            ['codigo' => 'TteTipoCarroceria',                   'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 28],
            ['codigo' => 'TteTipoCombustible',                  'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 29],
            ['codigo' => 'TteVehiculo',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 30],
            ['codigo' => 'TteZona',                             'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Guia',                'descontinuado' => '0',  'orden' => 31],

            ['codigo' => 'CrmVisita',                           'modulo'=> 'Crm',                     'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'CrmNegocio',                          'modulo'=> 'Crm',                     'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'CrmVisitaTipo',                       'modulo'=> 'Crm',                     'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'CrmFase',                             'modulo'=> 'Crm',                     'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'CrmContacto',                         'modulo'=> 'Crm',                     'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 3],
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

