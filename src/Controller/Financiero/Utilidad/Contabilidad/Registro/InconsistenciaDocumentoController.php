<?php

namespace App\Controller\Financiero\Utilidad\Contabilidad\Registro;

use App\Entity\Financiero\FinConfiguracion;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinRegistroInconsistencia;
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

class InconsistenciaDocumentoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/financiero/utilidad/contabilidad/registro/inconsistenciaDocumento", name="financiero_utilidad_contabilidad_registro_inconsistenciaDocumento")
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
                $arRegistros = $this->getDoctrine()->getRepository(FinRegistro::class)->analizarInconsistencias($fechaDesde, $fechaHasta);
                foreach ($arRegistros as $arRegistro){
                    if($arRegistro['vrDebito'] != $arRegistro['vrCredito']) {
                        $dif = $arRegistro['vrDebito'] - $arRegistro['vrCredito'];
                        $arRegistroInconsistencia = new FinRegistroInconsistencia();
                        $arRegistroInconsistencia->setNumero($arRegistro['numero']);
                        $arRegistroInconsistencia->setNumeroPrefijo($arRegistro['numeroPrefijo']);
                        $arRegistroInconsistencia->setCodigoComprobanteFk($arRegistro['codigoComprobanteFk']);
                        $arRegistroInconsistencia->setDescripcion('Diferencia en debito y credito');
                        $arRegistroInconsistencia->setUtilidad('inconsistencia');
                        $arRegistroInconsistencia->setDiferencia($dif);
                        $em->persist($arRegistroInconsistencia);
                    }
                }
                $em->flush();
                $arRegistrosInconsistencias = $paginator->paginate($em->getRepository(FinRegistroInconsistencia::class)->lista('inconsistencia'), $request->query->getInt('page', 1), 1000);
            }

        }
        return $this->render('financiero/utilidad/contabilidad/registro/inconsistenciaDocumento.html.twig', [
            'arRegistrosInconsistencias' => $arRegistrosInconsistencias,
            'form' => $form->createView()]);
    }
}

