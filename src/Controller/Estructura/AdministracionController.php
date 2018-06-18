<?php

namespace App\Controller\Estructura;

use App\Entity\General\GenEntidad;
use App\Entity\General\GenCubo;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

final class AdministracionController extends Controller
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
//     * @param $entidad
//     * @param $entidadCubo
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @throws \PhpOffice\PhpSpreadsheet\Exception
//     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
//     * @Route("lista/{entidad}/{entidadCubo}",name="lista")
//     */
//    public function generarLista(Request $request, $entidad, $entidadCubo = "")
//    {
//        $em = $this->getDoctrine()->getManager();
//        $paginator = $this->get('knp_paginator');
//        $arConfiguracionEntidad = $em->getRepository('App:General\GenEntidad')->find($entidad);
//        $jsonFiltro = $arConfiguracionEntidad->getJsonFiltro();
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
//        $arRegistros = $em->getRepository('App:General\GenEntidad')->lista($arConfiguracionEntidad, 0, $entidadCubo);
//        if ($request->getMethod() == 'POST') {
//            if ($request->request->has('form')) {
//                if ($form->isSubmitted() && $form->isValid()) {
//                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                    if ($form->get('btnExcel')->isClicked()) {
//                        $arRegistrosExcel = $em->getRepository('App:General\GenEntidad')->lista($arConfiguracionEntidad, 1, $entidadCubo);
//                        $this->generarExcel($arRegistrosExcel, 'Excel');
//                    }
//                    if ($form->get('btnEliminar')->isClicked()) {
//                        $respuesta = $em->getRepository('App:General\GenEntidad')->eliminar($arConfiguracionEntidad, $arrSeleccionados);
//                        $this->validarRespuesta($respuesta, $em);
//                        return $this->redirectToRoute("lista", ['entidad' => $entidad, 'entidadCubo' => $entidadCubo]);
//                    }
//                }
//            }
//            if ($request->request->has('formFiltro')) {
//                if ($formFiltro instanceof Form) {
//                    if ($formFiltro->get('btnFiltrar')->isClicked()) {
//                        $arrFiltros = $formFiltro->getData();
//                        $arRegistros = $em->getRepository('App:General\GenEntidad')->listaFiltro($arConfiguracionEntidad, $arrFiltros);
//                    }
//                }
//            }
//        }
//        $arRegistros = $paginator->paginate($arRegistros, $request->query->getInt('page', 1), 10);
//        return $this->render('estructura/lista.html.twig', [
//            'arRegistros' => $arRegistros,
//            'entidadCubo' => $entidadCubo,
//            'arConfiguracionEntidad' => $arConfiguracionEntidad,
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("admin/{modulo}/{entidad}/lista",name="admin_lista")
     */
    public function generarAdminLista(Request $request, $modulo, $entidad)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $codigo = $modulo . "_" . $entidad;
        $arEntidad = $em->getRepository('App:General\GenEntidad')->find($codigo);
        $jsonFiltro = $arEntidad->getJsonFiltro();
        $arrCamposFiltro = json_decode($jsonFiltro);
        $arrNombreCamposFiltro = [];
        foreach ($arrCamposFiltro as $arrCampo) {
            if ($arrCampo->mostrar) {
                $arrNombreCamposFiltro[] = $arrCampo->campo;
            }
        }
        $arrFiltrar = $this->formularioFiltro($jsonFiltro);
        if ($arrFiltrar['filtrar']) {
            $formFiltro = $arrFiltrar['form'];
            $formFiltro->handleRequest($request);
        } else {
            $formFiltro = '';
        }
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if ($arEntidad->getPersonalizado()) {
            $arRegistros = $em->getRepository('App:General\GenEntidad')->lista($arEntidad, 0);
        } else {
            $arRegistros = $em->getRepository('App:' . ucfirst($arEntidad->getModulo()) . "\\" . ucfirst($arEntidad->getPrefijo()) . ucfirst($arEntidad->getEntidad()))->camposPredeterminados();
        }
        if ($request->getMethod() == 'POST') {
            if ($request->request->has('form')) {
                if ($form->isSubmitted() && $form->isValid()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if ($form->get('btnExcel')->isClicked()) {
                        $arRegistrosExcel = $em->getRepository('App:General\GenEntidad')->lista($arEntidad, 1);
                        $this->generarExcel($arRegistrosExcel, 'Excel');
                    }
                    if ($form->get('btnEliminar')->isClicked()) {
                        $em->getRepository('App:General\GenEntidad')->eliminar($arEntidad, $arrSeleccionados);
                        return $this->redirect($this->generateUrl('admin_lista',['modulo' => $arEntidad->getModulo(),'entidad' => $arEntidad->getEntidad()]));
                    }
                }
            }
            if ($request->request->has('formFiltro')) {
                if ($formFiltro instanceof Form) {
                    if ($formFiltro->get('btnFiltrar')->isClicked()) {
                        $arrFiltros = $formFiltro->getData();
                        $arRegistros = $em->getRepository('App:General\GenEntidad')->listaFiltro($arEntidad, $arrFiltros);
                    }
                }
            }
        }
        $arRegistros = $paginator->paginate($arRegistros, $request->query->getInt('page', 1), 10);
        return $this->render('estructura/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'entidadCubo' => "",
            'arEntidad' => $arEntidad,
            'form' => $form->createView(),
            'formFiltro' => $formFiltro instanceof Form ? $formFiltro->createView() : '',
            'filtrar' => $arrFiltrar['filtrar']
        ]);
    }

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
            'arrCampos' => $arrCampos
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
                    return $this->redirect($this->generateUrl($arEntidad->getPrefijo().'_'.substr($arEntidad->getFuncion(), 0,3).'_'.$arEntidad->getModulo().'_'.$arEntidad->getEntidad().'_detalle',['id' => $arRegistro->$getPk()]));
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
