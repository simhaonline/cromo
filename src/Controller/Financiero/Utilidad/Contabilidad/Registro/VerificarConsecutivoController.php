<?php

namespace App\Controller\Financiero\Utilidad\Contabilidad\Registro;

use App\Entity\Financiero\FinConfiguracion;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinRegistroInconsistencia;
use App\Entity\General\GenConfiguracion;
use App\General\General;
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

class VerificarConsecutivoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/financiero/utilidad/contabilidad/registro/verificarConsecutivo", name="financiero_utilidad_contabilidad_registro_verificarconsecutivo")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $fecha = new \DateTime('now');
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => $fecha])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => $fecha])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $arRegistrosInconsistencias = [];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGenerar')->isClicked()) {
                $em->getRepository(FinRegistroInconsistencia::class)->limpiar('inconsistencia');
                $fechaDesde = $form->get('fechaDesde')->getData()->format('Y-m-d');
                $fechaHasta = $form->get('fechaHasta')->getData()->format('Y-m-d');
                $arComprobantes = $em->getRepository(FinRegistro::class)->documentoPeriodo($fechaDesde, $fechaHasta);
                foreach ($arComprobantes as $arComprobante) {
                    $desde = $arComprobante['minimo'];
                    $hasta = $arComprobante['maximo'];
                    $numero = $desde;
                    $arDocumentos = $em->getRepository(FinRegistro::class)->documentoPeriodoEncabezado($arComprobante['codigoComprobanteFk'], $arComprobante['numeroPrefijo'], $desde, $hasta);
                    foreach ($arDocumentos as $arDocumento) {
                        if($arDocumento['numero'] == $numero) {
                            $numero++;
                        } else {
                            $arRegistroInconsistencia = new FinRegistroInconsistencia();
                            $arRegistroInconsistencia->setNumero($arDocumento['numero']);
                            $arRegistroInconsistencia->setNumeroPrefijo($arComprobante['numeroPrefijo']);
                            $arRegistroInconsistencia->setCodigoComprobanteFk($arComprobante['codigoComprobanteFk']);
                            $arRegistroInconsistencia->setDescripcion('Falta el documento');
                            $arRegistroInconsistencia->setUtilidad('verificarConsecutivo');
                            $em->persist($arRegistroInconsistencia);
                        }
                    }
                }
                $em->flush();
                $arRegistrosInconsistencias = $paginator->paginate($em->getRepository(FinRegistroInconsistencia::class)->lista('verificarConsecutivo'), $request->query->getInt('page', 1), 1000);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(FinRegistroInconsistencia::class)->lista('verificarConsecutivo')->getQuery()->execute(), "Inconsistencias");
            }

        }
        return $this->render('financiero/utilidad/contabilidad/registro/verificarConsecutivo.html.twig', [
            'arRegistrosInconsistencias' => $arRegistrosInconsistencias,
            'form' => $form->createView()]);
    }
}

