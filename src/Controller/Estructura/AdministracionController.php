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
     * @param $strTipo
     * @param $strMensaje
     */
    public function Mensaje($strTipo, $strMensaje)
    {
        $session = new Session();
        $session->getFlashBag()->add($strTipo, $strMensaje);
    }

    /**
     * @param $respuesta mixed
     * @param $em EntityManager
     */
    public function validarRespuesta($respuesta, $em)
    {
        if (is_array($respuesta)) {
            if (count($respuesta) > 0) {
                foreach ($respuesta as $respuesta) {
                    $this->Mensaje(AdministracionController::TP_ERROR, $respuesta);
                }
            }
        } elseif ($respuesta != '') {
            $this->Mensaje(AdministracionController::TP_ERROR, $respuesta);
        } else {
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $this->Mensaje(AdministracionController::TP_ERROR, 'No se puede eliminar, el registro esta siendo utilizado en el sistema');
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
        $arRegistros = $em->getRepository('App:General\GenConfiguracionEntidad')->lista($arConfiguracionEntidad, 0);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistrosExcel = $em->getRepository('App:General\GenConfiguracionEntidad')->lista($arConfiguracionEntidad, 1);
                $this->generarExcel($arRegistrosExcel, 'Excel');
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:General\GenConfiguracionEntidad')->eliminar($arConfiguracionEntidad, $arrSeleccionados);
                $this->validarRespuesta($respuesta, $em);
            }
        }

        $arRegistros = $paginator->paginate($arRegistros, $request->query->getInt('page', 1), 10);
        return $this->render('estructura/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("detalle/{entidad}/{id}",name="detalle")
     */
    public function generarDetalles(Request $request, $entidad, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionEntidad = $em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad);
        $arRegistros = $em->getRepository('App:General\GenConfiguracionEntidad')->listaDetalles($arConfiguracionEntidad, $id);
        $arrCampos = json_decode($em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad)->getJsonLista());
        return $this->render('estructura/detalles.html.twig', [
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
            'arRegistros' => $arRegistros,
            'arrCampos' => $arrCampos
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("configuracion/{entidad}", name="configuracion_entidad")
     */
    public function configuracionEntidad(Request $request, $entidad)
    {
        /**
         * @var $arConfiguracionEntidad GenConfiguracionEntidad
         */
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionEntidad = $em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad);
        $arrColumnasLista = json_decode($arConfiguracionEntidad->getJsonLista());
        $arrColumnasExcel = json_decode($arConfiguracionEntidad->getJsonExcel());
        $arrColumnasFiltro = json_decode($arConfiguracionEntidad->getJsonFiltro());
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrAlias = $request->request->get('aliasLista');
                $arrMostrar = $request->request->get('mostrarLista');
                foreach ($arrColumnasLista as $columna) {
                    if ($arrAlias[$columna->campo]) {
                        if ($arrAlias[$columna->campo] != '') {
                            $columna->alias = $arrAlias[$columna->campo];
                        } else {
                            $columna->alias = $columna->campo;
                        }
                    }
                    if (isset($arrMostrar[$columna->campo])) {
                        if ($arrMostrar[$columna->campo] == 'on') {
                            $columna->mostrar = true;
                        } else {
                            $columna->mostrar = false;
                        }
                    } else {
                        $columna->mostrar = false;
                    }
                }
                $arConfiguracionEntidad->setJsonLista(json_encode($arrColumnasLista));
                $em->persist($arConfiguracionEntidad);
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('estructura/configuracionEntidad.html.twig', [
            'form' => $form->createView(),
            'arrColumnasLista' => $arrColumnasLista,
            'arrColumnasExcel,' => $arrColumnasExcel,
            'arrColumnasFiltro' => $arrColumnasFiltro
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("nuevo/{entidad}/{id}", name="nuevo")
     */
    public function generarNuevo(Request $request, $entidad, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionEntidad = $em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad);
        $rutaEntidad = $arConfiguracionEntidad->getRutaEntidad();
        $arRegistro = new  $rutaEntidad();
        $getPk = 'getCodigo'.substr($arConfiguracionEntidad->getCodigoConfiguracionEntidadPk(),3).'Pk';
        if ($id != 0) {
            $arRegistro = $em->getRepository($arConfiguracionEntidad->getRutaRepositorio())->find($id);
            if(!$arRegistro){
                return $this->redirect($this->generateUrl('listado',['entidad' => $arConfiguracionEntidad->getCodigoConfiguracionEntidadPk()]));
            }
        } else {
            if (property_exists($arRegistro, 'fecha')) {
                $arRegistro->setFecha(new \DateTime('now'));
            }
        }
        $form = $this->createForm($arConfiguracionEntidad->getRutaFormulario(), $arRegistro);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('guardar')->isClicked()){
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('detalle',['entidad' => $arConfiguracionEntidad->getCodigoConfiguracionEntidadPk(),'id' => $arRegistro->$getPk()]));
            }
            if($form->get('guardarnuevo')->isClicked()){
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('detalle',['entidad' => $arConfiguracionEntidad->getCodigoConfiguracionEntidadPk(),'id' => 0]));
            }
        }
        return $this->render('estructura/nuevo.html.twig', [
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
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
