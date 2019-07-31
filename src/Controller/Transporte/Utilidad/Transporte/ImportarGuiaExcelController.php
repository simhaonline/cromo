<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Entity\General\GenConfiguracion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteConsecutivo;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTemporal;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteProducto;
use App\Entity\Transporte\TteServicio;
use App\Utilidades\Mensajes;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImportarGuiaExcelController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @Route("/transporte/utilidad/transporte/guia/generar/excel", name="transporte_utilidad_transporte_guia_generar_excel")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('liquidar', CheckboxType::class, ['required' => false, 'label' => 'Liquidar'])
            ->add('codigoClienteFk', TextType::class, ['required' => false, 'data' => $session->get('filtroGuiaCodigoCliente')])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar'])
            ->add('flArchivo', FileType::class, ['required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                if ($form->get('flArchivo')->getData()) {
                    $session->set('filtroGuiaCodigoCliente', $form->get('codigoClienteFk')->getData() ?? '');
                    $liquidar = $form->get('liquidar')->getData();
                    $this->generarGuias($form->get('flArchivo')->getData(), $liquidar);
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaGenerarFactura(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/utilidad/transporte/importarGuiaExcel/generarGuia.html.twig', [
            'arGuias' => $arGuias,
            'formFiltro' => $form->createView()
        ]);
    }

    /**
     * @param $archivo
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function generarGuias($archivo, $liquidar)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $ruta = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $arCliente = $em->getRepository(TteCliente::class)->find($session->get('filtroGuiaCodigoCliente'));
        $arCondicion = $em->getRepository(TteCondicion::class)->find($arCliente->getCodigoCondicionFk());
        if (!$ruta) {
            Mensajes::error('Debe de ingresar una ruta temporal en la configuracion general del sistema');
        }
        $archivo->move($ruta, "archivo.xls");
        $rutaArchivo = $ruta . "archivo.xls";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
        $arrCargas = [];
        $arrErrores = [];
        $i = 0;
        if ($reader->getSheetCount() > 1) {
            Mensajes::error('El documento debe contener solamente una hoja');
        } else {
            $arrGuias = [];
            foreach ($reader->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $error = false;
                    /**
                     * @var $arGuia TteGuiaTemporal
                     */
                    $arGuia = new TteGuiaTemporal();
                    $respuesta = "La guia en la fila {$row} tiene un error, ";
                    $arGuia->setOrigen('E');
                    // Guia tipo
                    $cell = $worksheet->getCellByColumnAndRow(1, $row);
                    $arGuiaTipo = $em->find(TteGuiaTipo::class, $cell->getValue());
                    if (!$arGuiaTipo) {
                        $arrErrores[] = $respuesta . "el codigoGuiaTipo '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCodigoGuiaTipoFk($arGuiaTipo);
                    }

                    $arConsecutivo = $em->getRepository(TteConsecutivo::class)->find(1);
                    $arGuia->setCodigoGuiaPk($arConsecutivo->getGuia());
                    $arConsecutivo->setGuia($arConsecutivo->getGuia() + 1);
                    $em->persist($arConsecutivo);

//                    // Operación ingreso
////                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
//                    $arOperacionIngreso = $em->find(TteOperacion::class, $this->getUser()->getCodigoOperacionFk());
//                    if ($arOperacionIngreso) {
//                        $arGuia->setOperacionIngresoRel($arOperacionIngreso);
//                    }
//                    // Operación cargo
////                    $cell = $worksheet->getCellByColumnAndRow(3, $row);
//                    $arOperacionCargo = $em->find(TteOperacion::class, $this->getUser()->getCodigoOperacionCargoFk());
//                    if ($arOperacionCargo) {
//                        $arGuia->setOperacionCargoRel($arOperacionCargo);
//                    }
                    // Cliente
//                    $cell = $worksheet->getCellByColumnAndRow(4, $row);
                    if ($arCliente) {
                        $arGuia->setClienteRel($arCliente);
                    } else {
                        Mensajes::error("Debe seleccionar un cliente");
                    }

                    // Ciudad origen
                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $arCiudadOrigen = $em->find(TteCiudad::class, $cell->getValue());
                    if (!$arCiudadOrigen) {
                        $arrErrores[] = $respuesta . "la ciudad de origen '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCiudadOrigenRel($arCiudadOrigen);
                    }

                    // Ciudad destino
                    $cell = $worksheet->getCellByColumnAndRow(3, $row);
                    $arCiudadDestino = $em->find(TteCiudad::class, $cell->getValue());
                    if (!$arCiudadDestino) {
                        $arrErrores[] = $respuesta . "la ciudad de destino '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCiudadDestinoRel($arCiudadDestino);
                    }

                    // Servicio
//                    $cell = $worksheet->getCellByColumnAndRow(4, $row);
//                    $arServicio = $em->find(TteServicio::class, $cell->getValue());
//                    if (!$arServicio) {
//                        $arrErrores[] = $respuesta . "el servicio '{$cell->getValue()}' no existe";
//                        $error = true;
//                    } else {
//                        $arGuia->setServicioRel($arServicio);
//                    }

                    // Producto
                    $cell = $worksheet->getCellByColumnAndRow(4, $row);
                    $arProducto = $em->find(TteProducto::class, $cell->getValue());
                    if (!$arProducto) {
                        $arrErrores[] = $respuesta . "el producto '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCodigoProductoFk($arProducto->getCodigoProductoPk());
                    }

//                    // Empaque
//                    $cell = $worksheet->getCellByColumnAndRow(6, $row);
//                    $arEmpaque = $em->find(TteEmpaque::class, $cell->getValue());
//                    if (!$arEmpaque) {
//                        $arrErrores[] = $respuesta . "el empaque '{$cell->getValue()}' no existe";
//                        $error = true;
//                    } else {
//                        $arGuia->setEmpaqueRel($arEmpaque);
//                    }

//                    // Condición
//                    if ($arCliente) {
//                        $arGuia->setCondicionRel($arCliente->getCondicionRel());
//                    } else {
//                        Mensajes::error("El cliente no tiene condicion");
//                    }

                    // Numero
                    $cell = $worksheet->getCellByColumnAndRow(5, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setNumero((int)$cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "el numero '{$cell->getValue()}' tiene un error";
                        $error = true;
                    }

                    // Documento cliente
                    $cell = $worksheet->getCellByColumnAndRow(6, $row);
                    $arGuia->setClienteDocumento(substr($cell->getValue(), 0, 80));

//                    // Relación cliente
//                    $arGuia->setRelacionCliente(Null);

                    // Remitente
                    $arGuia->setRemitente($arCliente->getNombreCorto());

                    // Nombre destinatario
                    $cell = $worksheet->getCellByColumnAndRow(7, $row);
                    $arGuia->setDestinatarioNombre(substr($cell->getValue(), 0, 150));

                    // Dirección destinatario
                    $cell = $worksheet->getCellByColumnAndRow(8, $row);
                    $arGuia->setDestinatarioDireccion(substr($cell->getValue(), 0, 150));

                    // Teléfono destinatario
                    $cell = $worksheet->getCellByColumnAndRow(9, $row);
                    $arGuia->setDestinatarioTelefono(substr($cell->getValue(), 0, 80));

                    // Fecha ingreso
                    $cell = $worksheet->getCellByColumnAndRow(10, $row);
                    $strFecha = str_replace(['"', "'", '”', "’"], '', $cell->getValue());
                    $fecha = date_create($strFecha);
                    if (!is_bool($fecha) && $fecha) {
                        $arGuia->setFechaIngreso($fecha);
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con la fecha ingresada";
                        $error = true;
                    }

                    // Unidades
                    $cell = $worksheet->getCellByColumnAndRow(11, $row);
                    $unidades = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setUnidades($cell->getValue());
                        $unidades = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con las unidades ingresadas";
                        $error = true;
                    }

                    // Peso real
                    $cell = $worksheet->getCellByColumnAndRow(12, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setPesoReal($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso real' ingresado";
                        $error = true;
                    }

                    // Peso volumen
                    $cell = $worksheet->getCellByColumnAndRow(13, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setPesoVolumen($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso volumen' ingresado";
                        $error = true;
                    }

//                    // Peso facturado
//                    $cell = $worksheet->getCellByColumnAndRow(22, $row);
//                    $pesoFacturar = 0;
//                    if (is_numeric($cell->getValue())) {
//                        $arGuia->setPesoFacturado($cell->getValue());
//                        $pesoFacturar = $cell->getValue();
//                    } else {
//                        $arrErrores[] = $respuesta . "existe un error con el 'peso facturado' ingresado";
//                        $error = true;
//                    }

                    // Valor declara
                    $cell = $worksheet->getCellByColumnAndRow(14, $row);
                    $declarado = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setVrDeclara($cell->getValue());
                        $declarado = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'valor declara' ingresado";
                        $error = true;
                    }


                    if ($error == false) {
                        if ($liquidar) {
                            $arrLiquidacion = $em->getRepository(TteGuia::class)->liquidar(
                                $arCliente->getCodigoClientePk(),
                                $arCondicion->getCodigoCondicionPk(),
                                $arCondicion->getCodigoPrecioFk(),
                                $arCiudadOrigen->getCodigoCiudadPk(),
                                $arCiudadDestino->getCodigoCiudadPk(),
                                $arProducto->getCodigoProductoPk(),
                                $arCiudadDestino->getCodigoZonaFk(),
                                $unidades,
                                $declarado);
                            $arGuia->setVrFlete($arrLiquidacion['flete']);
                            $arGuia->setVrManejo($arrLiquidacion['manejo']);
                        } else {
                            // Valor flete
                            $cell = $worksheet->getCellByColumnAndRow(24, $row);
                            if (is_numeric($cell->getValue())) {
                                $arGuia->setVrFlete($cell->getValue());
                            } else {
                                $arrErrores[] = $respuesta . "existe un error con el 'valor flete' ingresado";
                                $error = true;
                            }

                            // Valor manejo
                            $cell = $worksheet->getCellByColumnAndRow(25, $row);
                            if (is_numeric($cell->getValue())) {
                                $arGuia->setVrManejo($cell->getValue());
                            } else {
                                $arrErrores[] = $respuesta . "existe un error con el 'valor manejo' ingresado";
                                $error = true;
                            }
                        }
                    }


                    // Valor recaudo
                    $cell = $worksheet->getCellByColumnAndRow(26, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setVrRecaudo($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'valor recaudo' ingresado";
                        $error = true;
                    }

                    // Mercancia peligrosa
                    $cell = $worksheet->getCellByColumnAndRow(27, $row);
                    if ($cell->getValue() == 1 || $cell->getValue() == 0) {
                        $arGuia->setMercanciaPeligrosa($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "los valores para el campo mercancia peligrosa deben de ser unicamente '1' ó '0'";
                        $error = true;
                    }

                    // Usuario
                    $cell = $worksheet->getCellByColumnAndRow(28, $row);
                    $arUsuario = $em->getRepository(Usuario::class)->findOneBy(['username' => $cell->getValue()]);
                    if (!$arUsuario) {
                        $arrErrores[] = $respuesta . "el usuario '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setUsuario($cell->getValue());
                    }

                    //Comentario
                    $cell = $worksheet->getCellByColumnAndRow(29, $row);
                    $arGuia->setComentario(substr($cell->getValue(), 0, 2000));

                    if (!$error) {
                        $em->persist($arGuia);
                        if ($arGuia->getNumero() == 0) {
                            $arrGuias[] = $arGuia;
                        }
                    }
                    $i++;
                }
                if (count($arrErrores) == 0) {
                    try {
                        $em->flush();
                        //$em->getRepository(TteGuia::class)->actualizarNumeros($arrGuias);
                        Mensajes::success('Guias creadas con éxito');
                    } catch (\Exception $exception) {
                        Mensajes::error('Ha ocurrido un problema al insertar las guias, por favor contacte con soporte y agregue en su reporte el siguiente error: ' . $exception->getMessage());
                    }
                } else {
                    Mensajes::error('Se han generado los siguientes errores: ' . implode(", ", $arrErrores));
                }
            }
        }
    }
}

