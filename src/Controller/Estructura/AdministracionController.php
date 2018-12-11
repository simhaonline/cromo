<?php

namespace App\Controller\Estructura;

use App\Controller\BaseController;
use App\Entity\General\GenEntidad;
use App\Entity\General\GenCubo;
use App\Utilidades\Mensajes;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Routing\Annotation\Route;

final class AdministracionController extends BaseController
{
    protected $clase;
    protected $claseFormulario;
    protected $claseNombre;
    protected $modulo;
    protected $funcion;
    protected $grupo;
    protected $nombre;

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @param $modulo
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("administracion/{modulo}/{entidad}/lista",name="administracion_lista")
     */
    public function lista(Request $request, $modulo, $entidad)
    {
        $em = $this->getDoctrine()->getManager();
        $this->request = $request;
        $this->nombre=ucfirst($entidad);//añadido alex
        $this->modulo = ucfirst($modulo);
        $prefijo = $this->obtenerPrefijo($modulo);
        $clase = "\\App\\Entity\\" . ucfirst($modulo) . "\\" . ucfirst($prefijo) . ucfirst($entidad);
        $this->claseNombre = ucfirst($prefijo) . ucfirst($entidad);
        $form = $this->botoneraLista();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                foreach ($arrSeleccionados as $codigoRegistro) {
                    $arRegistro = $em->find($clase, $codigoRegistro);
                    if ($arRegistro) {
                        $em->remove($arRegistro);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('El registro esta siendo utilizado en el sistema');
                }
            }
            if ($form->get('btnExcel')->isClicked()) {
                $this->getDatosExportar($form->getClickedButton()->getName(), $this->nombre);
            }
        }

        return $this->render('estructura/administracion/lista.html.twig', [
            'modulo' => $modulo,
            'nombreEntidad' => $entidad,
            'arrDatosLista' => $this->getDatosLista(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad string
     * @param $modulo string
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("administracion/{modulo}/{entidad}/{id}/nuevo",name="administracion_nuevo")
     */
    public function nuevo(Request $request, $modulo, $entidad, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $prefijo = $this->obtenerPrefijo($modulo);
        $clase = "\\App\\Entity\\" . ucfirst($modulo) . "\\" . ucfirst($prefijo) . ucfirst($entidad);
        $arRegistro = new $clase();
        if ($id != '0') {
            $arRegistro = $em->find($clase, $id);
            if (!$arRegistro) {
                return $this->redirect($this->generateUrl('administracion_lista', ['modulo' => $modulo, 'entidad' => $entidad]));
            }
        }
        $getPk = 'getCodigo' . ucfirst($entidad) . 'Pk';
        $form = $this->createForm("\\App\Form\\Type\\" . ucfirst($modulo) . "\\" . ucfirst($entidad) . "Type", $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($arRegistro);
            $em->flush();
            return $this->redirect($this->generateUrl('administracion_detalle',['modulo' => $modulo,'entidad' => $entidad,'id' => $arRegistro->$getPk()]));
        }
        return $this->render('estructura/administracion/nuevo.html.twig', [
            'modulo' => $modulo,
            'nombreEntidad' => $entidad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param $modulo
     * @param $entidad
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("administracion/{modulo}/{entidad}/{id}/detalle",name="administracion_detalle")
     */
    public function detalle($modulo, $entidad, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $prefijo = $this->obtenerPrefijo($modulo);
        $clase = "\\App\\Entity\\" . ucfirst($modulo) . "\\" . ucfirst($prefijo) . ucfirst($entidad);
        $arRegistro = $em->find($clase, $id);
        return $this->render('estructura/administracion/detalle.html.twig', [
            'modulo' => $modulo,
            'nombreEntidad' => $entidad,
            'arRegistro' => $arRegistro
        ]);
    }


    /**
     * @param $modulo
     * @return string
     */
    private function obtenerPrefijo($modulo)
    {
        switch ($modulo) {
            case 'inventario':
                $prefijo = 'inv';
                break;
            case 'recursoHumano':
                $prefijo = 'rhu';
                break;
            case 'cartera':
                $prefijo = 'car';
                break;
            case 'compra':
                $prefijo = 'com';
                break;
            case 'financiero':
                $prefijo = 'fin';
                break;
            case 'transporte':
                $prefijo = 'tte';
                break;
            case 'turno':
                $prefijo = 'tur';
                break;
            case 'general':
                $prefijo = 'gen';
                break;
        }
        return $prefijo;
    }


    /**
     * @param $arrRespuestas
     * @param $em ObjectManager
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
    public function generarExcelAdmin($arrDatos, $nombre)
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
            MensajesController::error('El listado esta vacío, no hay nada que exportar');
        }
    }

    /**
     * @author Juan Felipe Mesa Ocampo
     * @param $arrDatos
     * @param $nombre
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

//    /**
//     * @author Andres Acevedo Cartagena
//     * @param Request $request
//     * @param $modulo
//     * @param $entidad
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @throws \Doctrine\ORM\ORMException
//     * @throws \Doctrine\ORM\OptimisticLockException
//     * @throws \PhpOffice\PhpSpreadsheet\Exception
//     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
//     * @Route("admin/{modulo}/{entidad}/lista",name="admin_lista")
//     */
//    public function generarAdminLista(Request $request, $modulo, $entidad)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $paginator = $this->get('knp_paginator');
//        $codigo = $modulo . "_" . $entidad;
//        $arEntidad = $em->getRepository('App:General\GenEntidad')->find($codigo);
//        $jsonFiltro = $arEntidad->getJsonFiltro();
//        $arrCamposFiltro = json_decode($jsonFiltro);
//        $arrNombreCamposFiltro = [];
//        foreach ($arrCamposFiltro as $arrCampo) {
//            if ($arrCampo->mostrar) {
//                $arrNombreCamposFiltro[] = $arrCampo->campo;
//            }
//        }
//        $arrFiltrar = $this->formularioFiltro($jsonFiltro);
//        if ($arrFiltrar['filtrar']) {
//            $formFiltro = $arrFiltrar['form'];
//            $formFiltro->handleRequest($request);
//        } else {
//            $formFiltro = '';
//        }
//        $form = $this->formularioLista();
//        $form->handleRequest($request);
//        if ($arEntidad->getPersonalizado()) {
//            $arRegistros = $em->getRepository('App:General\GenEntidad')->lista($arEntidad, 0);
//        } else {
//            $arRegistros = $em->getRepository('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()))->camposPredeterminados();
//        }
//        if ($request->getMethod() == 'POST') {
//            if ($request->request->has('form')) {
//                if ($form->isSubmitted() && $form->isValid()) {
//                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                    if ($form->get('btnExcel')->isClicked()) {
//                        $arRegistrosExcel = $em->getRepository('App:General\GenEntidad')->lista($arEntidad, 1);
//                        General::get()->setExportar($arRegistrosExcel, "Excel");
//                    }
//                    if ($form->get('btnEliminar')->isClicked()) {
//                        $em->getRepository('App:General\GenEntidad')->eliminar($arEntidad, $arrSeleccionados);
//                        return $this->redirect($this->generateUrl('admin_lista', ['modulo' => $arEntidad->getModulo(), 'entidad' => $arEntidad->getEntidad()]));
//                    }
//                }
//            }
//            if ($request->request->has('formFiltro')) {
//                if ($formFiltro instanceof Form) {
//                    if ($formFiltro->get('btnFiltrar')->isClicked()) {
//                        $arrFiltros = $formFiltro->getData();
//                        $arRegistros = $em->getRepository('App:General\GenEntidad')->listaFiltro($arEntidad, $arrFiltros);
//                    }
//                }
//            }
//        }
//        $arRegistros = $paginator->paginate($arRegistros, $request->query->getInt('page', 1), 30);
//        return $this->render('estructura/lista.html.twig', [
//            'arRegistros' => $arRegistros,
//            'entidadCubo' => "",
//            'arEntidad' => $arEntidad,
//            'form' => $form->createView(),
//            'formFiltro' => $formFiltro instanceof Form ? $formFiltro->createView() : '',
//            'filtrar' => $arrFiltrar['filtrar']
//        ]);
//    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("admin/{modulo}/{entidad}/detalles/{id}",name="admin_detalle")
     */
    public function generarAdminDetalles(Request $request, $modulo, $entidad, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $codigo = $modulo . "_" . $entidad;
        $arEntidad = $em->getRepository('App:General\GenEntidad')->find($codigo);
        $arRegistro = $em->getRepository('App:General\GenEntidad')->listaDetalles($arEntidad, $id);
        $arrCampos = json_decode($em->getRepository('App:General\GenEntidad')->find($codigo)->getJsonLista());
        return $this->render('estructura/detalles.html.twig', [
            'arEntidad' => $arEntidad,
            'arRegistro' => $arRegistro,
            'arrCampos' => $arrCampos,
            'clase' => array('clase' => $arEntidad->getPrefijo() . "_" . $entidad, 'codigo' => $id),
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param string $modulo
     * @param string $grupo
     * @param string $entidad
     * @param string $funcion
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("admin/navigator/{modulo}/{funcion}/{grupo}/{entidad}",name="admin_navigator")
     */
    public function generarAdminNavigator(Request $request, $modulo = '', $grupo = '', $entidad = '', $funcion = '')
    {
        $em = $this->getDoctrine()->getManager();
        $arRutas = $em->getRepository('App:General\GenEntidad')->generarNavigator($modulo, $funcion, $grupo, $entidad);
        $codigo = $modulo . "_" . $entidad;
        $arEntidad = $em->getRepository('App:General\GenEntidad')->find($codigo);
        $arEntidad = $arEntidad != null ?: '';
        return $this->render('estructura/navigator.html.twig', [
            'arEntidad' => $arEntidad,
            'arRutas' => $arRutas
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
         * @var $arConfiguracionEntidad GenEntidad
         */
        $em = $this->getDoctrine()->getManager();
        $arEntidad = $em->getRepository('App:General\GenEntidad')->find($entidad);
        $arrColumnasLista = json_decode($arEntidad->getJsonLista());
        $arrColumnasExcel = json_decode($arEntidad->getJsonExcel());
        $arrColumnasFiltro = json_decode($arEntidad->getJsonFiltro());
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arEntidad->setPersonalizado($request->request->get('personalizada') != null ? true : false);
                $arrAliasLista = $request->request->get('aliasLista');
                $arrMostrarLista = $request->request->get('mostrarLista');
                $this->generarJson($arEntidad, $arrColumnasLista, $em, $arrAliasLista, $arrMostrarLista, 'lista');

                $arrAliasFiltros = $request->request->get('aliasFiltro');
                $arrMostrarFiltros = $request->request->get('mostrarFiltro');
                $this->generarJson($arEntidad, $arrColumnasLista, $em, $arrAliasFiltros, $arrMostrarFiltros, 'filtro');

                $arrAliasExcel = $request->request->get('aliasExcel');
                $arrMostrarExcel = $request->request->get('mostrarExcel');
                $this->generarJson($arEntidad, $arrColumnasLista, $em, $arrAliasExcel, $arrMostrarExcel, 'excel');


            }
            $em->flush();
            echo "<script type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('estructura/configuracionEntidad.html.twig', [
            'form' => $form->createView(),
            'arrColumnasLista' => $arrColumnasLista,
            'arEntidad' => $arEntidad,
            'arrColumnasExcel' => $arrColumnasExcel,
            'arrColumnasFiltro' => $arrColumnasFiltro
        ]);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param $arConfiguracionEntidad GenEntidad
     * @param $arrColumnas
     * @param $em
     * @param $arrAlias
     * @param $arrMostrar
     */
    public function generarJson($arConfiguracionEntidad, $arrColumnas, $em, $arrAlias, $arrMostrar, $json)
    {
        foreach ($arrColumnas as $columna) {
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
        switch ($json) {
            case 'lista':
                $arConfiguracionEntidad->setJsonLista(json_encode($arrColumnas));
                $dqlLista = $em->getRepository('App:General\GenEntidad')->generarDql($arConfiguracionEntidad, 0);
                $arConfiguracionEntidad->setDqlLista($dqlLista);
                break;
            case 'excel':
                $arConfiguracionEntidad->setJsonExcel(json_encode($arrColumnas));
                break;
            case 'filtro':
                $arConfiguracionEntidad->setJsonFiltro(json_encode($arrColumnas));
                break;
        }
        $em->persist($arConfiguracionEntidad);
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @param $modulo
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("admin/{modulo}/{entidad}/nuevo/{id}", name="admin_nuevo")
     */
    public function generarAdminNuevo(Request $request, $entidad, $modulo, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $codigo = $modulo . '_' . $entidad;
        $arEntidad = $em->getRepository('App:General\GenEntidad')->find($codigo);
        $rutaEntidad = "\App\Entity\\" . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad());
        $arRegistro = new $rutaEntidad();
        $getPk = 'getCodigo' . ucfirst($arEntidad->getEntidad()) . 'Pk';
        if ($id != '0') {
            $arRegistro = $em->getRepository('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()))->find($id);
        }
        $form = $this->createForm("\App\Form\Type\\" . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getEntidad() . 'Type'), $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $this->validacionAdicional($arRegistro);
                $em->persist($arRegistro);
                $em->flush();
                if ($arEntidad->getDetalleInterno()) {
                    return $this->redirect($this->generateUrl($arEntidad->getModulo() . '_' . $arEntidad->getFuncion() . '_' . $arEntidad->getGrupo() . '_' . $arEntidad->getEntidad() . '_detalle', ['id' => $arRegistro->$getPk()]));
                } else {
                    return $this->redirect($this->generateUrl('admin_detalle', ['modulo' => $arEntidad->getModulo(), 'entidad' => $arEntidad->getEntidad(), 'id' => $arRegistro->$getPk()]));
                }
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_nuevo', ['modulo' => $arEntidad->getModulo(), 'entidad' => $arEntidad->getEntidad(), 'id' => 0]));
            }
        }
        return $this->render('estructura/nuevo.html.twig', [
            'arEntidad' => $arEntidad,
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
        return $this->get('form.factory')->createNamedBuilder('form')
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
    }

    /**
     * @author Andres Acevedo
     * @param $jsonFiltro
     * @return array
     */
    public function formularioFiltro($jsonFiltro)
    {
        $form = $this->get('form.factory')->createNamedBuilder('formFiltro', FormType::class, null, ['allow_extra_fields' => true])->getForm();
        $arrFiltros = json_decode($jsonFiltro);
        $boolFiltrar = false;
        foreach ($arrFiltros as $arrFiltro) {
            if ($arrFiltro->mostrar) {
                switch ($arrFiltro->tipo) {
                    case 'integer':
                        $tipo = IntegerType::class;
                        $propiedades = ['label' => $arrFiltro->alias, 'required' => false];
                        $boolFiltrar = true;
                        break;
                    case 'date':
                        $tipo = DateType::class;
                        $propiedades = ['label' => $arrFiltro->alias, 'data' => new \DateTime('now')];
                        $boolFiltrar = true;
                        break;
                    case 'boolean':
                        $tipo = CheckboxType::class;
                        $propiedades = ['label' => $arrFiltro->alias, 'required' => false];
                        $boolFiltrar = true;
                        break;
                    case 'string':
                        $tipo = TextType::class;
                        $propiedades = ['label' => $arrFiltro->alias, 'required' => false];
                        $boolFiltrar = true;
                        break;
                }
                $form->add($arrFiltro->campo, $tipo, $propiedades);
            }
        }
        $form->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']]);
        return ['form' => $form, 'filtrar' => $boolFiltrar];
    }

    /**
     * @param $arRegistro
     * @return mixed
     */
    public function validacionAdicional($arRegistro)
    {
        if (property_exists($arRegistro, 'fecha')) {
            $arRegistro->setFecha(new \DateTime('now'));
        }
        if (property_exists($arRegistro, 'usuario')) {
            $arRegistro->setUsuario($this->getUser()->getUsername());
        }
//        if (property_exists($arRegistro, 'codigoEntidadFk')) {
//            $arRegistro->setCodigoEntidadFk($entidadCubo);
//        }

        return $arRegistro;
    }

    /**
     * @author Juan Felipe Mesa Ocampo
     * @param $arRegistro GenCubo
     * @param $entidadCubo
     * @return mixed
     */
    public function generarPropiedadesFormCubo($arRegistro, $entidadCubo)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracionEntidad = $em->getRepository("App:General\GenEntidad")->find($entidadCubo);
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
