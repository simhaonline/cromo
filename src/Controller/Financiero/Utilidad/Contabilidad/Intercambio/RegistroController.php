<?php

namespace App\Controller\Financiero\Utilidad\Contabilidad\Intercambio;

use App\Entity\Financiero\FinConfiguracion;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenConfiguracion;
use App\Utilidades\Mensajes;
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

class RegistroController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro", name="financiero_utilidad_contabilidad_intercambio_registro")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
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
            fputs($ar, FuncionesController::RellenarNr($numero, "0", 9) . "\t");
            fputs($ar, FuncionesController::RellenarNr($numeroReferencia, "0", 9) . "\t");
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
                $sheet->setCellValue('A'.$j, $arrConfiguracion['codigoEmpresaIntercambio']);
                $sheet->setCellValue('B'.$j, $arRegistro['codigoComprobanteFk']);
                $sheet->setCellValue('C'.$j,$arRegistro['numeroPrefijo']);
                $sheet->setCellValue('D'.$j,$arRegistro['numero']);
                $sheet->setCellValue('E'.$j,$arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
                $sheet->setCellValue('F'.$j,$arRegistro['numeroIdentificacion']);
                $sheet->setCellValue('G'.$j, $arRegistro['nombre']);
                $sheet->setCellValue('H'.$j, '0');
                $sheet->setCellValue('I'.$j, '');
                $sheet->setCellValue('J'.$j, '0');
                $sheet->setCellValue('K'.$j, '0');

                $sheet->setCellValue('L'.$j,$arRegistro['codigoCuentaFk']);
                $sheet->setCellValue('M'.$j, $arRegistro['descripcion']);
                $sheet->setCellValue('N'.$j,$arRegistro['numeroIdentificacion']);
                $sheet->setCellValue('O'.$j, '');
                $sheet->setCellValue('P'.$j, '');
                $sheet->setCellValue('Q'.$j,$arRegistro['vrDebito']);
                $sheet->setCellValue('R'.$j,$arRegistro['vrCredito']);
                $sheet->setCellValue('S'.$j, '');
                $sheet->setCellValue('T'.$j, '');
                $sheet->setCellValue('U'.$j,$arRegistro['fecha'] ? $arRegistro['fecha']->format('d-m-Y') : '');
                $sheet->setCellValue('V'.$j, '');

                $j++;
            }
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename='DetallesContables.xls'");
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
            $array = array($arRegistro['codigoCuentaFk'], chr(9), '00003', chr(9), $arRegistro['fecha']->format('mdY'), chr(9), $arRegistro['numero'], chr(9),
                $arRegistro['numero'], chr(9), $arRegistro['numeroIdentificacion'], chr(9), $arRegistro['descripcion'], chr(9), $arRegistro['codigoComprobanteFk'], chr(9),
                $valor, chr(9), '0', chr(9), '404', chr(9), "", chr(9), '0',  "\n");
            foreach ($array as $fields) {
                fputs($ar, $fields);
            }
//            fputs($ar, $arRegistro['codigoCuentaFk'].'00003'.$arRegistro['fecha']->format('m-d-Y'));
//            fputs($ar, FuncionesController::RellenarNr($arRegistro['codigoComprobanteFk'], "0", 5) . "\t");
//            fputs($ar, $arRegistro['fecha']->format('m/d/Y') . "\t");
//            fputs($ar, FuncionesController::RellenarNr($numero, "0", 9) . "\t");
//            fputs($ar, FuncionesController::RellenarNr($numeroReferencia, "0", 9) . "\t");
//            fputs($ar, $srtNit . "\t");
//            fputs($ar, $arRegistro['descripcion'] . "\t");
//            fputs($ar, $naturaleza . "\t");
//            fputs($ar, $valor . "\t");
//            fputs($ar, $arRegistro['vrBase'] . "\t");
//            fputs($ar, $srtCentroCosto . "\t");
//            fputs($ar, "" . "\t");
//            fputs($ar, "" . "\t");
//            fputs($ar, "\n");
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

}

