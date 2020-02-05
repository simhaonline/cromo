<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteConsecutivo;
use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTemporal;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteProducto;
use App\Entity\Transporte\TteServicio;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
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

class ImportarGuiaExcelController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "tteu0010";




    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @Route("/transporte/utilidad/transporte/guia/generar/excel", name="transporte_utilidad_transporte_guia_generar_excel")
     */
    public function lista(Request $request,  PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

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
            ->add('ciudadOrigenRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Origen:'
            ])
            ->add('fechaIngreso', DateType::class, ['label' => 'Fecha ingreso: ', 'required' => false, 'data' => date_create($session->get('filtroFechaIngreso'))])
            ->add('btnCargar', SubmitType::class, ['label' => 'Cargar'])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar'])
            ->add('flArchivo', FileType::class, ['required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnCargar')->isClicked()) {
                if ($form->get('flArchivo')->getData()) {
                    $em->getRepository(TteGuiaTemporal::class)->createQueryBuilder('t')->delete(TteGuiaTemporal::class)->getQuery()->execute();
                    $arGuiaTipo = $form->get('guiaTipoRel')->getData();
                    if ($arGuiaTipo) {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', $arGuiaTipo->getCodigoGuiaTipoPk());
                    } else {
                        $session->set('filtroTteGuiaCodigoGuiaTipo', null);
                    }
                    $session->set('filtroGuiaCodigoCliente', $form->get('codigoClienteFk')->getData() ?? '');
                    $session->set('filtroFechaIngreso', $form->get('fechaIngreso')->getData()->format('Y-m-d'));
                    $arCiudadOrigen = $form->get('ciudadOrigenRel')->getData();
                    $this->cargarGuias($form->get('flArchivo')->getData(), $arCiudadOrigen);
                }
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    $this->generarGuias($arrSeleccionados);
                }
                $em->getRepository(TteGuiaTemporal::class)->eliminar($arrSeleccionados);
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuiaTemporal::class)->importarExcel(), $request->query->getInt('page', 1), 1000);
        return $this->render('transporte/utilidad/transporte/importarGuiaExcel/generarGuia.html.twig', [
            'arGuias' => $arGuias,
            'formFiltro' => $form->createView()
        ]);
    }

    /**
     * @param $archivo
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function cargarGuias($archivo, $arCiudadOrigen)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $ruta = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $arCliente = $em->getRepository(TteCliente::class)->find($session->get('filtroGuiaCodigoCliente'));
        $arCondicion = $em->getRepository(TteCondicion::class)->find($arCliente->getCodigoCondicionFk());
        $arGuiaTipo = $em->getRepository(TteGuiaTipo::class)->find($session->get('filtroTteGuiaCodigoGuiaTipo'));
        $arFecha = $session->get('filtroFechaIngreso');
        $fechaIngreso = date_create($arFecha);
        if (!$ruta) {
            Mensajes::error('Debe de ingresar una ruta temporal en la configuracion general del sistema');
        }
        $archivo->move($ruta, "archivo.xls");
        $rutaArchivo = $ruta . "archivo.xls";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::load($rutaArchivo);
        $arrGuias = [];
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
                    $arGuiaTemporal = new TteGuiaTemporal();
                    $respuesta = "La guia en la fila {$row} tiene un error, ";
                    $arGuiaTemporal->setOrigen('E');
                    $arGuiaTemporal->setFechaIngreso($fechaIngreso);
                    $arGuiaTemporal->setFecha(new \DateTime('now'));
                    $arGuiaTemporal->setUsuario($this->getUser()->getUsername());
                    $arGuiaTemporal->setOperacion($this->getUser()->getCodigoOperacionFk());
                    $arGuiaTemporal->setCodigoCondicionFk($arCondicion->getCodigoCondicionPk());

                    // Guia tipo
                    $arGuiaTemporal->setCodigoGuiaTipoFk($arGuiaTipo->getCodigoGuiaTipoPk());

                    //$arConsecutivo = $em->getRepository(TteConsecutivo::class)->find(1);
                    //$arGuia->setCodigoGuiaPk($arConsecutivo->getGuia());
                    //$arConsecutivo->setGuia($arConsecutivo->getGuia() + 1);
                    //$em->persist($arConsecutivo);
                    if ($arCliente) {
                        $arGuiaTemporal->setClienteRel($arCliente);
                    } else {
                        Mensajes::error("Debe seleccionar un cliente");
                    }

                    // Ciudad origen
                    if ($arCiudadOrigen) {
                        $arGuiaTemporal->setCiudadOrigenRel($arCiudadOrigen);
                    } else {
                        $arGuiaTemporal->setCiudadOrigenRel($this->getUser()->getOperacionRel()->getCiudadRel());
                    }


                    // Ciudad destino
                    $cell = $worksheet->getCellByColumnAndRow(1, $row);
                    $arCiudadDestino = $em->find(TteCiudad::class, $cell->getValue());
                    if (!$arCiudadDestino) {
                        $arrErrores[] = $respuesta . "la ciudad de destino '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuiaTemporal->setCiudadDestinoRel($arCiudadDestino);
                    }
                    // Producto
                    $cell = $worksheet->getCellByColumnAndRow(2, $row);
                    $arProducto = $em->find(TteProducto::class, $cell->getValue());
                    if (!$arProducto) {
                        $arrErrores[] = $respuesta . "el producto '{$cell->getValue()}' no existe";
                        $error = true;
                    } else {
                        $arGuiaTemporal->setProductoRel($arProducto);
                    }
                    // Documento cliente
                    $cell = $worksheet->getCellByColumnAndRow(3, $row);
                    $arGuiaTemporal->setClienteDocumento(substr($cell->getValue(), 0, 80));

                    // Relacion cliente
                    $cell = $worksheet->getCellByColumnAndRow(4, $row);
                    $arGuiaTemporal->setClienteRelacion(substr($cell->getValue(), 0, 80));

                    // Remitente
                    $arGuiaTemporal->setRemitente($arCliente->getNombreCorto());

                    // Nombre destinatario
                    $cell = $worksheet->getCellByColumnAndRow(5, $row);
                    $arGuiaTemporal->setDestinatarioNombre(substr($cell->getValue(), 0, 150));

                    // Dirección destinatario
                    $cell = $worksheet->getCellByColumnAndRow(6, $row);
                    $arGuiaTemporal->setDestinatarioDireccion(substr($cell->getValue(), 0, 150));

                    // Teléfono destinatario
                    $cell = $worksheet->getCellByColumnAndRow(7, $row);
                    $arGuiaTemporal->setDestinatarioTelefono(substr($cell->getValue(), 0, 80));

                    // Unidades
                    $cell = $worksheet->getCellByColumnAndRow(8, $row);
                    $unidades = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuiaTemporal->setUnidades($cell->getValue());
                        $unidades = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con las unidades ingresadas";
                        $error = true;
                    }

                    // Peso real
                    $cell = $worksheet->getCellByColumnAndRow(9, $row);
                    $peso = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuiaTemporal->setPesoReal($cell->getValue());
                        $peso = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso real' ingresado";
                        $error = true;
                    }

                    // Peso volumen
                    $cell = $worksheet->getCellByColumnAndRow(10, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuiaTemporal->setPesoVolumen($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'peso volumen' ingresado";
                        $error = true;
                    }
                    // Valor declara
                    $cell = $worksheet->getCellByColumnAndRow(11, $row);
                    $declarado = 0;
                    if (is_numeric($cell->getValue())) {
                        $arGuiaTemporal->setVrDeclara($cell->getValue());
                        $declarado = $cell->getValue();
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'valor declara' ingresado";
                        $error = true;
                    }



                    // Valor recaudo
                    $cell = $worksheet->getCellByColumnAndRow(12, $row);
                    if (is_numeric($cell->getValue())) {
                        $arGuiaTemporal->setVrRecaudo($cell->getValue());
                    } else {
                        $arrErrores[] = $respuesta . "existe un error con el 'valor recaudo' ingresado";
                        $error = true;
                    }

                    //Comentario
                    $cell = $worksheet->getCellByColumnAndRow(13, $row);
                    $arGuiaTemporal->setComentario(substr($cell->getValue(), 0, 2000));

                    $tipoLiquidacion = $em->getRepository(TteCondicion::class)->tipoLiquidacion($arCondicion);
                    //Si el archivo de excel tiene tipo de liquidacion se prima este
                    $cell = $worksheet->getCellByColumnAndRow(14, $row);
                    $tipoLiquidacionExcel = substr($cell->getValue(), 0, 1);
                    if($tipoLiquidacionExcel) {
                        $tipoLiquidacionExcel = strtoupper($tipoLiquidacionExcel);
                        if($tipoLiquidacionExcel === 'K' || $tipoLiquidacionExcel === 'U' || $tipoLiquidacionExcel === 'A') {
                            $tipoLiquidacion = $tipoLiquidacionExcel;
                        }
                    }

                    if ($error == false) {
                        $arrLiquidacion = $em->getRepository(TteGuia::class)->liquidar(
                            $arCliente->getCodigoClientePk(),
                            $arCondicion->getCodigoCondicionPk(),
                            $arCondicion->getCodigoPrecioFk(),
                            $arGuiaTemporal->getCiudadOrigenRel()->getCodigoCiudadPk(),
                            $arCiudadDestino->getCodigoCiudadPk(),
                            $arProducto->getCodigoProductoPk(),
                            $arCiudadDestino->getCodigoZonaFk(),
                            $tipoLiquidacion,
                            $unidades,
                            $peso,
                            $declarado);
                        $arGuiaTemporal->setVrFlete($arrLiquidacion['flete']);
                        $arGuiaTemporal->setVrManejo($arrLiquidacion['manejo']);
                        $arGuiaTemporal->setPesoFacturado($arrLiquidacion['pesoFacturado']);
                        $arGuiaTemporal->setTipoLiquidacion($tipoLiquidacion);
                    }

                    if (!$error) {
                        $em->persist($arGuiaTemporal);
                        if ($arGuiaTemporal->getNumero() == 0) {
                            $arrGuias[] = $arGuiaTemporal;
                        }
                    }
                    $i++;
                }
                if (count($arrErrores) == 0) {
                    try {
                        $em->flush();
                        Mensajes::success('Guias cargadas con éxito');
                    } catch (\Exception $exception) {
                        Mensajes::error('Ha ocurrido un problema al insertar las guias, por favor contacte con soporte y agregue en su reporte el siguiente error: ' . $exception->getMessage());
                    }
                } else {
                    Mensajes::error('Se han generado los siguientes errores: ' . implode(", ", $arrErrores));
                }
            }
        }
    }

    private function generarGuias($arrSeleccionados)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($arrSeleccionados as $codigoGuiaTemporal) {
            $arGuiaTemporal = $em->find(TteGuiaTemporal::class, $codigoGuiaTemporal);
            $arGuiaTipo = $em->getRepository(TteGuiaTipo::class)->find($arGuiaTemporal->getCodigoGuiaTipoFk());
            $arOperacion = $em->find(TteOperacion::class, $arGuiaTemporal->getOperacion());
            $arEmpaque = $em->getRepository(TteEmpaque::class)->find(1);
            $arServicio = $em->find(TteServicio::class, 'PAQ');
            if ($arGuiaTemporal) {
                if ($arOperacion) {
                    $arGuia = new TteGuia();
                    $arConsecutivo = $em->getRepository(TteConsecutivo::class)->find(1);
                    $arGuia->setCodigoGuiaPk($arConsecutivo->getGuia());
                    $arGuia->setNumero($arConsecutivo->getGuia());
                    $arConsecutivo->setGuia($arConsecutivo->getGuia() + 1);
                    $em->persist($arConsecutivo);
                    $arGuia->setCiudadOrigenRel($arGuiaTemporal->getCiudadOrigenRel());
                    $arGuia->setGuiaTipoRel($arGuiaTipo);
                    $arGuia->setCiudadDestinoRel($arGuiaTemporal->getCiudadDestinoRel());
                    $arGuia->setClienteRel($arGuiaTemporal->getClienteRel());
                    $arGuia->setServicioRel($arServicio);
                    $arGuia->setRutaRel($arGuiaTemporal->getCiudadDestinoRel()->getRutaRel());
                    $arGuia->setProductoRel($arGuiaTemporal->getProductoRel());
                    $arGuia->setEmpaqueRel($arEmpaque);
                    $arGuia->setCondicionRel($arGuiaTemporal->getClienteRel()->getCondicionRel());
                    $arGuia->setOperacionCargoRel($arOperacion);
                    $arGuia->setOperacionIngresoRel($arOperacion);
                    $arGuia->setTelefonoDestinatario($arGuiaTemporal->getDestinatarioTelefono());
                    $arGuia->setFechaIngreso($arGuiaTemporal->getFechaIngreso());
                    $arGuia->setDocumentoCliente($arGuiaTemporal->getClienteDocumento());
                    $arGuia->setRelacionCliente($arGuiaTemporal->getClienteRelacion());
                    $arGuia->setRemitente($arGuiaTemporal->getClienteRel()->getNombreCorto());
                    $arGuia->setNombreDestinatario($arGuiaTemporal->getDestinatarioNombre());
                    $arGuia->setDireccionDestinatario($arGuiaTemporal->getDestinatarioDireccion());
                    $arGuia->setTelefonoDestinatario($arGuiaTemporal->getDestinatarioTelefono());
                    $arGuia->setUnidades($arGuiaTemporal->getUnidades());
                    $arGuia->setPesoFacturado($arGuiaTemporal->getPesoFacturado());
                    $arGuia->setPesoReal($arGuiaTemporal->getPesoReal());
                    $arGuia->setPesoVolumen($arGuiaTemporal->getPesoVolumen());
                    $arGuia->setVrDeclara($arGuiaTemporal->getVrDeclara());
                    $arGuia->setVrFlete($arGuiaTemporal->getVrFlete());
                    $arGuia->setVrManejo($arGuiaTemporal->getVrManejo());
                    $arGuia->setComentario($arGuiaTemporal->getComentario());
                    $arGuia->setEstadoAutorizado(1);
                    $arGuia->setEstadoAprobado(1);
                    $arGuia->setEstadoImpreso(1);
                    $arGuia->setUsuario($this->getUser()->getUsername());
                    $arGuia->setZonaRel($arGuiaTemporal->getCiudadDestinoRel()->getZonaRel());
                    $arGuia->setTipoLiquidacion($arGuiaTemporal->getTipoLiquidacion());
                    $arGuia->setImportado('E');
                    $em->persist($arGuia);
                } else {
                    Mensajes::error("El consecutivo '{$arGuiaTemporal->getNumero()}' no tiene una operacion valida");
                    return $this->redirect($this->generateUrl('transporte_utilidad_transporte_importar_guia'));
                }
            }
        }
        $em->flush();
        Mensajes::success('Guias creadas con éxito');
    }
}

