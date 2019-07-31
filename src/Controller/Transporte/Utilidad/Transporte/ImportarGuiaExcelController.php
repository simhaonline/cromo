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
use Doctrine\ORM\EntityRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, ['required' => false, 'data' => $session->get('filtroGuiaCodigoCliente')])
            ->add('guiaTipoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Transporte\TteGuiaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gt')
                        ->orderBy('gt.nombre', 'ASC')
                        ->where('gt.factura = 0')
                        ->andWhere('gt.cortesia = 0');
                },
                'choice_label' => 'nombre',
                'label' => 'Guia tipo:'
            ])
            ->add('fechaIngreso', DateType::class, ['label' => 'Fecha ingreso: ', 'required' => false, 'data' => date_create($session->get('filtroFechaIngreso'))])
            ->add('btnImportar', SubmitType::class, ['label' => 'Importar'])
            ->add('flArchivo', FileType::class, ['required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImportar')->isClicked()) {
                if ($form->get('flArchivo')->getData()) {
                    $arGuiaTipo = $form->get('guiaTipoRel')->getData();
                    if ($arGuiaTipo) {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', $arGuiaTipo->getCodigoGuiaTipoPk());
                    } else {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', null);
                    }
                    $session->set('filtroGuiaCodigoCliente', $form->get('codigoClienteFk')->getData() ?? '');
                    $session->set('filtroFechaIngreso', $form->get('fechaIngreso')->getData()->format('Y-m-d'));
                    $this->generarGuias($form->get('flArchivo')->getData());
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
    private function generarGuias($archivo)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $ruta = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $arCliente = $em->getRepository(TteCliente::class)->find($session->get('filtroGuiaCodigoCliente'));
        $arCondicion = $em->getRepository(TteCondicion::class)->find($arCliente->getCodigoCondicionFk());
        $arGuiaTipo = $em->getRepository(TteGuiaTipo::class)->find($session->get('filtroTteGuiaCodigoGuiaTipo'));
        $arFechaIngreso = $em->getRepository(TteCliente::class)->find($session->get('filtroFechaIngreso'));
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
                    $arGuia->setFechaIngreso($arFechaIngreso);
                    $arGuia->setUsuario($this->getUser()->getUsername());
                    $arGuia->setOperacion($this->getUser()->getCodigoOperacionFk());
                    $arGuia->setCodigoCondicionFk($arCondicion->getCodigoCondicionPk());

                    // Guia tipo
                    $arGuia->setCodigoGuiaTipoFk($arGuiaTipo->getCodigoGuiaTipoPk());

                    //$arConsecutivo = $em->getRepository(TteConsecutivo::class)->find(1);
                    //$arGuia->setCodigoGuiaPk($arConsecutivo->getGuia());
                    //$arConsecutivo->setGuia($arConsecutivo->getGuia() + 1);
                    //$em->persist($arConsecutivo);
                    if ($arCliente) {
                        $arGuia->setClienteRel($arCliente);
                    } else {
                        Mensajes::error("Debe seleccionar un cliente");
                    }

                    // Ciudad origen
                    $arGuia->setCiudadOrigenRel($this->getUser()->getOperacionRel()->getCiudadRel());

                    // Ciudad destino
                    $cell = $worksheet->getCellByColumnAndRow(1, $row);
                    $arCiudadDestino = $em->find(TteCiudad::class, $cell->getValue());
                    if (!$arCiudadDestino) {
                        $arrErrores[] = $respuesta . "la ciudad de destino '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCiudadDestinoRel($arCiudadDestino);
                    }
                    // Producto
                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $arProducto = $em->find(TteProducto::class, $cell->getValue());
                    if (!$arProducto) {
                        $arrErrores[] = $respuesta . "el producto '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuia->setCodigoProductoFk($arProducto->getCodigoProductoPk());
                    }
                    // Documento cliente
                    $cell = $worksheet->getCellByColumnAndRow(3, $row);
                    $arGuia->setClienteDocumento(substr($cell->getValue(), 0, 80));

                    // Remitente
                    $arGuia->setRemitente($arCliente->getNombreCorto());

                    // Nombre destinatario
                    $cell = $worksheet->getCellByColumnAndRow(4, $row);
                    $arGuia->setDestinatarioNombre(substr($cell->getValue(), 0, 150));

                    // Dirección destinatario
                    $cell = $worksheet->getCellByColumnAndRow(5, $row);
                    $arGuia->setDestinatarioDireccion(substr($cell->getValue(), 0, 150));

                    // Teléfono destinatario
                    $cell = $worksheet->getCellByColumnAndRow(6, $row);
                    $arGuia->setDestinatarioTelefono(substr($cell->getValue(), 0, 80));

                    // Unidades
                    $cell = $worksheet->getCellByColumnAndRow(7, $row);
                    $unidades = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setUnidades($cell->getValue());
                        $unidades = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con las unidades ingresadas";
                        $error = true;
                    }

                    // Peso real
                    $cell = $worksheet->getCellByColumnAndRow(8, $row);
                    $peso = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setPesoReal($cell->getValue());
                        $peso = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso real' ingresado";
                        $error = true;
                    }

                    // Peso volumen
                    $cell = $worksheet->getCellByColumnAndRow(9, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setPesoVolumen($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso volumen' ingresado";
                        $error = true;
                    }
                    // Valor declara
                    $cell = $worksheet->getCellByColumnAndRow(10, $row);
                    $declarado = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setVrDeclara($cell->getValue());
                        $declarado = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'valor declara' ingresado";
                        $error = true;
                    }
                    $tipoLiquidacion = "K";
                    if ($arCondicion->getPrecioPeso()) {
                        $tipoLiquidacion = "K";
                    } else if ($arCondicion->getPrecioUnidad()) {
                        $tipoLiquidacion = "U";
                    } else if ($arCondicion->getPrecioAdicional()) {
                        $tipoLiquidacion = "A";
                    }
                    if ($error == false) {
                        $arrLiquidacion = $em->getRepository(TteGuia::class)->liquidar(
                            $arCliente->getCodigoClientePk(),
                            $arCondicion->getCodigoCondicionPk(),
                            $arCondicion->getCodigoPrecioFk(),
                            $arGuia->getCiudadOrigenRel()->getCodigoCiudadPk(),
                            $arCiudadDestino->getCodigoCiudadPk(),
                            $arProducto->getCodigoProductoPk(),
                            $arCiudadDestino->getCodigoZonaFk(),
                            $tipoLiquidacion = "K",
                            $unidades,
                            $peso,
                            $declarado);
                        $arGuia->setVrFlete($arrLiquidacion['flete']);
                        $arGuia->setVrManejo($arrLiquidacion['manejo']);
                        $arGuia->setPesoFacturado($arrLiquidacion['pesoFacturado']);
                    }
                    // Valor recaudo
                    $cell = $worksheet->getCellByColumnAndRow(11, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuia->setVrRecaudo($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'valor recaudo' ingresado";
                        $error = true;
                    }

                    //Comentario
                    $cell = $worksheet->getCellByColumnAndRow(12, $row);
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

