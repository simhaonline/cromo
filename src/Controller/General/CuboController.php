<?php

namespace App\Controller\General;

use App\Controller\Estructura\AdministracionController;
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
    public function inicio(Request $request, $entidadCubo, $id, $opcion)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find($entidadCubo);
        $strSql = $em->getRepository('App:General\GenCubo')->find($id)->getSqlCubo();
        if ($strSql != null) {
            $arrRegistros = $em->createQuery($strSql)->execute();
            if ($opcion === 'lista' || $opcion === 'chart') {
                $arRegistros = $paginator->paginate($arrRegistros, $request->query->getInt('page', 1), 50);
                return $this->render("general/{$opcion}Cubo.html.twig", [
                    'arConfiguracionEntidad' => $arConfiguracionEntidad,
                    'arRegistros' => $arRegistros
                ]);
            } elseif ($opcion === 'excel' || $opcion === 'csv') {
                $FuncionAdministracion = new AdministracionController();
                if ($opcion == "excel") {
                    $FuncionAdministracion->generarExcel($arrRegistros, 'Excel');
                } else {
                    $FuncionAdministracion->generarCsv($arrRegistros, 'Csv');
                }
                return $this->redirectToRoute('listado', ['entidad' => 'GenCubo', 'entidadCubo' => $entidadCubo]);
            }
        } else {
            if ($opcion === 'lista' || $opcion === 'chart') {
                echo "<script type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } elseif ($opcion === 'excel' || $opcion === 'csv') {
                return $this->redirectToRoute('listado', ['entidad' => 'GenCubo', 'entidadCubo' => $entidadCubo]);
            }
        }
    }
}

