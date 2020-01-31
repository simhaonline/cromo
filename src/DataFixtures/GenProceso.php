<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenProceso extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('I');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('I');
            $arGenProcesoTipo->setNombre('INFORME');
            $manager->persist($arGenProcesoTipo);
        }
        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('P');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('P');
            $arGenProcesoTipo->setNombre('PROCESO');
            $manager->persist($arGenProcesoTipo);
        }
        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('U');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('U');
            $arGenProcesoTipo->setNombre('UTILIDAD');
            $manager->persist($arGenProcesoTipo);
        }
        $arGenProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find('O');
        if(!$arGenProcesoTipo){
            $arGenProcesoTipo = new \App\Entity\General\GenProcesoTipo();
            $arGenProcesoTipo->setCodigoProcesoTipoPk('O');
            $arGenProcesoTipo->setNombre('OTROS');
            $manager->persist($arGenProcesoTipo);
        }
        $arrProcesos1 = array(
            ['codigo' => 'ttep0001',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Recogida',             'nombre' => 'Programada'],
            ['codigo' => 'ttep0002',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Recogida',             'nombre' => 'Recoge'],
            ['codigo' => 'ttep0003',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Recogida',             'nombre' => 'Descarga'],
            ['codigo' => 'ttep0004',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Despacho',             'nombre' => 'Entrega'],
            ['codigo' => 'ttep0005',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Despacho',             'nombre' => 'Soporte'],
            ['codigo' => 'ttep0006',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Despacho',             'nombre' => 'Cumplir rndc'],
            ['codigo' => 'ttep0007',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Guia',                 'nombre' => 'Entrega guia'],
            ['codigo' => 'ttep0008',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Guia',                 'nombre' => 'Soporte guia'],
            ['codigo' => 'ttep0009',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Guia',                 'nombre' => 'Re-despacho'],
            ['codigo' => 'ttep0010',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Guia',                 'nombre' => 'Desembarco'],
            ['codigo' => 'ttep0011',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Guia',                 'nombre' => 'Cortesia'],
            ['codigo' => 'ttep0012',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Guia',                 'nombre' => 'Generar factura'],
            ['codigo' => 'ttep0013',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'General',              'nombre' => 'Cierre'],
            ['codigo' => 'ttep0014',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'General',              'nombre' => 'Unificar cliente'],
            ['codigo' => 'ttep0015',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Contabilidad',         'nombre' => 'Factura'],
            ['codigo' => 'ttep0016',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Contabilidad',         'nombre' => 'Despacho'],
            ['codigo' => 'ttep0017',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Contabilidad',         'nombre' => 'Despacho recogida'],
            ['codigo' => 'ttep0018',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Contabilidad',         'nombre' => 'Recaudo'],
            ['codigo' => 'ttep0019',           'modulo' => 'Transporte',           'tipo' => 'p',           'grupo' => 'Venta',                'nombre' => 'Factura electronica'],
            ['codigo' => 'tteu0001',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Servicio',             'nombre' => 'Notificar novedad'],
            ['codigo' => 'tteu0002',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Servicio',             'nombre' => 'Notificar entrega'],
            ['codigo' => 'tteu0003',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Servicio',             'nombre' => 'Notificar estado'],
            ['codigo' => 'tteu0004',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Correccion guia'],
            ['codigo' => 'tteu0005',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Correccion guia valores'],
            ['codigo' => 'tteu0006',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Cargar info. guias'],
            ['codigo' => 'tteu0007',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Precio reexpedicion'],
            ['codigo' => 'tteu0008',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Imprimir guia masivo'],
            ['codigo' => 'tteu0009',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Importar guia bufalo'],
            ['codigo' => 'tteu0010',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Importar guia(Excel)'],
            ['codigo' => 'tteu0011',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Guia',                 'nombre' => 'Vehiculo disponible'],
            ['codigo' => 'tteu0012',           'modulo' => 'Transporte',           'tipo' => 'u',           'grupo' => 'Recogida',             'nombre' => 'Correccion'],
            ['codigo' => 'ttei0001',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Servicio',             'nombre' => 'Pendiente atender'],
            ['codigo' => 'ttei0002',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Servicio',             'nombre' => 'Pendiente solucionar'],
            ['codigo' => 'ttei0003',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Servicio',             'nombre' => 'Reporte de novedades'],
            ['codigo' => 'ttei0004',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Despacho',             'nombre' => 'Detalle'],
            ['codigo' => 'ttei0005',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Despacho',             'nombre' => 'Siplatf'],
            ['codigo' => 'ttei0006',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Redespacho',           'nombre' => 'Lista'],
            ['codigo' => 'ttei0007',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Despacho pendiente ruta'],
            ['codigo' => 'ttei0008',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Pendiente conductor'],
            ['codigo' => 'ttei0009',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Pendiente soporte'],
            ['codigo' => 'ttei0010',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Pendiente entrega'],
            ['codigo' => 'ttei0011',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Guias cliente'],
            ['codigo' => 'ttei0012',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Estado guias'],
            ['codigo' => 'ttei0013',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Desembarco'],
            ['codigo' => 'ttei0014',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Pendiente recaudo devolucion'],
            ['codigo' => 'ttei0015',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Pendiente recaudo cobro'],
            ['codigo' => 'ttei0016',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Entrega por fecha'],
            ['codigo' => 'ttei0017',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Soporte por fecha'],
            ['codigo' => 'ttei0018',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Guia',                 'nombre' => 'Tiempo'],
            ['codigo' => 'ttei0019',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Costo',                'nombre' => 'Costos'],
            ['codigo' => 'ttei0020',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Recogida',             'nombre' => 'Pendiente programar'],
            ['codigo' => 'ttei0021',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Recogida',             'nombre' => 'Fecha'],
            ['codigo' => 'ttei0022',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Produccion(cliente)'],
            ['codigo' => 'ttei0023',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Produccion(asesor)'],
            ['codigo' => 'ttei0024',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Factura'],
            ['codigo' => 'ttei0025',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'FacturaDetalle'],
            ['codigo' => 'ttei0026',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Pendiente facturar'],
            ['codigo' => 'ttei0027',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Pendiente facturar(cliente)'],
            ['codigo' => 'ttei0028',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Control',              'nombre' => 'Factura'],
            ['codigo' => 'ttei0029',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Financiero',           'nombre' => 'Rentabilidad(despacho)'],
            ['codigo' => 'ttei0030',           'modulo' => 'Transporte',           'tipo' => 'i',           'grupo' => 'Financiero',           'nombre' => 'Costos'],
            ['codigo' => 'ttet0001',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Recogida',             'nombre' => 'Recogida'],
            ['codigo' => 'ttet0002',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Guia',                 'nombre' => 'Resumen'],
            ['codigo' => 'ttet0003',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Guia',                 'nombre' => 'Pendiente'],
            ['codigo' => 'ttet0004',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Guia',                 'nombre' => 'Produccion'],
            ['codigo' => 'ttet0005',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Guia',                 'nombre' => 'Costos'],
            ['codigo' => 'ttet0006',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Despacho',             'nombre' => 'Resumen'],
            ['codigo' => 'ttet0007',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Despacho',             'nombre' => 'Despacho por mes'],
            ['codigo' => 'ttet0008',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Detalle',              'nombre' => 'Detalle'],
            ['codigo' => 'ttet0009',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Novedad',              'nombre' => 'Novedad por mes'],
            ['codigo' => 'ttet0010',           'modulo' => 'Transporte',           'tipo' => 't',           'grupo' => 'Desembarco',           'nombre' => 'Resumen'],

            ['codigo' => 'rhuu0001',           'modulo' => 'RecursoHumano',        'tipo' => 'u',           'grupo' => 'Programacion',         'nombre' => 'Imprimir pago masivo'],
            ['codigo' => 'rhuu0002',           'modulo' => 'RecursoHumano',        'tipo' => 'u',           'grupo' => 'Programacion',         'nombre' => 'Intercambio de datos'],
            ['codigo' => 'rhuu0003',           'modulo' => 'RecursoHumano',        'tipo' => 'u',           'grupo' => 'Embargo',              'nombre' => 'Banco Agrario'],
            ['codigo' => 'rhuu0004',           'modulo' => 'RecursoHumano',        'tipo' => 'u',           'grupo' => 'Certificados',         'nombre' => 'Ingreso y retencion'],
            ['codigo' => 'rhup0001',           'modulo' => 'RecursoHumano',        'tipo' => 'p',           'grupo' => 'Pago',                 'nombre' => 'Regenerar IBP'],
            ['codigo' => 'rhup0002',           'modulo' => 'RecursoHumano',        'tipo' => 'p',           'grupo' => 'Pago',                 'nombre' => 'Regenerar provision'],
            ['codigo' => 'rhup0003',           'modulo' => 'RecursoHumano',        'tipo' => 'p',           'grupo' => 'Credito',              'nombre' => 'Regenerar'],
            ['codigo' => 'rhui0001',           'modulo' => 'RecursoHumano',        'tipo' => 'i',           'grupo' => 'Contrato',             'nombre' => 'Fecha ingreso'],
            ['codigo' => 'rhui0002',           'modulo' => 'RecursoHumano',        'tipo' => 'i',           'grupo' => 'Contrato',             'nombre' => 'Fecha terminacion'],
            ['codigo' => 'rhui0003',           'modulo' => 'RecursoHumano',        'tipo' => 'i',           'grupo' => 'Nomina',               'nombre' => 'Pago'],
            ['codigo' => 'rhui0004',           'modulo' => 'RecursoHumano',        'tipo' => 'i',           'grupo' => 'Nomina',               'nombre' => 'Pago detalle'],
            ['codigo' => 'rhui0005',           'modulo' => 'RecursoHumano',        'tipo' => 'i',           'grupo' => 'Nomina',               'nombre' => 'Credito pago'],
            ['codigo' => 'rhui0006',           'modulo' => 'RecursoHumano',        'tipo' => 'i',           'grupo' => 'Vacacion',             'nombre' => 'Pendiente'],
            ['codigo' => 'rhui0007',           'modulo' => 'RecursoHumano',        'tipo' => 'i',           'grupo' => 'Seguridad social',     'nombre' => 'Aportes'],

            ['codigo' => 'caru0001',           'modulo' => 'Cartera',              'tipo' => 'u',           'grupo' => 'Recibos',              'nombre' => 'Imprimir recibos masivos'],
            ['codigo' => 'carp0001',           'modulo' => 'Cartera',              'tipo' => 'p',           'grupo' => 'Contabilidad',         'nombre' => 'Recibo'],
            ['codigo' => 'carp0002',           'modulo' => 'Cartera',              'tipo' => 'p',           'grupo' => 'Ingreso',              'nombre' => 'Recibo masivo'],
            ['codigo' => 'carp0003',           'modulo' => 'Cartera',              'tipo' => 'p',           'grupo' => 'Cuenta cobrar',        'nombre' => 'Ajuste peso(Correccion)'],
            ['codigo' => 'carp0004',           'modulo' => 'Cartera',              'tipo' => 'p',           'grupo' => 'Cuenta cobrar',        'nombre' => 'Corregir saldos'],
            ['codigo' => 'carp0005',           'modulo' => 'Cartera',              'tipo' => 'p',           'grupo' => 'General',              'nombre' => 'Unificar cliente'],
            ['codigo' => 'cari0001',           'modulo' => 'Cartera',              'tipo' => 'i',           'grupo' => 'Cuenta cobrar',        'nombre' => 'Pendientes'],
            ['codigo' => 'cari0002',           'modulo' => 'Cartera',              'tipo' => 'i',           'grupo' => 'Recibo',               'nombre' => 'Recaudo(Asesor)'],
            ['codigo' => 'cari0003',           'modulo' => 'Cartera',              'tipo' => 'i',           'grupo' => 'Recibo',               'nombre' => 'Recaudo detalle(Asesor)'],
            ['codigo' => 'cari0004',           'modulo' => 'Cartera',              'tipo' => 'i',           'grupo' => 'Recibo',               'nombre' => 'Detalle'],

            ['codigo' => 'invp0001',           'modulo' => 'Inventario',           'tipo' => 'p',           'grupo' => 'Inventario',           'nombre' => 'Regenerar'],
            ['codigo' => 'invp0002',           'modulo' => 'Inventario',           'tipo' => 'p',           'grupo' => 'Inventario',           'nombre' => 'Corregir fecha vencimiento lote'],
            ['codigo' => 'invp0003',           'modulo' => 'Inventario',           'tipo' => 'p',           'grupo' => 'Contabilidad',         'nombre' => 'Importacion'],
            ['codigo' => 'invp0004',           'modulo' => 'Inventario',           'tipo' => 'p',           'grupo' => 'Contabilidad',         'nombre' => 'Movimiento'],
            ['codigo' => 'invp0005',           'modulo' => 'Inventario',           'tipo' => 'p',           'grupo' => 'Venta',                'nombre' => 'Facturacion electronica'],
            ['codigo' => 'invu0001',           'modulo' => 'Inventario',           'tipo' => 'u',           'grupo' => 'Lote',                 'nombre' => 'Corregir fecha vencimiento'],
            ['codigo' => 'invu0002',           'modulo' => 'Inventario',           'tipo' => 'u',           'grupo' => 'Factura',              'nombre' => 'Corregir factura'],
            ['codigo' => 'invu0003',           'modulo' => 'Inventario',           'tipo' => 'u',           'grupo' => 'Inventario',           'nombre' => 'Reliquidar'],
            ['codigo' => 'invi0001',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Item',                 'nombre' => 'Existencia'],
            ['codigo' => 'invi0002',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Item',                 'nombre' => 'Rotacion'],
            ['codigo' => 'invi0003',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Item',                 'nombre' => 'Stock bajo'],
            ['codigo' => 'invi0004',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Lote',                 'nombre' => 'Existencia'],
            ['codigo' => 'invi0005',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Movimiento',           'nombre' => 'Kardex'],
            ['codigo' => 'invi0006',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Movimiento',           'nombre' => 'Disponible'],
            ['codigo' => 'invi0007',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Movimiento',           'nombre' => 'Detalles'],
            ['codigo' => 'invi0008',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Movimiento',           'nombre' => 'Inventario valorizado'],
            ['codigo' => 'invi0009',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Remision',             'nombre' => 'Kardex'],
            ['codigo' => 'invi0010',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Precio detalle'],
            ['codigo' => 'invi0011',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Pedidos pendientes'],
            ['codigo' => 'invi0012',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Remisiones pendientes'],
            ['codigo' => 'invi0013',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Remisiones detalles'],
            ['codigo' => 'invi0014',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Ventas por asesor'],
            ['codigo' => 'invi0015',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Comercial',            'nombre' => 'Clientes bloqueados'],
            ['codigo' => 'invi0016',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Compras',              'nombre' => 'Solicitud pendiente'],
            ['codigo' => 'invi0017',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Compras',              'nombre' => 'Orden pendientes'],
            ['codigo' => 'invi0018',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Compras',              'nombre' => 'Orden detalles'],
            ['codigo' => 'invi0019',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Asesor',               'nombre' => 'Ventas'],
            ['codigo' => 'invi0020',           'modulo' => 'Inventario',           'tipo' => 'i',           'grupo' => 'Asesor',               'nombre' => 'Ventas detalle'],

            ['codigo' => 'finu0001',          'modulo' => 'Financiero',            'tipo' => 'u',           'grupo' => 'Intercambio de datos', 'nombre' => 'Registro'],
            ['codigo' => 'finu0002',          'modulo' => 'Financiero',            'tipo' => 'u',           'grupo' => 'Intercambio de datos', 'nombre' => 'Tercero'],
            ['codigo' => 'finu0003',          'modulo' => 'Financiero',            'tipo' => 'u',           'grupo' => 'Registros',            'nombre' => 'Incosistencia documento'],
            ['codigo' => 'finu0004',          'modulo' => 'Financiero',            'tipo' => 'u',           'grupo' => 'Registros',            'nombre' => 'Verificar consecutivo'],
            ['codigo' => 'fini0001',          'modulo' => 'Financiero',            'tipo' => 'i',           'grupo' => 'Contabilidad',         'nombre' => 'Registro'],
            ['codigo' => 'fini0002',          'modulo' => 'Financiero',            'tipo' => 'i',           'grupo' => 'Contabilidad',         'nombre' => 'Auxiliar'],
            ['codigo' => 'fini0003',          'modulo' => 'Financiero',            'tipo' => 'i',           'grupo' => 'Contabilidad',         'nombre' => 'Balance prueba'],

            ['codigo' => 'tesp0001',          'modulo' => 'General',               'tipo' => 'p',           'grupo' => 'General',              'nombre' => 'Unificar cliente'],
            ['codigo' => 'tesp0002',          'modulo' => 'General',               'tipo' => 'p',           'grupo' => 'Cuenta pagar',         'nombre' => 'Regenerar saldos'],
            ['codigo' => 'tesu0001',          'modulo' => 'General',               'tipo' => 'u',           'grupo' => 'Movimiento',           'nombre' => 'Imprimir movimiento masivo'],
            ['codigo' => 'tesi0001',          'modulo' => 'General',               'tipo' => 'i',           'grupo' => 'Cuenta pagar',         'nombre' => 'Pendientes'],



        );
        $arrProcesos = array(
            ['codigo' => '0001', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Informe de kardex'],
            ['codigo' => '0002', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Existencia lote'],
            ['codigo' => '0003', 'modulo' => 'Transporte', 'tipo' => 'U', 'nombre' => 'Notificar novedad'],
            ['codigo' => '0004', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Informe de kardex remisión'],
            ['codigo' => '0005', 'modulo' => 'Transporte', 'tipo' => 'U', 'nombre' => 'Corrección de guías'],
            ['codigo' => '0006', 'modulo' => 'Transporte', 'tipo' => 'O', 'nombre' => 'Generar RNDC'],
            ['codigo' => '0007', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Existencia item'],
            ['codigo' => '0008', 'modulo' => 'Inventario', 'tipo' => 'U', 'nombre' => 'Corregir fecha vencimiento lote'],
            ['codigo' => '0009', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Ventas de asesor'],
            ['codigo' => '0010', 'modulo' => 'Inventario', 'tipo' => 'I', 'nombre' => 'Ventas de asesor detalle'],
            ['codigo' => '0011', 'modulo' => 'Inventario', 'tipo' => 'U', 'nombre' => 'Corrección factura'],
            ['codigo' => '0012', 'modulo' => 'Transporte', 'tipo' => 'U', 'nombre' => 'Corrección recogida']

        );
        foreach ($arrProcesos as $arrProceso) {
            $arProceso = $manager->getRepository('App:General\GenProceso')->find($arrProceso['codigo']);
            if(!$arProceso){
                $arProcesoTipo = $manager->getRepository('App:General\GenProcesoTipo')->find($arrProceso['tipo']);
                $arProceso = new \App\Entity\General\GenProceso();
                $arProceso->setCodigoProcesoPk($arrProceso['codigo']);
                $arProceso->setCodigoModuloFk($arrProceso['modulo']);
                $arProceso->setCodigoProcesoTipoFk($arrProceso['tipo']);
                $arProceso->setProcesoTipoRel($arProcesoTipo);
                $arProceso->setNombre($arrProceso['nombre']);
                $manager->persist($arProceso);
            }
        }
        $manager->flush();



    }
}
