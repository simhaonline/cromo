<?php

namespace App\Controller\Financiero\Utilidad\General;

use App\Entity\Documental\DocArchivo;
use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivo;
use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenModelo;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VerMovimientoController extends Controller
{
    /**
     * @Route("/financiero/utilidad/general/vermovimiento/{clase}/{id}", name="financiero_utilidad_general_vermovimiento")
     */
    public function listaAction(Request $request, $clase, $id) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $arModelo = $em->getRepository(GenModelo::class)->find($clase);
        $repositorio = "App:{$arModelo->getCodigoModuloFk()}\\{$clase}";
        $arMovimiento = $em->getRepository($repositorio)->find($id);
        $arrBotonContabilizar = array('label' => 'Contabilizar', 'disabled' => true);
        $arrBotonDescontabilizar = array('label' => 'Descontabilizar', 'disabled' => true);
        if ($arMovimiento->getEstadoContabilizado()) {
            $arrBotonDescontabilizar['disabled'] = false;
            $arRegistroIntercambio = $em->getRepository(FinRegistro::class)->findOneBy(array('codigoModeloFk' => $clase, 'codigoDocumento' => $id, 'estadoIntercambio' => 1));
            if($arRegistroIntercambio) {
                Mensajes::info('No se puede descontabilizar porque ya fue utilizado para intercambio de datos');
                $arrBotonDescontabilizar['disabled'] = true;
            }
        } else {
            $arrBotonContabilizar['disabled'] = false;

        }
        $form = $this->createFormBuilder()
            ->add('btnContabilizar', SubmitType::class, $arrBotonContabilizar)
            ->add('btnDescontabilizar', SubmitType::class, $arrBotonDescontabilizar)
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnPdf', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnContabilizar')->isClicked()) {
                $resultado = $em->getRepository($repositorio)->contabilizar(array($id));
                return $this->redirect($this->generateUrl('financiero_utilidad_general_vermovimiento', array('clase' => $clase, 'id' => $id)));
            }
            if ($form->get('btnDescontabilizar')->isClicked()) {

                $em->createQueryBuilder()
                    ->delete(FinRegistro::class,'r')
                    ->where("r.codigoModeloFk = '{$clase}' AND r.codigoDocumento = {$id}")->getQuery()->execute();

                //$arRegistroEliminar = $em->getRepository(FinRegistro::class)->findBy(array('codigoModeloFk' => $clase, 'codigoDocumento' => $id));
                //$em->remove($arRegistroEliminar);
                $arMovimiento->setEstadoContabilizado(0);
                $em->persist($arMovimiento);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_utilidad_general_vermovimiento', array('clase' => $clase, 'id' => $id)));
            }
            if ($form->get('btnExcel')->isClicked()){
                $arMovimientos = $this->getDoctrine()->getRepository(FinRegistro::class)->listaVerMovimiento($clase, $id)->getQuery()->getResult();
                $this->exportarExcelPersonalizado($arMovimientos);
            }
        }
        $query = $this->getDoctrine()->getRepository(FinRegistro::class)->listaVerMovimiento($clase, $id);
        $arRegistros = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('financiero/utilidad/general/verMovimiento.html.twig', array(
            'arRegistros' => $arRegistros,
            'form' => $form->createView()
        ));
    }

    public function exportarExcelPersonalizado($arMovimientos){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arMovimientos) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('Movimientos');
            $j = 0;
            $arrColumnas=[ 'ID','P','NUMERO','P','NUM_REF','FECHA','VENCE','COMPRABANTE','CUENTA','C_C','NIT','TERCERO','DEBITO','CREDITO','BASE','DETALLE',
            ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setBold(true);;
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arMovimientos as $arMovimiento) {
                $hoja->setCellValue('A' . $j, $arMovimiento['id']);
                $hoja->setCellValue('C' . $j, $arMovimiento['numeroPrefijo']);
                $hoja->setCellValue('B' . $j, $arMovimiento['numero']);
                $hoja->setCellValue('E' . $j, $arMovimiento['numeroReferenciaPrefijo']);
                $hoja->setCellValue('D' . $j, $arMovimiento['numeroReferencia']);
                $hoja->setCellValue('F' . $j, $arMovimiento['fecha']->format('Y/m/d'));
                $hoja->setCellValue('G' . $j, $arMovimiento['fechaVence']);
                $hoja->setCellValue('H' . $j, "{$arMovimiento['idComprobante']} - {$arMovimiento['comprobante']}");
                $hoja->setCellValue('I' . $j, $arMovimiento['cuenta']);
                $hoja->setCellValue('J' . $j, $arMovimiento['c_c']);
                $hoja->setCellValue('K' . $j, $arMovimiento['nit']);
                $hoja->setCellValue('L' . $j, $arMovimiento['tercero']);
                $hoja->setCellValue('M' . $j, number_format($arMovimiento['vrDebito'], 0,'.', ','));
                $hoja->setCellValue('N' . $j, number_format($arMovimiento['vrCredito'], 0,'.', ','));
                $hoja->setCellValue('O' . $j, number_format($arMovimiento['vrBase'], 0,'.', ','));
                $hoja->setCellValue('P' . $j, $arMovimiento['descripcion']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=soportes.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }

}

