<?php

namespace App\Controller\Estructura;

use App\Entity\General\GenConfiguracionEntidad;
use App\Entity\General\GenCubo;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * @param $arrRespuestas
     * @param $em EntityManager
     */
    public function validarRespuesta($arrRespuestas, $em)
    {
        if (count($arrRespuestas) > 0) {
            foreach ($arrRespuestas as $strRespuesta) {
                $this->Mensaje(AdministracionController::TP_ERROR, $strRespuesta);
            }
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
     * @author Juan Felipe Mesa Ocampo
     * @param $arrDatos
     * @param $nombre
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generarCsv($arrDatos, $nombre)
    {
        $delimiter = ";";
        $f = fopen('php://memory', 'w');//crea objeto tipo csv escribible
        $arrColumnas = array_keys($arrDatos[0]);
        fputcsv($f, $arrColumnas, $delimiter);//insrta cabeceras
        foreach ($arrDatos as $datos) {
            for ($col = 0; $col <= sizeof($arrColumnas) - 1; $col++) {
                $dato = $datos[$arrColumnas[$col]];
                if ($dato instanceof \DateTime) {
                    $datos['fecha'] = $dato->format('Y-m-d');
                }
            }
            fputcsv($f, $datos, $delimiter);//inserta fila en el archivo
        }

        fseek($f, 0);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $nombre . '";');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        fpassthru($f);
        fclose($f);
        exit();
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @param $entidadCubo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("listado/{entidad}/{entidadCubo}",name="listado")
     */
    public function generarLista(Request $request, $entidad, $entidadCubo = "")
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arConfiguracionEntidad = $em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad);
        //Se crea el formulario estandar
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $arRegistros = $em->getRepository('App:General\GenConfiguracionEntidad')->lista($arConfiguracionEntidad, 0, $entidadCubo);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistrosExcel = $em->getRepository('App:General\GenConfiguracionEntidad')->lista($arConfiguracionEntidad, 1);
                $this->generarExcel($arRegistrosExcel, 'Excel');
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $respuesta = $em->getRepository('App:General\GenConfiguracionEntidad')->eliminar($arConfiguracionEntidad, $arrSeleccionados);
                $this->validarRespuesta($respuesta, $em);
                return $this->redirectToRoute("listado", ['entidad' => $entidad, 'entidadCubo' => $entidadCubo]);
            }
        }

        $arRegistros = $paginator->paginate($arRegistros, $request->query->getInt('page', 1), 10);
        return $this->render('estructura/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'entidadCubo' => $entidadCubo,
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @param $entidadCubo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("detalle/{entidad}/{id}/{entidadCubo}",name="detalle")
     */
    public function generarDetalles(Request $request, $entidad, $id, $entidadCubo = "")
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionEntidad = $em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad);
        $arRegistros = $em->getRepository('App:General\GenConfiguracionEntidad')->listaDetalles($arConfiguracionEntidad, $id);
        $arrCampos = json_decode($em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad)->getJsonLista());
        return $this->render('estructura/detalles.html.twig', [
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
            'arRegistros' => $arRegistros,
            'arrCampos' => $arrCampos,
            'entidadCubo' => $entidadCubo
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
            echo "<script type='text/javascript'>window.close();window.opener.location.reload();</script>";
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
     * @param $entidadCubo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("nuevo/{entidad}/{id}/{entidadCubo}", name="nuevo")
     */
    public function generarNuevo(Request $request, $entidad, $id, $entidadCubo = "")
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionEntidad = $em->getRepository('App:General\GenConfiguracionEntidad')->find($entidad);
        $rutaEntidad = $arConfiguracionEntidad->getRutaEntidad();
        $arRegistro = new $rutaEntidad();
        $getPk = 'getCodigo' . substr($arConfiguracionEntidad->getCodigoConfiguracionEntidadPk(), 3) . 'Pk';
        //Validaciones adicionales.
        $arRegistro = $this->validacionAdicional($arRegistro, $arConfiguracionEntidad, $id, $entidadCubo);
        $form = $entidadCubo == "" ? $this->createForm($arConfiguracionEntidad->getRutaFormulario(), $arRegistro) : $this->formularioCubo($entidadCubo, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($entidadCubo) {
                    //Validar funciones adicionales para guardar un registro según la entidad.
                    $arRegistro = $em->getRepository($arConfiguracionEntidad->getRutaEntidad())->guardar($form, $arRegistro, $entidadCubo);
                }
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('detalle', ['entidad' => $arConfiguracionEntidad->getCodigoConfiguracionEntidadPk(), 'id' => $arRegistro->$getPk(), 'entidadCubo' => $entidadCubo]));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                if ($entidadCubo) {
                    $arRegistro = $em->getRepository($arConfiguracionEntidad->getRutaEntidad())->guardar($form, $arRegistro, $entidadCubo);
                }
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('detalle', ['entidad' => $arConfiguracionEntidad->getCodigoConfiguracionEntidadPk(), 'id' => 0, 'entidadCubo' => $entidadCubo]));
            }
        }
        return $this->render('estructura/nuevo.html.twig', [
            'entidadCubo' => $entidadCubo,
            'arConfiguracionEntidad' => $arConfiguracionEntidad,
            'form' => $form->createView()
        ]);
    }


    /**
     * @author Juan Felipe Mesa Ocampo
     * @param $entidadCubo
     * @param $arRegistro GenCubo
     * @return \Symfony\Component\Form\FormInterface
     */
    public function formularioCubo($entidadCubo, $arRegistro)
    {
        $arrPropiedades = $this->generarPropiedadesFormCubo($arRegistro, $entidadCubo);
        return $this->createFormBuilder()
            ->add('nombre', TextType::class, [
                'label' => 'Nombre:',
                'data' => $arRegistro->getNombre()])
            ->add("columnas", ChoiceType::class, [
                'label' => 'Columnas:',
                'multiple' => true,
                'placeholder' => '',
                'choices' => $arrPropiedades['arrCamposSelect'],
                'data' => $arrPropiedades['arrCamposSelected']])
            ->add("condicion", ChoiceType::class, [
                'label' => 'Condición:',
                'placeholder' => '',
                'choices' => $arrPropiedades['arrCamposSelect'],
                'data' => $arrPropiedades['condicionSelected']])
            ->add('operadorCondicion', ChoiceType::class, [
                'label' => ' ',
                'placeholder' => '',
                'choices' => $arrPropiedades['arrOperadores'],
                'data' => $arrPropiedades['operadorCondicionSelected']])
            ->add('valorCondicion', TextType::class, [
                'label' => ' ',
                'data' => $arrPropiedades['valorCondicionSelected']])
            ->add("ordenar", ChoiceType::class, [
                'label' => 'Ordenar por:',
                'multiple' => true,
                'placeholder' => '',
                'choices' => $arrPropiedades['arrCamposSelect'],
                'data' => $arrPropiedades['arrOrdenSelected']])
            ->add("ordenTipo", ChoiceType::class, [
                'label' => 'Tipo de orden:',
                'placeholder' => '',
                'choices' => array('ASC' => 'ASC', 'DESC' => 'DESC'),
                'data' => $arrPropiedades['ordenTipo']])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
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

    /**
     * @param $arRegistro
     * @param $arConfiguracionEntidad GenConfiguracionEntidad
     * @param $id
     * @param $entidadCubo
     * @return mixed
     */
    public function validacionAdicional($arRegistro, $arConfiguracionEntidad, $id, $entidadCubo)
    {
        $em = $this->getDoctrine()->getManager();
        if (property_exists($arRegistro, 'fecha')) {
            $arRegistro->setFecha(new \DateTime('now'));
        }
        if (property_exists($arRegistro, 'codigoUsuarioFk')) {
            $arRegistro->setCodigoUsuarioFk($this->getUser()->getUsername());
        }
        if (property_exists($arRegistro, 'codigoEntidadFk')) {
            $arRegistro->setCodigoEntidadFk($entidadCubo);
        }
        if ($id != 0) {//Validar si se va a editar un registro
            $arRegistro = $em->getRepository($arConfiguracionEntidad->getRutaRepositorio())->find($id);
            if (!$arRegistro) {//Validación si realmente el registro existe.
                return $this->redirect($this->generateUrl('listado', ['entidad' => $arConfiguracionEntidad->getCodigoConfiguracionEntidadPk(), 'entidadCubo' => $entidadCubo]));
            }
        }
        return $arRegistro;
    }

    /**
     * @param $arRegistro GenCubo
     * @param $entidadCubo
     * @return mixed
     */
    public function generarPropiedadesFormCubo($arRegistro, $entidadCubo)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionEntidad = $em->getRepository("App:General\GenConfiguracionEntidad")->find($entidadCubo);
        $arrCampos = json_decode($arRegistro->getJsonCubo());
        $arrCamposEntidad = json_decode($arConfiguracionEntidad->getJsonLista());
        $arrCamposSelect = [];
        $arrCamposSelected = [];
        $arrOrdenSelected = [];
        $arrOperadores = ['Igual' => '=', 'Mayor igual' => '>=', 'Menor igual' => '<=', 'Diferente' => '<>'];
        $condicionSelected = $arrCampos != null ? $arrCampos->condicion : null;
        $operadorCondicionSelected = $arrCampos != null ? $arrCampos->operadorCondicion : null;
        $valorCondicionSelected = $arrCampos != null ? $arrCampos->valorCondicion : null;
        $ordenTipo = $arrCampos != null ? $arrCampos->tipoOrden : null;
        if ($arrCampos) {
            foreach ($arrCampos->columnas as $campo) {
                $arrCamposSelected[$campo] = $campo;
            }
            foreach ($arrCampos->orden as $orden) {
                $arrOrdenSelected[$orden] = $orden;
            }
        }
        foreach ($arrCamposEntidad as $lista) {
            if (strpos($lista->campo, 'Fk')) {
                $strCampo = preg_replace("/(codigo|Fk)/", '', $lista->campo);
                $arrCamposSelect[$strCampo . "Rel"] = [$lista->campo => $lista->campo, "Nombre" . $strCampo => $strCampo . "Rel"];
            } else {
                $arrCamposSelect[$lista->campo] = $lista->campo;
            }
        }

        return ['arrCamposSelect' => $arrCamposSelect,
            'arrCamposSelected' => $arrCamposSelected,
            'arrOrdenSelected' => $arrOrdenSelected,
            'condicionSelected' => $condicionSelected,
            'arrOperadores' => $arrOperadores,
            'operadorCondicionSelected' => $operadorCondicionSelected,
            'valorCondicionSelected' => $valorCondicionSelected,
            'ordenTipo' => $ordenTipo];
    }

}
