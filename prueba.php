<?php






if ($arRecibo->getEstadoAutorizado() == 0) {
    $this->actualizarDetalle($arrControles, $codigoRecibo);
    if ($arRecibo->getEstadoAutorizado() == 0) {
        if ($em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->numeroRegistros($codigoRecibo) > 0) {
            $error = false;
            $arReciboDetalles = $em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findBy(array('codigoReciboFk' => $codigoRecibo));
            foreach ($arReciboDetalles AS $arReciboDetalle) {
                $arCuentaCobrar = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arReciboDetalle->getCodigoCuentaCobrarFk());
                if ($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk()) {
                    $arCuentaCobrarAplicacion = $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->find($arReciboDetalle->getCodigoCuentaCobrarAplicacionFk());
                    if ($arCuentaCobrarAplicacion->getSaldo() >= $arReciboDetalle->getVrPagoAfectar()) {
                        //Cuenta por cobrar aplicacion
                        $saldo = $arCuentaCobrarAplicacion->getSaldo() - $arReciboDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaCobrarAplicacion->getOperacion();
                        $arCuentaCobrarAplicacion->setSaldo($saldo);
                        $arCuentaCobrarAplicacion->setSaldoOperado($saldoOperado);
                        $arCuentaCobrarAplicacion->setAbono($arCuentaCobrarAplicacion->getAbono() + $arReciboDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaCobrarAplicacion);
                        //Cuenta por cobrar
                        $saldo = $arCuentaCobrar->getSaldo() - $arReciboDetalle->getVrPagoAfectar();
                        $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                        $arCuentaCobrar->setSaldo($saldo);
                        $arCuentaCobrar->setSaldoOperado($saldoOperado);
                        $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arReciboDetalle->getVrPagoAfectar());
                        $em->persist($arCuentaCobrar);
                    } else {
                        $objMensaje->Mensaje('error', 'El valor a afectar del documento aplicacion ' . $arCuentaCobrarAplicacion->getNumeroDocumento() . " supera el saldo desponible: " . $arCuentaCobrarAplicacion->getSaldo());
                        $error = true;
                        break;
                    }
                } else {
                    $saldo = $arCuentaCobrar->getSaldo() - $arReciboDetalle->getVrPagoAfectar();
                    $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                    $arCuentaCobrar->setSaldo($saldo);
                    $arCuentaCobrar->setSaldoOperado($saldoOperado);
                    $arCuentaCobrar->setAbono($arCuentaCobrar->getAbono() + $arReciboDetalle->getVrPagoAfectar());
                    $em->persist($arCuentaCobrar);
                }
//                                    } else {
//                                        $objMensaje->Mensaje('error', 'El pago de la factura ' . $arCuentaCobrar->getNumeroDocumento() . " por " . $arReciboDetalle->getVrPagoAfectar() . " supera el saldo desponible para afectar " . $arCuentaCobrar->getSaldo());
//                                        $error = true;
//                                        break;
//                                    }
            }
            if ($error == false) {
                $arRecibo->setEstadoAutorizado(1);
                $em->persist($arRecibo);
                $em->flush();
            }
        } else {
            $objMensaje->Mensaje('error', 'Debe adicionar detalles al recibo de caja');
        }
    }
    return $this->redirect($this->generateUrl('brs_car_movimiento_recibo_detalle', array('codigoRecibo' => $codigoRecibo)));
} else {
    $objMensaje->Mensaje('error', 'No se puede autorizar, el documento ya esta autorizado');
}