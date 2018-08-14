<?php

namespace App\Controller\Contabilidad\Utilidad\Intercambio;

use App\Entity\Contabilidad\CtbRegistro;
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
     * @Route("/contabilidad/utilidad/intercambio/registro/lista", name="contabilidad_utilidad_intercambio_registro_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('filtrarFecha', CheckboxType::class, array('required' => false, 'data' => $session->get('filtroCtbRegistroFiltroFecha')))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'data' => date_create($session->get('filtroCtbRegistroFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroCtbRegistroFechaHasta'))])
            ->add('txtComprobante', TextType::class, ['required' => false, 'data' => $session->get('filtroCtbComprobante'), 'attr' => ['class' => 'form-control']])
            ->add('btnDescargar', SubmitType::class, ['label' => 'Descargar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnGenerar')->isClicked() || $form->get('btnDescargar')->isClicked()) {
                $session->set('filtroCtbRegistroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroCtbRegistroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroCtbRegistroFiltroFecha', $form->get('filtrarFecha')->getData());
                $session->set('filtroCtbComprobante', $form->get('txtComprobante')->getData());
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $this->ilimitada();
            }
            if ($form->get('btnDescargar')->isClicked()) {
                $em->getRepository(CtbRegistro::class)->aplicarIntercambio();
            }

        }
        $arRegistros = $paginator->paginate($em->getRepository(CtbRegistro::class)->listaIntercambio(), $request->query->getInt('page', 1),20);
        return $this->render('contabilidad/utilidad/intercambio/registro/lista.html.twig',
            ['arRegistros' => $arRegistros,
            'form' => $form->createView()]);
    }

    private function ilimitada()
    {
        $em = $this->getDoctrine()->getManager();
        $rutaTemporal = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $strNombreArchivo = "ExpIlimitada" . date('YmdHis') . ".txt";
        $strArchivo = $rutaTemporal . $strNombreArchivo;
        $ar = fopen($strArchivo, "a") or
        die("Problemas en la creacion del archivo plano");
        $arRegistros = $em->getRepository(CtbRegistro::class)->listaIntercambio()->getQuery()->getResult();
        foreach ($arRegistros as $arRegistro) {
            $valor = 0;
            $naturaleza = "1";
            if ($arRegistro['naturaleza'] == "D") {
                $valor = $arRegistro['vrDebito'];
                $naturaleza = "1";
            } else {
                $valor = $arRegistro['vrCredito'];
                $naturaleza = "1";
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
            fputs($ar, $arRegistro['codigoCuentaFk'] . "\t");
            fputs($ar, FuncionesController::RellenarNr($arRegistro['codigoComprobanteFk'], "0", 5) . "\t");
            fputs($ar, $arRegistro['fecha']->format('m/d/Y') . "\t");
            fputs($ar, FuncionesController::RellenarNr($numero, "0", 9) . "\t");
            fputs($ar, FuncionesController::RellenarNr($numero, "0", 9) . "\t");
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

