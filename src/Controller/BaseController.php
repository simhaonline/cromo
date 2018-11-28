<?php

namespace App\Controller;

use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class BaseController extends Controller
{
    protected $request = null;

    /**
     * @return array
     */
    protected function getDatosLista($filtro=false)
    {
        $paginator = $this->get('knp_paginator');
        $nombreRepositorio = "App:{$this->modulo}\\{$this->claseNombre}";
        $namespaceType = "\\App\\Form\\Type\\{$this->modulo}\\{$this->nombre}Type";
        $campos = json_decode($namespaceType::getEstructuraPropiedadesLista());
        if(!$filtro){
        $queryBuilder = $this->getGenerarQuery($nombreRepositorio, $campos);
        }
        else{
            $camposFiltro = json_decode($namespaceType::getEstructuraPropiedadesFiltro(), true);
            $queryBuilder = $this->getGenerarQueryConFiltro($nombreRepositorio, $camposFiltro);
        }
        /** @var  $queryBuilder QueryBuilder */
        return [
            'ruta' => strtolower($this->modulo) . "_" . strtolower($this->funcion) . "_" . strtolower($this->grupo) . "_" . strtolower($this->nombre),
            'arrCampos' => $campos,
            'arDatos' => $paginator->paginate($queryBuilder->getQuery(), $this->request->query->getInt('page', 1), 30)
        ];
    }

    protected function getFiltroLista()
    {
        $namespaceType = "\\App\\Form\\Type\\{$this->modulo}\\{$this->nombre}Type";
        $campos = json_decode($namespaceType::getEstructuraPropiedadesFiltro(), true);
        $session = new Session();
        $form = $this->createFormBuilder();
        if ($campos) {
            foreach ($campos as $campo) {
                $tipoNombre = $campo['tipo'];
                $tipo = "Symfony\\Component\Form\Extension\\Core\\Type\\{$tipoNombre}";
                if ($campo['tipo'] == "EntityType") {
                    $session->set($this->claseNombre . "_" . $campo['child'], '');
                    $entidad = $campo['propiedades']['class'];
                    $nombreRepositorio = "App:{$this->modulo}\\{$entidad}";
                    $form->add($campo['child'], EntityType::class,
                        [
                            'label' => $campo['propiedades']['label'],
                            'required' => false,
                            'class' => $nombreRepositorio,
                            'choice_label' => $campo['propiedades']['choice_label'],
                            'placeholder' => "TODO",
                        ]);
                } else if ($campo['tipo'] == "DateType") {
                    $session->set($this->claseNombre . "_" . $campo['child'], null);
//                    $dateFecha = new \DateTime('now');
                    $form->add($campo['child'], $tipo,
                        [
                            'required' => false,
                            'data' => null,
                            'widget' => 'single_text',
                            'format' => 'yyyy-MM-dd',
                            'attr' => array('class' => 'date')
                        ]);
                }
                else if($campo['tipo'] == "Tercero"){
                    $form->add("txtCodigoTercero", TextType::class, ['required' => false, 'data' => $session->get($this->claseNombre . '_terceroRel.codigoTerceroPk')??""])
                        ->add("txtNombreCorto", TextType::class, ['required' => false, 'data' => $session->get($this->claseNombre . '_nombreCorto')??"", 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']]);
                }
                 else if ($campo['tipo'] != "SubmitType" && $campo['tipo'] != "CheckboxType" && $campo['tipo'] != "ChoiceType") {
                    $form->add($campo['child'], $tipo, ['label' => $campo['propiedades']['label'], 'required' => false, 'data' => $session->get($this->claseNombre . "_" . $campo['child']) ?? ""]);
                }
                else {
                    if ($campo['tipo'] == "ChoiceType") {
                        $session->set($this->claseNombre . "_" . $campo['child'], null);
                        $form->add($campo['child'], $tipo, ['label' => $campo['propiedades']['label'], 'required' => false, 'placeholder' => 'TODO', 'attr' => ['class' => 'form-control'], 'choices' => $campo['propiedades']['choices']]);
                    } else {
                        $form->add($campo['child'], $tipo, ['label' => $campo['propiedades']['label'], 'required' => false, 'attr' => $campo['tipo'] != "CheckboxType" ? ['class' => 'form-control'] : []]);
                    }
                }
            }
            $form->add("btnFiltro", SubmitType::class, ['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']]);
        }

        return $form->getForm();
    }

    protected function botoneraLista()
    {
        return $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn-sm btn btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-deafult']])
            ->getForm();
    }

    /**
     * @param $submittedButton string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function getDatosExportar($submittedButton)
    {
        $nombreRepositorio = "App:{$this->modulo}\\{$this->claseNombre}";
        $namespaceType = "\\App\\Form\\Type\\{$this->modulo}\\{$this->nombre}Type";
        $campos = json_decode($namespaceType::getEstructuraPropiedadesExportar());
        /** @var  $queryBuilder QueryBuilder */
        $queryBuilder = $this->getGenerarQuery($nombreRepositorio, $campos);
        switch ($submittedButton) {
            case 'btnExcel':
                $this->generarExcel($campos, $queryBuilder->getQuery()->execute(), $this->nombre);
                break;
        }
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param $arrDatos
     * @param $nombre
     * @param $campos
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generarExcel($campos, $arrDatos, $nombre)
    {
        if (count($arrDatos) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $j = 0;
            //Se obtienen las columnas del archivo
            $arrColumnas = array_keys($arrDatos[0]);
            $arrCampos = array_map(function ($campo) {
                return $campo->titulo;
            }
                , $campos);
            for ($i = 'A'; $j <= sizeof($arrCampos) - 1; $i++) {
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
                $sheet->setCellValue($i . '1', strtoupper($arrCampos[$j]));
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
                    } elseif (is_bool($dato)) {
                        $dato = $dato ? 'SI' : 'NO';
                    }
                    $spreadsheet->getActiveSheet()->getStyle($i)->getFont()->setBold(false);
                    $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
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
            Mensajes::error('El listado esta vacío, no hay nada que exportar');
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

    /**
     * @param $nombreRepositorio
     * @param $campos
     * @return QueryBuilder
     */
    private function getGenerarQuery($nombreRepositorio, $campos)
    {
        $arrRelaciones = [];
        /** @var  $queryBuilder QueryBuilder */
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()->from($nombreRepositorio, 'e')
            ->select('e.' . $campos[0]->campo);
        foreach ($campos as $campo) {
            if ($campo->tipo != "pk" && !isset($campo->relacion)) {
                $queryBuilder->addSelect('e.' . $campo->campo);
            } elseif (isset($campo->relacion)) {
                $arrRel = explode('.', $campo->campo);
                $alias = substr($arrRel[0], 0, 3) . 'Rel' . $arrRel[1];
                if (!$this->validarRelacion($arrRelaciones, $arrRel[0])) {
                    $arrRelaciones[] = $arrRel[0];
                    $queryBuilder->leftJoin('e.' . $arrRel[0], $arrRel[0]);
                    $queryBuilder->addSelect($arrRel[0] . '.' . $arrRel[1] . ' AS ' . $alias);
                } else {
                    $queryBuilder->addSelect($arrRel[0] . '.' . $arrRel[1] . ' AS ' . $alias);
                }
            }
        }

        return $queryBuilder;
    }


    /**
     * @param $nombreRepositorio
     * @param $campos
     * @return QueryBuilder
     */
    private function getGenerarQueryConFiltro($nombreRepositorio, $campos)
    {
        $claseNombre = $this->claseNombre;
        $arrRelaciones = [];
        $session = new Session();
        /** @var  $queryBuilder QueryBuilder */
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()->from($nombreRepositorio, 'e')
            ->select($campos[0]['child']);
        $namespaceType = "\\App\\Form\\Type\\{$this->modulo}\\{$this->nombre}Type";
        $camposTabla = json_decode($namespaceType::getEstructuraPropiedadesLista());

        foreach ($camposTabla as $camposT){
            if(!isset($camposT->relacion)){
            $queryBuilder->addSelect('e.' . $camposT->campo);
            }
            else{
                $arrRel = explode('.', $camposT->campo);
                $alias = substr($arrRel[0], 0, 3) . 'Rel' . $arrRel[1];
                $queryBuilder->addSelect($camposT->campo . ' AS ' . $alias);
            }
        }
        foreach ($campos as $campo) {
            $filtro = $session->get($claseNombre . "_" . $campo['child']);
            if (!isset($campo['relacion'])) {
                if(strlen($campo['child']) >= 5 && substr($campo['child'], 0, 5) == "fecha"){
                    $queryBuilder->addSelect('e.' . (substr($campo['child'], 0, strlen($campo['child'])-5)));

                }
                else{

                $queryBuilder->addSelect('e.' . $campo['child']);
                }
            } elseif (isset($campo['relacion'])) {
                $arrRel = explode('.', $campo['child']);
                $alias = substr($arrRel[0], 0, 3) . 'Rel' . $arrRel[1];
                if (!$this->validarRelacion($arrRelaciones, $arrRel[0])) {
                    $arrRelaciones[] = $arrRel[0];
                    $queryBuilder->leftJoin('e.' . $arrRel[0], $arrRel[0]);
                    $queryBuilder->addSelect($arrRel[0] . '.' . $arrRel[1] . ' AS ' . $alias);
                } else{
                    $queryBuilder->addSelect($arrRel[0] . '.' . $arrRel[1] . ' AS ' . $alias);
                }

                if($claseNombre) {
                    if ($filtro != "" && $filtro != null) {
                        $queryBuilder->andWhere($arrRel[0] . '.' . $arrRel[1] . "={$filtro}");
                    }
                }
            }

            if ($claseNombre && !isset($campo['relacion'])) {
                if (strlen($campo['child']) >= 5 && substr($campo['child'], 0, 5) == "fecha") {
                    $fecha = $session->get($claseNombre . "_" . $campo['child']);
                    if ($fecha!==null) {
                        $campoExplode=substr($campo['child'], 0, strlen($campo['child'])-5);
                        if(substr($campo['child'],  -5)==="Desde"){
                        $queryBuilder->andWhere('e.' . $campoExplode . ">='{$fecha}'");
                        }
                        else{
                            $queryBuilder->andWhere('e.' . $campoExplode . "<='{$fecha}'");
                        }
                    }
                } else {
                    if ($filtro !== "" && $filtro !== null) {

                        $queryBuilder->andWhere('e.'.$campo['child'] . "='{$filtro}'");
                    }
                }
            }
        }

        return $queryBuilder;
    }

    /**
     * @param $arrRelaciones
     * @param $relacion
     * @return bool
     */
    private function validarRelacion($arrRelaciones, $relacion)
    {
        $relExiste = false;
        foreach ($arrRelaciones as $arrRelacion) {
            if ($arrRelacion == $relacion) {
                $relExiste = true;
                break;
            }
        }
        if ($relExiste) {
            return true;
        } else {
            return false;
        }
    }
}