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
            ['codigo' =>'TurCotizacion',                        'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'TurCierre',                            'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Financiero',          'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'TurContrato',                          'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Juridico',            'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'TurSoporte',                           'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Operacion',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'TurFactura',                           'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'TurPedido',                            'modulo'=> 'Turno',                   'funcion' => 'movimiento',               'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'TurCliente',                           'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'TurPuesto',                            'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'TurPedidoTipo',                        'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'TurSecuencia',                         'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Operacion',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'TurTurno',                             'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Operacion',           'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'TurItem',                              'modulo'=> 'Turno',                   'funcion' => 'administracion',           'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 6],

            ['codigo' =>'CarAnticipo',                          'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Anticipo',            'descontinuado' => '1',  'orden' => 1],
            ['codigo' =>'CarAplicacion',                        'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Aplicacion',          'descontinuado' => '1',  'orden' => 2],
            ['codigo' =>'CarCompromiso',                        'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Compromiso',          'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'CarCuentaCobrar',                      'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'CuentaCobrar',        'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'CarCuentaCobrarTipo',                  'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'CuentaCobrar',        'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'CarMovimiento',                        'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Movimiento',          'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'CarRecibo',                            'modulo'=> 'Cartera',                 'funcion' => 'movimiento',               'grupo' => 'Recibo',              'descontinuado' => '1',  'orden' => 7],
            ['codigo' =>'CarCliente',                           'modulo'=> 'Cartera',                 'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],

            ['codigo' =>'DocArchivo',                           'modulo'=> 'Documental',              'funcion' => 'administracion',           'grupo' => 'Documental',           'descontinuado' => '0',  'orden' => 1],

            ['codigo' =>'FinAsiento',                           'modulo'=> 'Financiero',              'funcion' => 'movimiento',               'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'FinRegistro',                          'modulo'=> 'Financiero',              'funcion' => 'movimiento',               'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'FinCentroCosto',                       'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'CentroCosto',         'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'FinCuenta',                            'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'FinComprobante',                       'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'Contabilidad',        'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'FinTercero',                           'modulo'=> 'Financiero',              'funcion' => 'administracion',           'grupo' => 'Tercero',             'descontinuado' => '0',  'orden' => 4],

            ['codigo' =>'GenFormato',                           'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Calidad',             'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'GenBanco',                             'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'GenCiudad',                            'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'GenCuenta',                            'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'GenDepartamento',                      'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'GenEntidad',                           'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'GenEstadoCivil',                       'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'GenEstudioTipo',                       'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'GenFormaPago',                         'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'GenIdentificacion',                    'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'GenLog',                               'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Log',                 'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'GenModelo',                            'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 12],
            ['codigo' =>'GenNotificacionTipo',                  'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'NotificacionTipo',    'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'GenNotificacion',                      'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Notificacion',        'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'GenPais',                              'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'GenReligion',                          'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 16],
            ['codigo' =>'GenSexo',                              'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 17],
            ['codigo' =>'GenTarea',                             'modulo'=> 'General',                 'funcion' => 'administracion',           'grupo' => 'Tarea',               'descontinuado' => '0',  'orden' => 18],

            ['codigo' =>'InvMovimiento',                        'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'InvCotizacion',                        'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'InvPedido',                            'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'InvRemision',                          'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'InvOrden',                             'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Compra',              'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'InvServicio',                          'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Control',             'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'InvImportacion',                       'modulo'=> 'Inventario',              'funcion' => 'movimiento',               'grupo' => 'Extranjero',          'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'InvOrdenTipo',                         'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Compra',              'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'InvRemisionTipo',                      'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'InvPedidoTipo',                        'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'InvCotizacionTipo',                    'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'InvServicioTipo',                      'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Control',             'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'InvPrecio',                            'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'InvSucursal',                          'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'InvBodega',                            'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'InvBodegaUsuario',                     'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'InvDocumento',                         'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'InvGrupo',                             'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'InvLinea',                             'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'InvMarca',                             'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'InvSubgrupo',                          'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'Inventario',          'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'InvContacto',                          'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'InvItem',                              'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'InvTercero',                           'modulo'=> 'Inventario',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 16],

            ['codigo' =>'RhuExamen',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Contratacion',        'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'RhuPsicotecnica',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Contratacion',        'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'RhuRequisito',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Contratacion',        'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'RhuCierre',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Financiero',          'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'RhuAdicional',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'RhuAdicionalPeriodo',                  'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'RhuCredito',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'RhuEmbargo',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'RhuIncapacidad',                       'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'RhuLicencia',                          'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'RhuLiquidacion',                       'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'RhuPago',                              'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 12],
            ['codigo' =>'RhuProgramacion',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'RhuReclamo',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'RhuVacacion',                          'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'RhuAccidente',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Ocupacional',         'descontinuado' => '0',  'orden' => 16],
            ['codigo' =>'RhuAcreditacion',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 17],
            ['codigo' =>'RhuCapacitacion',                      'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 18],
            ['codigo' =>'RhuDesempeno',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 19],
            ['codigo' =>'RhuDisciplinario',                     'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 20],
            ['codigo' =>'RhuEstudio',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 21],
            ['codigo' =>'RhuIncidente',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 22],
            ['codigo' =>'RhuInduccion',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 23],
            ['codigo' =>'RhuPermiso',                           'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 24],
            ['codigo' =>'RhuPoligrafia',                        'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 25],
            ['codigo' =>'RhuVisita',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 26],
            ['codigo' =>'RhuSolicitud',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 27],
            ['codigo' =>'RhuAporte',                            'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'SeguridadSocial',     'descontinuado' => '0',  'orden' => 28],
            ['codigo' =>'RhuAspirante',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 29],
            ['codigo' =>'RhuSeleccion',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 30],
            ['codigo' =>'RhuSolicitud',                         'modulo'=> 'RecursoHumano',           'funcion' => 'movimiento',               'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 31],
            ['codigo' =>'RhuCostoClase',                        'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Costo',               'descontinuado' => '0',  'orden' => 1],
            ['codigo' =>'RhuEntidadExamen',                     'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Examen',              'descontinuado' => '0',  'orden' => 2],
            ['codigo' =>'RhuCargo',                             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 3],
            ['codigo' =>'RhuCentroTrabajo',                     'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 4],
            ['codigo' =>'RhuEntidad',                           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 5],
            ['codigo' =>'RhuGrupo',                             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 6],
            ['codigo' =>'RhuAportePeriodo',                     'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 7],
            ['codigo' =>'RhuConcepto',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 8],
            ['codigo' =>'RhuContratoTipo',                      'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 9],
            ['codigo' =>'RhuCreditoTipo',                       'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 10],
            ['codigo' =>'RhuEmbargoJuzgado',                    'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 11],
            ['codigo' =>'RhuEmbargoPago',                       'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 12],
            ['codigo' =>'RhuEmbargoTipo',                       'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 13],
            ['codigo' =>'RhuPagoTipo',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 14],
            ['codigo' =>'RhuPension',                           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 15],
            ['codigo' =>'RhuRh',                                'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 16],
            ['codigo' =>'RhuSalud',                             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 17],
            ['codigo' =>'RhuSubTipoCotizante',                  'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 18],
            ['codigo' =>'RhuTiempo',                            'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 19],
            ['codigo' =>'RhuTipoCotizacion',                    'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Nomina',              'descontinuado' => '0',  'orden' => 20],
            ['codigo' =>'RhuContrato',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 21],
            ['codigo' =>'RhuContratoMotivo',                    'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 22],
            ['codigo' =>'RhuEmpleado',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Recurso',             'descontinuado' => '0',  'orden' => 23],
            ['codigo' =>'RhuClasificacionRiesgo',               'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'SeguridadSocial',     'descontinuado' => '0',  'orden' => 24],
            ['codigo' =>'RhuSucursal',                          'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seguridad Social',    'descontinuado' => '0',  'orden' => 25],
            ['codigo' =>'RhuCierreSeleccionMotivo',             'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 26],
            ['codigo' =>'RhuSeleccionEntrevistaTipo',           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 27],
            ['codigo' =>'RhuSeleccionPruebaTipo',               'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 28],
            ['codigo' =>'RhuSeleccionReferenciaTipo',           'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 29],
            ['codigo' =>'RhuSeleccionSeleccionTipo',            'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 30],
            ['codigo' =>'RhuSolicitudExperiencia',              'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 31],
            ['codigo' =>'RhuSolicitudMotivo',                   'modulo'=> 'RecursoHumano',           'funcion' => 'administracion',           'grupo' => 'Seleccion',           'descontinuado' => '0',  'orden' => 32],

            ['codigo' => 'TesCuentaPagar',                      'modulo'=> 'Tesoreria',               'funcion' => 'movimiento',               'grupo' => 'Pagar',               'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TesMovimiento',                       'modulo'=> 'Tesoreria',               'funcion' => 'movimiento',               'grupo' => 'Movimiento',          'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'TesCuentaPagarTipo',                  'modulo'=> 'Tesoreria',               'funcion' => 'administracion',           'grupo' => 'Administracion',      'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TesTercero',                          'modulo'=> 'Tesoreria',               'funcion' => 'administracion',           'grupo' => 'Administracion',      'descontinuado' => '0',  'orden' => 2],

            ['codigo' => 'TteFactura',                          'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Venta',               'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TteRelacionCaja',                     'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Control',             'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'TteIntermediacion',                   'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Financiero',          'descontinuado' => '0',  'orden' => 3],
            ['codigo' => 'TteMonitoreo',                        'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Monitoreo',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' => 'TteDespachoRecogida',                 'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 5],
            ['codigo' => 'TteRedespacho',                       'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 7],
            ['codigo' => 'TteRecogida',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 8],
            ['codigo' => 'TteRecogidaProgramada',               'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 9],
            ['codigo' => 'TteCumplido',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 10],

            ['codigo' => 'TteDespachoAdicional',                'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 12],
            ['codigo' => 'TteDespacho',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 13],
            ['codigo' => 'TteDespachoTipo',                     'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 14],
            ['codigo' => 'TteDocumental',                       'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 15],
            ['codigo' => 'TteGuia',                             'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 16],
            ['codigo' => 'TteNovedad',                          'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 17],
            ['codigo' => 'TteProducto',                         'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 18],
            ['codigo' => 'TteRecaudoCobro',                     'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 19],
            ['codigo' => 'TteRecaudoDevolucion',                'modulo'=> 'Transporte',              'funcion' => 'movimiento',               'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 20],
            ['codigo' => 'TteDespachoRecogidaTipo',             'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Recogida',            'descontinuado' => '0',  'orden' => 6],
            ['codigo' => 'TteCumplidoTipo',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 11],
            ['codigo' => 'TteCliente',                          'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'TteCondicion',                        'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'TtePrecio',                           'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 3],
            ['codigo' => 'TteFacturaTipo',                      'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 4],
            ['codigo' => 'TteServicio',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 5],
            ['codigo' => 'TteConductor',                        'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Conductor',           'descontinuado' => '0',  'orden' => 6],
            ['codigo' => 'TteCiudad',                           'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 7],
            ['codigo' => 'TteOperacion',                        'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 8],
            ['codigo' => 'TteDepartamento',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'General',             'descontinuado' => '0',  'orden' => 9],
            ['codigo' => 'TteDestinatario',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Guia',                'descontinuado' => '0',  'orden' => 10],
            ['codigo' => 'TteEmpaque',                          'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Guia',                'descontinuado' => '0',  'orden' => 11],
            ['codigo' => 'TteZona',                             'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Guia',                'descontinuado' => '0',  'orden' => 12],
            ['codigo' => 'TtePoseedor',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Poseedor',            'descontinuado' => '0',  'orden' => 13],
            ['codigo' => 'TteAuxiliar',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 14],
            ['codigo' => 'TteNovedadTipo',                      'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 15],
            ['codigo' => 'TteRuta',                             'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 16],
            ['codigo' => 'TteRutaRecogida',                     'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Trasnporte',          'descontinuado' => '0',  'orden' => 17],
            ['codigo' => 'TteAseguradora',                      'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 18],
            ['codigo' => 'TteColor',                            'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 19],
            ['codigo' => 'TteGuiaTipo',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Transporte',          'descontinuado' => '0',  'orden' => 20],
            ['codigo' => 'TteLinea',                            'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 21],
            ['codigo' => 'TteMarca',                            'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 22],
            ['codigo' => 'TteTipoCarroceria',                   'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 24],
            ['codigo' => 'TteTipoCombustible',                  'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 25],
            ['codigo' => 'TteVehiculo',                         'modulo'=> 'Transporte',              'funcion' => 'administracion',           'grupo' => 'Vehiculo',            'descontinuado' => '0',  'orden' => 26],

            ['codigo' => 'CrmNegocio',                          'modulo'=> 'Crm',                     'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'CrmVisita',                           'modulo'=> 'Crm',                     'funcion' => 'movimiento',               'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'CrmContacto',                         'modulo'=> 'Crm',                     'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 1],
            ['codigo' => 'CrmFase',                             'modulo'=> 'Crm',                     'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 2],
            ['codigo' => 'CrmVisitaTipo',                       'modulo'=> 'Crm',                     'funcion' => 'administracion',           'grupo' => 'Comercial',           'descontinuado' => '0',  'orden' => 3],
        );
        foreach ($arrModelos as $arrModelo){
            $arModelo = $manager->getRepository(\App\Entity\General\GenModelo::class)->find($arrModelo['codigo']);
            if(!$arModelo) {
                $arModelo = new \App\Entity\General\GenModelo();
                $arModelo->setCodigoModeloPk($arrModelo['codigo']);
                $arModelo->setCodigoModuloFk($arrModelo['modulo']);
                $arModelo->setCodigoFuncionFk($arrModelo['funcion']);
                $arModelo->setCodigoGrupoFk($arrModelo['grupo']);
                $arModelo->setDescontinuado($arrModelo['descontinuado']);
                $manager->persist($arModelo);
            }
        }
        $manager->flush();
    }
}

