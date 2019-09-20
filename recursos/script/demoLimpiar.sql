DELETE FROM tte_recogida;
DELETE FROM tte_despacho_recogida;
DELETE FROM tte_novedad;
DELETE FROM tte_monitoreo_registro;
DELETE FROM tte_monitoreo_detalle;
DELETE FROM tte_monitoreo;
DELETE FROM tte_factura_detalle WHERE tte_factura_detalle.codigo_factura_detalle_fk IS NOT NULL;
DELETE FROM tte_factura_detalle;
DELETE FROM tte_factura_detalle_concepto;
DELETE FROM tte_despacho_detalle;
DELETE FROM tte_recibo;
DELETE FROM tte_costo;
DELETE FROM tte_desembarco;
DELETE FROM tte_redespacho;
DELETE FROM tte_guia;
DELETE FROM tte_despacho;
DELETE FROM tte_factura;
DELETE FROM tte_recibo;
DELETE FROM tte_cumplido;
DELETE FROM tte_relacion_caja;
DELETE FROM fin_registro;
DELETE FROM car_aplicacion;
DELETE FROM car_recibo_detalle;
DELETE FROM car_recibo;
DELETE FROM car_cuenta_cobrar;
DELETE FROM car_cliente;
DELETE FROM tes_cuenta_pagar;
DELETE FROM inv_movimiento_detalle;
DELETE FROM inv_movimiento;
DELETE FROM inv_pedido_detalle;
DELETE FROM inv_pedido;
DELETE FROM inv_orden_detalle;
DELETE FROM inv_orden;
DELETE FROM inv_solicitud_detalle;
DELETE FROM inv_solicitud;
DELETE FROM inv_lote;
DELETE FROM inv_remision_detalle WHERE inv_remision_detalle.codigo_remision_detalle_fk IS NOT NULL;
DELETE FROM inv_remision_detalle;
DELETE FROM inv_remision;
DELETE FROM inv_sucursal;
DELETE FROM rhu_pago_detalle;
DELETE FROM rhu_pago;
DELETE FROM rhu_programacion_detalle;
DELETE FROM rhu_programacion;
DELETE FROM rhu_adicional;
DELETE FROM rhu_credito;
DELETE FROM rhu_contrato;
DELETE FROM rhu_empleado;
DELETE FROM gen_log;
DELETE FROM tur_pedido_detalle;
DELETE FROM tur_pedido;
DELETE FROM tur_contrato_detalle;
DELETE FROM tur_contrato;

UPDATE inv_item SET cantidad_disponible = 0, cantidad_existencia = 0, cantidad_orden = 0, cantidad_remisionada = 0, cantidad_pedido = 0;