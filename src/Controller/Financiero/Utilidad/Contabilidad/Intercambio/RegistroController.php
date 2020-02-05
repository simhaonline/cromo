<?php

namespace App\Controller\Financiero\Utilidad\Contabilidad\Intercambio;

use App\Controller\MaestroController;
use App\Entity\Financiero\FinConfiguracion;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenConfiguracion;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Controller\Estructura\FuncionesController;

class RegistroController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "finu0001";

    /**
     * @param Request $request
     * @return Response
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro", name="financiero_utilidad_contabilidad_intercambio_registro")
     */
    public function lista(Request $request,PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('txtComprobante', TextType::class, ['required' => false, 'data' => $session->get('filtroFinComprobante'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroDesde', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroDesde'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroHasta', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroHasta'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroPrefijo', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroPrefijo'), 'attr' => ['class' => 'form-control']])
            ->add('txtCuenta', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCuenta'), 'attr' => ['class' => 'form-control']])
            ->add('txtCentroCosto', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCentroCosto'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroReferenciaPrefijo', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroReferenciaPrefijo'), 'attr' => ['class' => 'form-control']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFinRegistroFiltroFecha')))
            ->add('chkTodos', CheckboxType::class, array('label' => 'Todos', 'required' => false, 'data' => $session->get('filtroFinRegistrosTodos')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaHasta'))])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnDescargar', SubmitType::class, ['label' => 'Descargar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnDescargar')->isClicked()) {
                $session->set('filtroFinRegistrosTodos', $form->get('chkTodos')->getData());
                $session->set('filtroFinCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroFinComprobante', $form->get('txtComprobante')->getData());
                $session->set('filtroFinNumeroDesde', $form->get('txtNumeroDesde')->getData());
                $session->set('filtroFinNumeroHasta', $form->get('txtNumeroHasta')->getData());
                $session->set('filtroFinNumeroPrefijo', $form->get('txtNumeroPrefijo')->getData());
                $session->set('filtroFinCuenta', $form->get('txtCuenta')->getData());
                $session->set('filtroFinCentroCosto', $form->get('txtCentroCosto')->getData());
                $session->set('filtroFinNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
                $session->set('filtroFinNumeroReferenciaPrefijo', $form->get('txtNumeroReferenciaPrefijo')->getData());
                $session->set('filtroFinRegistroFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFiltroFecha', $form->get('filtrarFecha')->getData());
            }
            if ($form->get('btnDescargar')->isClicked()) {
                $em->getRepository(FinRegistro::class)->aplicarIntercambio();
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio()->getQuery()->getResult();
                $this->exportarExcelPersonalizado($arRegistros);
            }

        }
        $arRegistros = $paginator->paginate($em->getRepository(FinRegistro::class)->listaIntercambio(), $request->query->getInt('page', 1), 20);
        return $this->render('financiero/utilidad/contabilidad/intercambio/registro/registro.html.twig',
            ['arRegistros' => $arRegistros,
                'form' => $form->createView()]);
    }

    /**
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro/ilimitada", name="financiero_utilidad_contabilidad_intercambio_registro_ilimitada")
     */
    public function ilimitada()
    {
        $em = $this->getDoctrine()->getManager();
        $rutaTemporal = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $strNombreArchivo = "ExpIlimitada" . date('YmdHis') . ".txt";
        $strArchivo = $rutaTemporal . $strNombreArchivo;
        $ar = fopen($strArchivo, "a") or
        die("Problemas en la creacion del archivo plano");
        $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio()->getQuery()->getResult();
        foreach ($arRegistros as $arRegistro) {
            if ($arRegistro['naturaleza'] == "D") {
                $valor = $arRegistro['vrDebito'];
                $naturaleza = "1";
            } else {
                $valor = $arRegistro['vrCredito'];
                $naturaleza = "2";
            }
            $srtCentroCosto = "";
            if ($arRegistro['codigoCentroCostoFk']) {
                $srtCentroCosto = $arRegistro['codigoCentroCostoFk'];
            }
            $srtNit = "";
            if ($arRegistro['codigoTerceroFk']) {
                $srtNit = $arRegistro['numeroIdentificacion'];
            }
            $numero = $arRegistro['numeroPrefijo'] . $arRegistro['numero'];
            $numeroReferencia = $arRegistro['numeroReferenciaPrefijo'] . $arRegistro['numeroReferencia'];
            fputs($ar, $arRegistro['codigoCuentaFk'] . "\t");
            fputs($ar, FuncionesController::RellenarNr($arRegistro['codigoComprobanteFk'], "0", 5) . "\t");
            fputs($ar, $arRegistro['fecha']->format('m/d/Y') . "\t");
            fputs($ar, FuncionesController::RellenarNr($numero, "0", 9, "I") . "\t");
            fputs($ar, FuncionesController::RellenarNr($numeroReferencia, "0", 9, "I") . "\t");
            fputs($ar, $srtNit . "\t");
            fputs($ar, $arRegistro['descripcion'] . "\t");
            fputs($ar, $naturaleza . "\t");
            fputs($ar, $valor . "\t");
            fputs($ar, $arRegistro['vrBase'] . "\t");
            fputs($ar, $srtCentroCosto . "\t");
            fputs($ar, "" . "\t");
            fputs($ar, "" . "\t");
            fputs($ar, "\n");
        }
        fclose($ar);
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;

    }

    /**
     * @author Andres Acevedo Cartagena
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro/worldOffice", name="financiero_utilidad_contabilidad_intercambio_registro_worldOffice")
     */
    public function worldOffice()
    {
        $em = $this->getDoctrine()->getManager();
        $arrConfiguracion = $em->getRepository(FinConfiguracion::class)->intercambioDatos();
        $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio()->getQuery()->getResult();
        if (count($arRegistros) > 0) {
            $spreadsheet = new Spreadsheet();
            $campos = [
                'EMPRESA',
                'DOC',
                'PREFIJO',
                'Nro Doc',
                'FECHA',
                'Benefic.',
                'Concepto',
                'Bloq/act',
                'Forma de pago',
                'Verificado',
                'Anulado',
                'Cuenta Contable',
                'Nota',
                'Tercero Externo',
                'Cheque Numero',
                'BancoCheque',
                'Debito',
                'Credito',
                'CentroCosto',
                'PorcentajeRetencion',
                'Vencimiento',
                'Vendedor',
            ];
            $sheet = $spreadsheet->getActiveSheet();
            $i = 0;
            for ($j = 'A'; $j != 'W'; $j++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
                $sheet->setCellValue($j . '1', strtoupper($campos[$i]));
                $i++;
            }
            $j = 2;
            /** @var  $arRegistro FinRegistro */
            foreach ($arRegistros as $arRegistro) {
                $sheet->setCellValue('A' . $j, $arrConfiguracion['codigoEmpresaIntercambio']);
                $sheet->setCellValue('B' . $j, $arRegistro['codigoComprobanteFk']);
                $sheet->setCellValue('C' . $j, $arRegistro['numeroPrefijo']);
                $sheet->setCellValue('D' . $j, $arRegistro['numero']);
                $sheet->setCellValue('E' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
                $sheet->setCellValue('F' . $j, $arRegistro['numeroIdentificacion']);
                $sheet->setCellValue('G' . $j, $arRegistro['nombre']);
                $sheet->setCellValue('H' . $j, '0');
                $sheet->setCellValue('I' . $j, '');
                $sheet->setCellValue('J' . $j, '0');
                $sheet->setCellValue('K' . $j, '0');

                $sheet->setCellValue('L' . $j, $arRegistro['codigoCuentaFk']);
                $sheet->setCellValue('M' . $j, $arRegistro['descripcion']);
                $sheet->setCellValue('N' . $j, $arRegistro['numeroIdentificacion']);
                $sheet->setCellValue('O' . $j, '');
                $sheet->setCellValue('P' . $j, '');
                $sheet->setCellValue('Q' . $j, $arRegistro['vrDebito']);
                $sheet->setCellValue('R' . $j, $arRegistro['vrCredito']);
                $sheet->setCellValue('S' . $j, '');
                $sheet->setCellValue('T' . $j, '');
                $sheet->setCellValue('U' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
                $sheet->setCellValue('V' . $j, '');

                $j++;
            }
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=DetallesContables.xls");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        } else {
            Mensajes::error('El listado esta vacío, no hay nada que exportar');
        }
    }

    /**
     * @author Andres Acevedo Cartagena
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro/worldOffice2", name="financiero_utilidad_contabilidad_intercambio_registro_worldOffice2")
     */
    public function worldOffice2()
    {
        $em = $this->getDoctrine()->getManager();
        $genConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
        $arrConfiguracion = $em->getRepository(FinConfiguracion::class)->intercambioDatos();
        $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio()->getQuery()->getResult();
        if (count($arRegistros) > 0) {
            $spreadsheet = new Spreadsheet();
            $campos = [
                'EMPRESA',
                'TIPO_DOCUMENTO',
                'PREFIJO',
                'NRO_DOCUMENTO',
                'FECHA',
                'TERCERO_INTERNO',
                'TERCERO_EXTERNO',
                'CONCEPTO',
                'FORMA_PAGO',
                'FEHCA_ENTREGA',
                'PREFIJO_EXTERNO',
                'NUMERO_DOCUMENTO_EXTERNO',
                'VERIFICADO',
                'ANULADO',
                'NOTA_AL_PIE_DE_FACTURA',
                'SUCURSAL',
                'DETALLE_PRODUCTO',
                'BODEGA',
                'UNIDAD_MEDIDA',
                'CANTIDAD',
//                'Bloq/act',
//                'Forma de pago',
//                'Verificado',
//                'Anulado',
//                'Cuenta Contable',
//                'Nota',
//                'Tercero Externo',
//                'Cheque Numero',
//                'BancoCheque',
//                'Debito',
//                'Credito',
//                'CentroCosto',
//                'PorcentajeRetencion',
//                'Vencimiento',
//                'Vendedor',
            ];
            $sheet = $spreadsheet->getActiveSheet();
            $i = 0;
            for ($j = 'A'; $j != 'U'; $j++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
                $sheet->setCellValue($j . '1', strtoupper($campos[$i]));
                $i++;
            }
            $j = 2;
            /** @var  $arRegistro FinRegistro */
            foreach ($arRegistros as $arRegistro) {
                $sheet->setCellValue('A' . $j, $genConfiguracion->getNombre());
                $sheet->setCellValue('B' . $j, $arRegistro['codigoComprobanteFk']);
                $sheet->setCellValue('C' . $j, $arRegistro['numeroPrefijo']);
                $sheet->setCellValue('D' . $j, $arRegistro['numero']);
                $sheet->setCellValue('E' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
                $sheet->setCellValue('F' . $j, $arRegistro['numeroIdentificacion']);
                $sheet->setCellValue('G' . $j, $arRegistro['numeroIdentificacion']);
                $sheet->setCellValue('H' . $j, $arRegistro['nombre']);
                $sheet->setCellValue('I' . $j, '0');
                $sheet->setCellValue('J' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
                $sheet->setCellValue('K' . $j, '0');
                $sheet->setCellValue('L' . $j, '0');
                $sheet->setCellValue('M' . $j, '0');
                $sheet->setCellValue('N' . $j, '0');
                $sheet->setCellValue('O' . $j, '0');
                $sheet->setCellValue('P' . $j, '0');
                $sheet->setCellValue('Q' . $j, '0');
                $sheet->setCellValue('R' . $j, '0');
                $sheet->setCellValue('S' . $j, 'Und.');
                $sheet->setCellValue('T' . $j, '0');
//                $sheet->setCellValue('I' . $j, '');
//                $sheet->setCellValue('J' . $j, '0');
//                $sheet->setCellValue('K' . $j, '0');
//
//                $sheet->setCellValue('L' . $j, $arRegistro['codigoCuentaFk']);
//                $sheet->setCellValue('M' . $j, $arRegistro['descripcion']);
//                $sheet->setCellValue('N' . $j, $arRegistro['numeroIdentificacion']);
//                $sheet->setCellValue('O' . $j, '');
//                $sheet->setCellValue('P' . $j, '');
//                $sheet->setCellValue('Q' . $j, $arRegistro['vrDebito']);
//                $sheet->setCellValue('R' . $j, $arRegistro['vrCredito']);
//                $sheet->setCellValue('S' . $j, '');
//                $sheet->setCellValue('T' . $j, '');
//                $sheet->setCellValue('U' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
//                $sheet->setCellValue('V' . $j, '');

                $j++;
            }
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=DetallesContables.xls");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        } else {
            Mensajes::error('El listado esta vacío, no hay nada que exportar');
        }
    }

    /**
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro/siigo", name="financiero_utilidad_contabilidad_intercambio_registro_siigo")
     */
    public function siigo()
    {
        $em = $this->getDoctrine()->getManager();
        $rutaTemporal = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $strNombreArchivo = "ExpSigo" . date('YmdHis') . ".txt";
        $strArchivo = $rutaTemporal . $strNombreArchivo;
        $ar = fopen($strArchivo, "a") or
        die("Problemas en la creacion del archivo plano");
        $consecutivo = 0;
        $numeroAnterior = 0;
        $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio()->getQuery()->getResult();
        foreach ($arRegistros as $arRegistro) {
            if ($numeroAnterior != $arRegistro['numero']) {
                $consecutivo = 0;
            }
            $numeroAnterior = $arRegistro['numero'];
            $consecutivo++;

            $vendedor = '0001';
            $nit = "";
            if ($arRegistro['codigoTerceroFk']) {
                $nit = $arRegistro['numeroIdentificacion'];
            }
            if ($arRegistro['naturaleza'] == 'D') {
                $valor = $arRegistro['vrDebito'];
            } else {
                $valor = $arRegistro['vrCredito'];
            }
            $valor = number_format($valor, 2, '', '');


            if ($arRegistro['numeroReferencia']) {
                $fechaVence = '00000000';
                if ($arRegistro['fechaVence']) {
                    $fechaVence = $arRegistro['fechaVence']->format('Ymd');
                }
                $documentoCruce = $this->RellenarDato($arRegistro['codigoComprobanteReferenciaFk'], '0', 4, 'I') . $this->RellenarDato($arRegistro['numeroReferencia'], '0', 11, 'I') . $this->RellenarDato($consecutivo, '0', 3, 'I') . $fechaVence . '0001' . '00';
            } else {
                $documentoCruce = ' ' . '000' . '00000000000' . '000' . '00000000' . '0000' . '00';
            }
            /*
          If Mid(strCuenta, 1, 4) = "1305" Then
            strDocumentoCruce = "F" & strComprobante & strNumero & Rellenar(J & "", 3, "0", 1) & Format(rstFacturasExp!FhVence, "yyyymmdd") & "0001" & "00"
          Else
            strDocumentoCruce = " " & "000" & "00000000000" & "000" & "00000000" & "0000" & "00"
          End If

Print #1, "F" & strComprobante & strNumero & Rellenar(J & "", 5, "0", 1) & Rellenar(strNit, 13, "0", 1) & "000" & strCuenta & "000000000000000" & Format(rstFacturasExp!Fecha, "yyyymmdd") & strCentroCostos & "000" & Rellenar(strDetalle, 50, " ", 0) & strTipo & Rellenar(strValor, 15, "0", 1) & "000000000000000" & strVendedor & "0001" & "001" & "0001" & "000" & "000000000000000" & strDocumentoCruce

            */

            fputs($ar, $arRegistro['codigoComprobanteFk']);
            fputs($ar, $this->RellenarDato($arRegistro['numero'], '0', 11, 'I'));
            fputs($ar, $this->RellenarDato($consecutivo, '0', 5, 'I'));
            fputs($ar, $this->RellenarDato($nit, '0', 13, 'I'));
            fputs($ar, '000');
            fputs($ar, $this->RellenarDato($arRegistro['codigoCuentaFk'], '0', 8, 'I'));
            fputs($ar, '000000000000000');
            fputs($ar, $arRegistro['fecha']->format('Ymd'));
            fputs($ar, $this->RellenarDato($arRegistro['codigoCentroCostoFk'], '0', 4, 'I'));
            fputs($ar, '000');
            fputs($ar, $this->RellenarDato($arRegistro['descripcion'], ' ', 50, 'D'));
            fputs($ar, $arRegistro['naturaleza']);
            fputs($ar, $this->RellenarDato($valor, '0', 15, 'I'));
            fputs($ar, '000000000000000');
            fputs($ar, $vendedor);
            fputs($ar, '0001');
            fputs($ar, '001');
            fputs($ar, '0001');
            fputs($ar, '000');
            fputs($ar, '000000000000000');
            fputs($ar, $documentoCruce);
            fputs($ar, "\n");
        }
        fclose($ar);
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=ISO-8859-15');
        header('Content-Disposition: attachment; filename=' . basename($strArchivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($strArchivo));
        readfile($strArchivo);
        exit;

    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro/zeus", name="financiero_utilidad_contabilidad_intercambio_registro_zeus")
     */
    public function zeus()
    {
        $em = $this->getDoctrine()->getManager();
//        $arrConfiguracion = $em->getRepository(FinConfiguracion::class)->intercambioDatos();
        $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio()->getQuery()->getResult();
        if (count($arRegistros) > 0) {
            $spreadsheet = new Spreadsheet();
            $campos = [
                'CUENTA',
                'FECHA',
                'DESCRIPCION',
                'VALOR DEBITO',
                'VALOR CREDITO',
                'NIT',
                'CCOSTO',
                'FACTURA',
                'NOMBRE COMPLETO',
                'DIRECCION',
            ];
            $sheet = $spreadsheet->getActiveSheet();
            $i = 0;
            for ($j = 'A'; $j != 'K'; $j++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($j)->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
                $sheet->setCellValue($j . '1', strtoupper($campos[$i]));
                $i++;
            }
            $j = 2;
            /** @var  $arRegistro FinRegistro */
            foreach ($arRegistros as $arRegistro) {
                $sheet->setCellValue('A' . $j, $arRegistro['codigoCuenta']);
                $sheet->setCellValue('B' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('Y/m/d') : '');
                $sheet->setCellValue('C' . $j, $arRegistro['descripcion']);
                $sheet->setCellValue('D' . $j, $arRegistro['vrDebito']);
                $sheet->setCellValue('E' . $j, $arRegistro['vrCredito']);
                $sheet->setCellValue('F' . $j, $arRegistro['numeroIdentificacion']);
                $sheet->setCellValue('G' . $j, $arRegistro['codigoCentroCostoFk']);
                $sheet->setCellValue('H' . $j, $arRegistro['numero']);
                $sheet->setCellValue('I' . $j, trim($arRegistro['nombreCorto']));
                $sheet->setCellValue('J' . $j, $arRegistro['direccion']);
//                $sheet->setCellValue('B' . $j, $arRegistro['codigoComprobanteFk']);
//                $sheet->setCellValue('C' . $j, $arRegistro['numeroPrefijo']);
//                $sheet->setCellValue('D' . $j, $arRegistro['numero']);
//                $sheet->setCellValue('E' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
//                $sheet->setCellValue('F' . $j, $arRegistro['numeroIdentificacion']);
//                $sheet->setCellValue('G' . $j, $arRegistro['nombre']);
//                $sheet->setCellValue('H' . $j, '0');
//                $sheet->setCellValue('I' . $j, '');
//                $sheet->setCellValue('J' . $j, '0');
//                $sheet->setCellValue('K' . $j, '0');
//
//                $sheet->setCellValue('L' . $j, $arRegistro['codigoCuentaFk']);
//                $sheet->setCellValue('M' . $j, $arRegistro['descripcion']);
//                $sheet->setCellValue('N' . $j, $arRegistro['numeroIdentificacion']);
//                $sheet->setCellValue('O' . $j, '');
//                $sheet->setCellValue('P' . $j, '');
//                $sheet->setCellValue('Q' . $j, $arRegistro['vrDebito']);
//                $sheet->setCellValue('R' . $j, $arRegistro['vrCredito']);
//                $sheet->setCellValue('S' . $j, '');
//                $sheet->setCellValue('T' . $j, '');
//                $sheet->setCellValue('U' . $j, $arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
//                $sheet->setCellValue('V' . $j, '');

                $j++;
            }
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=DetallesContables.xls");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        } else {
            Mensajes::error('El listado esta vacío, no hay nada que exportar');
        }
    }

    public function RellenarDato($dato, $caracterRelleno, $cantidadCaracteres, $ladoDeRelleno = 'I')
    {
        $dato = utf8_decode($dato);
        $Longitud = strlen($dato);
        $cantidadCaracteres = $cantidadCaracteres - $Longitud;
        for ($i = 0; $i < $cantidadCaracteres; $i++) {
            if ($ladoDeRelleno == "I") {
                $dato = $caracterRelleno . $dato;
            } else {
                $dato = $dato . $caracterRelleno;
            }
        }

        return (string)$dato;
    }

    public function exportarExcelPersonalizado($arRegistros){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arRegistros) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Movimientos');
            $j = 0;
            $arrColumnas=[ 
                    'COD',
                    'P',
                    'NUMERO',
                    'P',
                    'NUMERO REF',
                    'FECHA',
                    'COMPRABANTE',
                    'CUENTA',
                    'C_C',
                    'NIT',
                    'TERCERO',
                    'DEBITO',
                    'CREDITO',
                    'BASE',
                    'DETALLE',
            ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arRegistros as $arRegistro) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);

                $hoja->setCellValue('A' . $j, $arRegistro['codigoRegistroPk']);
                $hoja->setCellValue('C' . $j, $arRegistro['numeroPrefijo']);
                $hoja->setCellValue('B' . $j, $arRegistro['numero']);
                $hoja->setCellValue('E' . $j, $arRegistro['numeroReferenciaPrefijo']);
                $hoja->setCellValue('D' . $j, $arRegistro['numeroReferencia']);
                $hoja->setCellValue('F' . $j, $arRegistro['fecha']->format('Y/m/d'));
                $hoja->setCellValue('G' . $j, "{$arRegistro['codigoComprobanteFk']} - {$arRegistro['nombre']}");
                $hoja->setCellValue('H' . $j, $arRegistro['codigoCuentaFk']);
                $hoja->setCellValue('I' . $j, $arRegistro['codigoCentroCostoFk']);
                $hoja->setCellValue('J' . $j, $arRegistro['numeroIdentificacion']);
                $hoja->setCellValue('K' . $j, $arRegistro['nombreCorto']);
                $hoja->setCellValue('L' . $j, $arRegistro['vrDebito']);
                $hoja->setCellValue('M' . $j, $arRegistro['vrCredito']);
                $hoja->setCellValue('N' . $j, $arRegistro['vrBase']);
                $hoja->setCellValue('O' . $j, $arRegistro['descripcion']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=intercambioRegistro.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}

