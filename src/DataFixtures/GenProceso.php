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
            ['codigo' => 'ttep0001', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Recogida',        'nombre' => 'Programada'],
            ['codigo' => 'ttep0002', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Recogida',        'nombre' => 'Recoge'],
            ['codigo' => 'ttep0003', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Recogida',        'nombre' => 'Descarga'],
            ['codigo' => 'ttep0004', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Entrega guia'],
            ['codigo' => 'ttep0005', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Soporte guia'],
            ['codigo' => 'ttep0006', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Re-despacho'],
            ['codigo' => 'ttep0007', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Desembarco'],
            ['codigo' => 'ttep0008', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Cortesia'],
            ['codigo' => 'ttep0009', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Generar factura'],
            ['codigo' => 'ttep0010', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Entrega'],
            ['codigo' => 'ttep0011', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Soporte'],
            ['codigo' => 'ttep0012', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Transporte',      'nombre' => 'Cumplir rndc'],
            ['codigo' => 'ttep0013', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'General',         'nombre' => 'Cierre'],
            ['codigo' => 'ttep0014', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'General',         'nombre' => 'Unificar cliente'],
            ['codigo' => 'ttep0015', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Contabilidad',    'nombre' => 'Factura'],
            ['codigo' => 'ttep0016', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Contabilidad',    'nombre' => 'Despacho'],
            ['codigo' => 'ttep0017', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Contabilidad',    'nombre' => 'Despacho recogida'],
            ['codigo' => 'ttep0018', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Contabilidad',    'nombre' => 'Recaudo'],
            ['codigo' => 'ttep0019', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Venta',           'nombre' => 'Factura electronica'],
            ['codigo' => 'tteu0001', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Servicio',        'nombre' => 'Notificar novedad'],
            ['codigo' => 'tteu0002', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Servicio',        'nombre' => 'Notificar entrega'],
            ['codigo' => 'tteu0003', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Servicio',        'nombre' => 'Notificar estado'],
            ['codigo' => 'tteu0004', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Correcion guia'],
            ['codigo' => 'tteu0005', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Correcion guia valores'],
            ['codigo' => 'tteu0006', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Cargar info. guias'],
            ['codigo' => 'tteu0007', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Precio reexpedicion'],
            ['codigo' => 'tteu0008', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Imprimir guia masivo'],
            ['codigo' => 'tteu0009', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Importar guia bufalo'],
            ['codigo' => 'tteu0010', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Importar guia(Excel)'],
            ['codigo' => 'tteu0011', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Vehiculo disponible'],
            ['codigo' => 'tteu0012', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Transporte',      'nombre' => 'Correcion'],
            ['codigo' => 'ttei0001', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Servicio',        'nombre' => 'Pendiente atender'],
            ['codigo' => 'ttei0002', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Servicio',        'nombre' => 'Pendiente solucionar'],
            ['codigo' => 'ttei0003', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Servicio',        'nombre' => 'Reporte de novedades'],
            ['codigo' => 'ttei0004', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Detalle'],
            ['codigo' => 'ttei0005', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Siplatf'],
            ['codigo' => 'ttei0006', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Lista'],
            ['codigo' => 'ttei0007', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Despacho pendiente ruta'],
            ['codigo' => 'ttei0008', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Pendiente conductor'],
            ['codigo' => 'ttei0009', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Pendiente soporte'],
            ['codigo' => 'ttei0010', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Pendiente entrega'],
            ['codigo' => 'ttei0011', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Guias cliente'],
            ['codigo' => 'ttei0012', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Estado guias'],
            ['codigo' => 'ttei0013', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Desembarco'],
            ['codigo' => 'ttei0014', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Pendiente recaudo devolución'],
            ['codigo' => 'ttei0015', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Pendiente recaudo cobro'],
            ['codigo' => 'ttei0016', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Entrega por fecha'],
            ['codigo' => 'ttei0017', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Soporte por fecha'],
            ['codigo' => 'ttei0018', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Tiempo'],
            ['codigo' => 'ttei0019', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Transporte',      'nombre' => 'Costos'],
            ['codigo' => 'ttei0020', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Recogida',        'nombre' => 'Pendiente programar'],
            ['codigo' => 'ttei0021', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Recogida',        'nombre' => 'Fecha'],
            ['codigo' => 'ttei0022', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Comercial',       'nombre' => 'Produccion(cliente)'],
            ['codigo' => 'ttei0023', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Comercial',       'nombre' => 'Produccion(asesor)'],
            ['codigo' => 'ttei0024', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Comercial',       'nombre' => 'Factura'],
            ['codigo' => 'ttei0025', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Comercial',       'nombre' => 'FacturaDetalle'],
            ['codigo' => 'ttei0026', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Comercial',       'nombre' => 'Pendiente facturar'],
            ['codigo' => 'ttei0027', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Comercial',       'nombre' => 'Pendiente facturar(cliente)'],
            ['codigo' => 'ttei0028', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Control',         'nombre' => 'Factura'],
            ['codigo' => 'ttei0029', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Financiero',      'nombre' => 'Rentabilidad(despacho)'],
            ['codigo' => 'ttei0030', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Financiero',      'nombre' => 'Costos'],
            ['codigo' => 'ttet0001', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Recogida',        'nombre' => 'Recogida'],
            ['codigo' => 'ttet0002', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Resumen'],
            ['codigo' => 'ttet0003', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Pendiente'],
            ['codigo' => 'ttet0004', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Produccion'],
            ['codigo' => 'ttet0005', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Costos'],
            ['codigo' => 'ttet0006', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Resumen'],
            ['codigo' => 'ttet0007', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Despacho por mes'],
            ['codigo' => 'ttet0008', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Detalle'],
            ['codigo' => 'ttet0009', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Novedad por mes'],
            ['codigo' => 'ttet0010', 'modulo' => 'Transporte', 'tipo' => 't', 'grupo' => 'Transporte',      'nombre' => 'Resumen'],

            ['codigo' => 'rhuu0001', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Programacion', 'nombre' => 'Imprimir pago masivo'],
            ['codigo' => 'rhuu0002', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Programacion', 'nombre' => 'Intercambio de datos'],
            ['codigo' => 'rhuu0003', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Embargo', 'nombre' => 'BancoAgrario'],
            ['codigo' => 'rhuu0004', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Certificados', 'nombre' => 'Ingreso y retencion'],
            ['codigo' => 'rhup0001', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Pago', 'nombre' => 'Regenerar IBP'],
            ['codigo' => 'rhup0002', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Pago', 'nombre' => 'Regenerar provision'],
            ['codigo' => 'rhup0003', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Credito', 'nombre' => 'Regenerar'],
            ['codigo' => 'rhui0001', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Contrato', 'nombre' => 'Fecha ingreso'],
            ['codigo' => 'rhui0002', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Contrato', 'nombre' => 'Fecha terminacion'],
            ['codigo' => 'rhui0003', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Nomina', 'nombre' => 'Pago'],
            ['codigo' => 'rhui0004', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Nomina', 'nombre' => 'Pago detalle'],
            ['codigo' => 'rhui0005', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Nomina', 'nombre' => 'Credito pago'],
            ['codigo' => 'rhui0006', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Vacacion', 'nombre' => 'Pendiente'],
            ['codigo' => 'rhui0007', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Aportes', 'nombre' => 'Seguridad social'],

            ['codigo' => 'rhuu0001', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Programacion', 'nombre' => 'Imprimir pago masivo'],
            ['codigo' => 'rhuu0002', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Programacion', 'nombre' => 'Intercambio de datos'],
            ['codigo' => 'rhuu0003', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Embargo', 'nombre' => 'BancoAgrario'],
            ['codigo' => 'rhuu0004', 'modulo' => 'Transporte', 'tipo' => 'u', 'grupo' => 'Certificados', 'nombre' => 'Ingreso y retencion'],
            ['codigo' => 'rhup0001', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Pago', 'nombre' => 'Regenerar IBP'],
            ['codigo' => 'rhup0002', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Pago', 'nombre' => 'Regenerar provision'],
            ['codigo' => 'rhup0003', 'modulo' => 'Transporte', 'tipo' => 'p', 'grupo' => 'Credito', 'nombre' => 'Regenerar'],
            ['codigo' => 'rhui0001', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Contrato', 'nombre' => 'Fecha ingreso'],
            ['codigo' => 'rhui0002', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Contrato', 'nombre' => 'Fecha terminacion'],
            ['codigo' => 'rhui0003', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Nomina', 'nombre' => 'Pago'],
            ['codigo' => 'rhui0004', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Nomina', 'nombre' => 'Pago detalle'],
            ['codigo' => 'rhui0005', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Nomina', 'nombre' => 'Credito pago'],
            ['codigo' => 'rhui0006', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Vacacion', 'nombre' => 'Pendiente'],
            ['codigo' => 'rhui0007', 'modulo' => 'Transporte', 'tipo' => 'i', 'grupo' => 'Aportes', 'nombre' => 'Seguridad social'],
























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
