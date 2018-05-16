<?php

namespace App\Controller\General;

use App\Controller\Estructura\AdministracionController;
use App\Entity\General\GenConfiguracionEntidad;
use App\Entity\General\GenCubo;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CuboController extends Controller
{
    /**
     * @param Request $request
     * @param $entidadCubo
     * @param $id
     * @param $opcion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/listado/cubo/{entidadCubo}/{id}/{opcion}", name="lista_cubo_entidad")
     */
    public function listaCuboAction(Request $request, $entidadCubo, $id, $opcion)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $FuncionAdministracion = new AdministracionController();
        $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find($entidadCubo);
        $strSql = $em->getRepository('App:General\GenCubo')->find($id)->getSqlCubo();
        $arrRegistros = $em->createQuery($strSql)->execute();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcel')->isClicked()) {
                $FuncionAdministracion->generarExcel($arrRegistros, 'Excel');
            }
            if ($form->get('btnCsv')->isClicked()) {
                $FuncionAdministracion->generarCsv($arrRegistros, 'Csv');
            }
        }
        $arRegistros = $paginator->paginate($arrRegistros, $request->query->getInt('page', 1), 50);
        return $this->render("general/listaCubo.html.twig", [
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
            'arRegistros' => $arRegistros,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function formularioLista()
    {
        return $this->createFormBuilder()
            ->add('btnCsv', SubmitType::class, ['label' => 'Csv', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnPdf', SubmitType::class, ['label' => 'Pdf', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGrafico', SubmitType::class, ['label' => 'Grafico', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
    }
}

