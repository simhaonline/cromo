<?php

namespace App\Controller\Financiero\Utilidad\Contabilidad\Intercambio;

use App\Entity\Financiero\FinRegistro;
use App\Entity\General\GenConfiguracion;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Controller\Estructura\FuncionesController;
class RegistroController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/financiero/utilidad/contabilidad/intercambio/registro", name="financiero_utilidad_contabilidad_intercambio_registro")
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
            ->add('txtCuenta', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCuenta'), 'attr' => ['class' => 'form-control']])
            ->add('txtCentroCosto', TextType::class, ['required' => false, 'data' => $session->get('filtroFinCentroCosto'), 'attr' => ['class' => 'form-control']])
            ->add('txtNumeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroFinNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroFinRegistroFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroFinRegistroFechaHasta'))])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnDescargar', SubmitType::class, ['label' => 'Descargar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGenerarPendientes', SubmitType::class, ['label' => 'Generar pendientes', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGenerarTodos', SubmitType::class, ['label' => 'Generar todos', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnGenerarPendientes')->isClicked() || $form->get('btnGenerarTodos')->isClicked() || $form->get('btnDescargar')->isClicked()) {
                $session->set('filtroFinCodigoTercero', $form->get('txtCodigoTercero')->getData());
                $session->set('filtroFinComprobante', $form->get('txtComprobante')->getData());
                $session->set('filtroFinNumeroDesde', $form->get('txtNumeroDesde')->getData());
                $session->set('filtroFinNumeroHasta', $form->get('txtNumeroHasta')->getData());
                $session->set('filtroFinCuenta', $form->get('txtCuenta')->getData());
                $session->set('filtroFinCentroCosto', $form->get('txtCentroCosto')->getData());
                $session->set('filtroFinNumeroReferencia', $form->get('txtNumeroReferencia')->getData());
                $session->set('filtroFinRegistroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFinRegistroFiltroFecha', $form->get('filtrarFecha')->getData());
            }
            if ($form->get('btnGenerarPendientes')->isClicked()) {
                $this->ilimitada(true);
            }
            if ($form->get('btnGenerarTodos')->isClicked()) {
                $this->ilimitada(false);
            }
            if ($form->get('btnDescargar')->isClicked()) {
                $em->getRepository(FinRegistro::class)->aplicarIntercambio();
            }

        }
        $arRegistros = $paginator->paginate($em->getRepository(FinRegistro::class)->listaIntercambio(true), $request->query->getInt('page', 1),20);
        return $this->render('financiero/utilidad/contabilidad/intercambio/registro/registro.html.twig',
            ['arRegistros' => $arRegistros,
            'form' => $form->createView()]);
    }

    private function ilimitada($pendientes =  true)
    {
        $em = $this->getDoctrine()->getManager();
        $rutaTemporal = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $strNombreArchivo = "ExpIlimitada" . date('YmdHis') . ".txt";
        $strArchivo = $rutaTemporal . $strNombreArchivo;
        $ar = fopen($strArchivo, "a") or
        die("Problemas en la creacion del archivo plano");
        $arRegistros = $em->getRepository(FinRegistro::class)->listaIntercambio($pendientes)->getQuery()->getResult();
        foreach ($arRegistros as $arRegistro) {
            $valor = 0;
            $naturaleza = "1";
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
            $numero = $arRegistro['numeroPrefijo'].$arRegistro['numero'];
            $numeroReferencia = $arRegistro['numeroReferenciaPrefijo'].$arRegistro['numeroReferencia'];
            fputs($ar, $arRegistro['codigoCuentaFk'] . "\t");
            fputs($ar, FuncionesController::RellenarNr($arRegistro['codigoComprobanteFk'], "0", 5) . "\t");
            fputs($ar, $arRegistro['fecha']->format('m/d/Y') . "\t");
            fputs($ar, FuncionesController::RellenarNr($numero, "0", 9) . "\t");
            fputs($ar, FuncionesController::RellenarNr($numeroReferencia, "0", 9) . "\t");
            fputs($ar, $srtNit . "\t");
            fputs($ar, $arRegistro['descripcion']. "\t");
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

}

