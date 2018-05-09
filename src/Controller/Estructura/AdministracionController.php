<?php

namespace App\Controller\Estructura;

use App\Entity\General\GenConfiguracionEntidad;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdministracionController extends Controller
{
    const TP_ERROR = "error";
    const TP_OK = "ok";
    const TP_INFO = "info";

    /**
     * Construye los parámetros requeridos para generar un mensaje
     * @param string $strTipo El tipo de mensaje a generar  se debe enviar en minuscula <br> error, informacion
     * @param string $strMensaje El mensaje que se mostrara
     */
    public function Mensaje($strTipo, $strMensaje)
    {
        $session = new Session();
        $session->getFlashBag()->add($strTipo, $strMensaje);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param $respuesta string
     * @param $em EntityManager
     */
    public function validarRespuesta($respuesta, $em)
    {
        if ($respuesta != '') {
            $this->Mensaje('error', $respuesta);
        } else {
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $this->Mensaje('error', 'No se puede eliminar, el registro esta siendo utilizado en el sistema');
            }
        }
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param $arrDatos
     * @param $nombre
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generarExcel($arrDatos, $nombre)
    {
        if (count($arrDatos) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $j = 0;
            //Se obtienen las columnas del archivo
            $arrColumnas = array_keys($arrDatos[0]);
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $sheet->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
                $j++;
            }
            $j = 1;
            foreach ($arrDatos as $datos) {
                $i = 'A';
                $j++;
                for ($col = 0; $col <= sizeof($arrColumnas) - 1; $col++) {
                    $dato = $datos[$arrColumnas[$col]];
                    if ($dato instanceof \DateTime) {
                        $dato = $dato->format('Y-m-d');
                    }
                    $spreadsheet->getActiveSheet()->getStyle($i)->getFont()->setBold(false);
                    $sheet->setCellValue($i . $j, $dato);
                    $i++;
                }
            }
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename='{$nombre}.xls'");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        } else {
            $this->Mensaje('error', 'El listado esta vacío, no hay nada que exportar');
        }
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("listado/{entidad}",name="listado")
     */
    public function generarLista(Request $request, $entidad)
    {
        /**
         * @var $arConfiguracionEntidad GenConfiguracionEntidad
         */
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $router = $this->container->get('router');
        $arConfiguracionEntidad = $em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad);
        //Se crea el formulario estandar
        $form = $this->formularioLista();
        $form->handleRequest($request);

//        $this->configuracionEntidad($arConfiguracionEntidad);
        $arRegistros = $em->getRepository('App:General\GenConfiguracionEntidad')->lista($arConfiguracionEntidad,0);


        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistrosExcel = $em->getRepository('App:General\GenConfiguracionEntidad')->lista($arConfiguracionEntidad,1);
                $this->generarExcel($arRegistrosExcel, 'Excel');
            }
        }

        $ruta = $arConfiguracionEntidad->getRutaGeneral();
        $rutaNuevo = $ruta . '_nuevo';
        $rutaDetalle = $ruta . '_detalle';

        //Se valida si existe la ruta nuevo
        $rutaNuevo = ($router->getRouteCollection()->get($rutaNuevo)) ? $rutaNuevo : null;

        //Se valida si existe la ruta detalles
        $rutaDetalle = ($router->getRouteCollection()->get($rutaDetalle)) ? $rutaDetalle : null;

        $arRegistros = $paginator->paginate($arRegistros, $request->query->getInt('page', 1), 10);
        return $this->render('estructura/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'rutaNuevo' => $rutaNuevo,
            'rutaDetalle' => $rutaDetalle,
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
            'extiende' => 0,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function formularioLista()
    {
        return $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
    }
}
