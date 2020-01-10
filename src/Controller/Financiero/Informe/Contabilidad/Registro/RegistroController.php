<?php

namespace App\Controller\Financiero\Informe\Contabilidad\Registro;

use App\Controller\Estructura\MensajesController;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Formato\Transporte\ControlFactura;
use App\Formato\Transporte\FacturaInforme;
use App\General\General;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistroController extends Controller
{
    /**
     * @Route("/financiero/informe/contabilidad/registro", name="financiero_informe_contabilidad_registro_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
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
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaHasta'))])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroFinCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroFinComprobante', $form->get('txtComprobante')->getData());
                $session->set('filtroFinNumeroDesde', $form->get('txtNumeroDesde')->getData());
                $session->set('filtroFinNumeroHasta', $form->get('txtNumeroHasta')->getData());
                $session->set('filtroFinNumeroPrefijo', $form->get('txtNumeroPrefijo')->getData());
                $session->set('filtroFinCuenta', $form->get('txtCuenta')->getData());
                $session->set('filtroFinCentroCosto', $form->get('txtCentroCosto')->getData());
                $session->set('filtroFinNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
                $session->set('filtroFinNumeroReferenciaPrefijo', $form->get('txtNumeroReferenciaPrefijo')->getData());
                $session->set('filtroFinRegistroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFiltroFecha', $form->get('filtrarFecha')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio()->getQuery()->getResult();
                $this->exportarExcelPersonalizado($arRegistros);
            }
        }
        $query = $this->getDoctrine()->getRepository(FinRegistro::class)->registros();
        $arRegistros = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('financiero/informe/registro/registros.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView() ]);
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

