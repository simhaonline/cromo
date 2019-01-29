<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Entity\General\GenConfiguracion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteProducto;
use App\Entity\Transporte\TteServicio;
use App\Utilidades\Mensajes;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GenerarGuiaController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @Route("/transporte/utilidad/transporte/guia/generar/excel", name="transporte_utilidad_transporte_guia_generar_excel")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar'])
            ->add('flArchivo', FileType::class, ['required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                if($form->get('flArchivo')->getData()){
                    $this->generarGuias($form->get('flArchivo')->getData());
                }
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaGenerarFactura(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/utilidad/transporte/importarGuiaExcel/generarGuia.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $archivo
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function generarGuias($archivo)
    {
        $em = $this->getDoctrine()->getManager();
        $ruta = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
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
                    $arGuia = new TteGuia();
                    $respuesta = "La guia en la fila {$row} tiene un error, ";

                    // Guia tipo
                    $cell = $worksheet->getCellByColumnAndRow(1, $row);
                    $arGuiaTipo = $em->find(TteGuiaTipo::class, $cell->getValue());
                    if (!$arGuiaTipo) {
                        $arrErrores[] = $respuesta . "el codigoGuiaTipo '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setGuiaTipoRel($arGuiaTipo);
                    }

                    // Operación ingreso
                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $arOperacionIngreso = $em->find(TteOperacion::class, $cell->getValue());
                    if (!$arOperacionIngreso) {
                        $arrErrores[] = $respuesta . "la operación de ingreso '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setOperacionIngresoRel($arOperacionIngreso);
                    }

                    // Operación cargo
                    $cell = $worksheet->getCellByColumnAndRow(3, $row);
                    $arOperacionCargo = $em->find(TteOperacion::class, $cell->getValue());
                    if (!$arOperacionCargo) {
                        $arrErrores[] = $respuesta . "la operación de cargo '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setOperacionCargoRel($arOperacionCargo);
                    }

                    // Cliente
                    $cell = $worksheet->getCellByColumnAndRow(4, $row);
                    $arCliente = $em->find(TteCliente::class, $cell->getValue());
                    if (!$arCliente) {
                        $arrErrores[] = $respuesta . "el cliente '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setClienteRel($arCliente);
                    }

                    // Ciudad origen
                    $cell = $worksheet->getCellByColumnAndRow(5, $row);
                    $arCiudadOrigen = $em->find(TteCiudad::class, $cell->getValue());
                    if (!$arCiudadOrigen) {
                        $arrErrores[] = $respuesta . "la ciudad de origen '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCiudadOrigenRel($arCiudadOrigen);
                    }

                    // Ciudad destino
                    $cell = $worksheet->getCellByColumnAndRow(6, $row);
                    $arCiudadDestino = $em->find(TteCiudad::class, $cell->getValue());
                    if (!$arCiudadDestino) {
                        $arrErrores[] = $respuesta . "la ciudad de destino '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCiudadDestinoRel($arCiudadDestino);
                    }

                    // Servicio
                    $cell = $worksheet->getCellByColumnAndRow(7, $row);
                    $arServicio = $em->find(TteServicio::class, $cell->getValue());
                    if (!$arServicio) {
                        $arrErrores[] = $respuesta . "el servicio '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setServicioRel($arServicio);
                    }

                    // Producto
                    $cell = $worksheet->getCellByColumnAndRow(8, $row);
                    $arProducto = $em->find(TteProducto::class, $cell->getValue());
                    if (!$arProducto) {
                        $arrErrores[] = $respuesta . "el producto '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setProductoRel($arProducto);
                    }

                    // Empaque
                    $cell = $worksheet->getCellByColumnAndRow(9, $row);
                    $arEmpaque = $em->find(TteEmpaque::class, $cell->getValue());
                    if (!$arEmpaque) {
                        $arrErrores[] = $respuesta . "el empaque '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setEmpaqueRel($arEmpaque);
                    }

                    // Condición
                    $cell = $worksheet->getCellByColumnAndRow(10, $row);
                    $arCondicion = $em->find(TteCondicion::class, $cell->getValue());
                    if (!$arCondicion) {
                        $arrErrores[] = $respuesta . "la condición '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCondicionRel($arCondicion);
                    }

                    // Numero
                    $cell = $worksheet->getCellByColumnAndRow(11, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setNumero((int)$cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "el numero '{$cell->getValue()}' tiene un error";
                        $error = true;
                    }

                    // Documento cliente
                    $cell = $worksheet->getCellByColumnAndRow(12, $row);
                    $arGuia->setDocumentoCliente(substr($cell->getValue(), 0, 80));

                    // Relación cliente
                    $cell = $worksheet->getCellByColumnAndRow(13, $row);
                    $arGuia->setRelacionCliente(substr($cell->getValue(), 0, 50));

                    // Remitente
                    $cell = $worksheet->getCellByColumnAndRow(14, $row);
                    $arGuia->setRemitente(substr($cell->getValue(), 0, 80));

                    // Nombre destinatario
                    $cell = $worksheet->getCellByColumnAndRow(15, $row);
                    $arGuia->setNombreDestinatario(substr($cell->getValue(), 0, 150));

                    // Dirección destinatario
                    $cell = $worksheet->getCellByColumnAndRow(16, $row);
                    $arGuia->setDireccionDestinatario(substr($cell->getValue(), 0, 150));

                    // Teléfono destinatario
                    $cell = $worksheet->getCellByColumnAndRow(17, $row);
                    $arGuia->setTelefonoDestinatario(substr($cell->getValue(), 0, 80));

                    // Fecha ingreso
                    $cell = $worksheet->getCellByColumnAndRow(18, $row);
                    $strFecha = str_replace(['"',"'",'”',"’"], '', $cell->getValue());
                    $fecha = date_create($strFecha);
                    if (!is_bool($fecha) && $fecha) {
                        $arGuia->setFechaIngreso($fecha);
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con la fecha ingresada";
                        $error = true;
                    }

                    // Unidades
                    $cell = $worksheet->getCellByColumnAndRow(19, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setUnidades($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con las unidades ingresadas";
                        $error = true;
                    }

                    // Peso real
                    $cell = $worksheet->getCellByColumnAndRow(20, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setPesoReal($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso real' ingresado";
                        $error = true;
                    }

                    // Peso volumen
                    $cell = $worksheet->getCellByColumnAndRow(21, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setPesoVolumen($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso volumen' ingresado";
                        $error = true;
                    }

                    // Peso facturado
                    $cell = $worksheet->getCellByColumnAndRow(22, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setPesoFacturado($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso facturado' ingresado";
                        $error = true;
                    }

                    // Valor declara
                    $cell = $worksheet->getCellByColumnAndRow(23, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setVrDeclara($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'valor declara' ingresado";
                        $error = true;
                    }

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
                        $em->getRepository(TteGuia::class)->actualizarNumeros($arrGuias);
                        Mensajes::success('Guias creadas con éxito');
                    } catch (\Exception $exception) {
                        Mensajes::error('Ha ocurrido un problema al insertar las guias, por favor contacte con soporte y agregue en su reporte el siguiente error: '. $exception->getMessage());
                    }
                } else {
                    Mensajes::error('Se han generado los siguientes errores: '.implode(", ",$arrErrores));
                }
            }
        }
    }
}

