<?php

namespace App\Controller\Financiero\Utilidad\Contabilidad\Intercambio;

use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenConfiguracion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Controller\Estructura\FuncionesController;

class TerceroController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/financiero/utilidad/contabilidad/intercambio/tercero", name="financiero_utilidad_contabilidad_intercambio_tercero")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnIlimitada', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnIlimitada')->isClicked()) {
                $this->ilimitada();
            }

        }
        $arTerceros = $paginator->paginate($em->getRepository(FinTercero::class)->listaIntercambio(), $request->query->getInt('page', 1), 20);
        return $this->render('financiero/utilidad/contabilidad/intercambio/tercero/tercero.html.twig', [
            'arTerceros' => $arTerceros,
            'form' => $form->createView()
        ]);
    }

    private function ilimitada()
    {
        $em = $this->getDoctrine()->getManager();
        $rutaTemporal = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');
        $strNombreArchivo = "ExpIlimitada" . date('YmdHis') . ".txt";
        $strArchivo = $rutaTemporal . $strNombreArchivo;
        $ar = fopen($strArchivo, "a") or
        die("Problemas en la creacion del archivo plano");
        $arTerceros = $em->getRepository(FinTercero::class)->listaIntercambio()->getQuery()->getResult();
        foreach ($arTerceros as $arTercero) {
            fputs($ar, $arTercero['numeroIdentificacion'] . "\t");
            fputs($ar, utf8_decode($arTercero['nombre1']) . "\t");
            fputs($ar, utf8_decode($arTercero['nombre2']) . "\t");
            fputs($ar, utf8_decode($arTercero['apellido1']) . "\t");
            fputs($ar, utf8_decode($arTercero['apellido2']) . "\t");
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

