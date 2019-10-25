<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAcreditacion;
use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracionCuenta;
use App\Entity\RecursoHumano\RhuConsecutivo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionAdicional;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuAcreditacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuAcreditacion::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoAcreditacion = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $fechaDesdeVenceCurso = null;
        $fechaHastaVenceCurso = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;
        $estadoRechazado = null;
        $estadoValidado = null;
        $estadoAcreditado = null;


        if ($filtros) {
            $codigoAcreditacion = $filtros['codigoAcreditacion'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
            $fechaDesdeVenceCurso = $filtros['fechaDesdeVenceCurso'] ??null;
            $fechaHastaVenceCurso = $filtros['fechaHastaVenceCurso'] ??null;
            $estadoRechazado = $filtros['estadoRechazado'] ??null;
            $estadoValidado = $filtros['estadoValidado'] ??null;
            $estadoAcreditado = $filtros['estadoAcreditado'] ??null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAcreditacion::class, 'a')
            ->select('a.codigoAcreditacionPk')
            ->addselect('at.nombre AS tipo')
            ->addSelect('a.fecha')
            ->addSelect('a.fechaVenceCurso')
            ->addselect('e.numeroIdentificacion as numeroIdentificacion')
            ->addselect('e.nombreCorto as empleado')
            ->addSelect('a.numeroRegistro')
            ->addSelect('a.estadoValidado')
            ->addSelect('a.estadoRechazado')
            ->addSelect('a.estadoAcreditado')
            ->addSelect('a.fechaAcreditacion')
            ->addselect('a.estadoAutorizado')
            ->addselect('a.estadoAprobado')
            ->addselect('a.estadoAnulado')
            ->leftJoin('a.acreditacionTipoRel', 'at')
            ->leftJoin('a.empleadoRel', 'e');
        if ($codigoAcreditacion) {
            $queryBuilder->andWhere("a.codigoAcreditacionPk = '{$codigoAcreditacion}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("a.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("a.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("a.fecha <= '{$fechaHasta} 23:59:59'");
        }
        if ($fechaDesdeVenceCurso) {
            $queryBuilder->andWhere("a.fechaVenceCurso >= '{$fechaDesdeVenceCurso} 00:00:00'");
        }
        if ($fechaHastaVenceCurso) {
            $queryBuilder->andWhere("a.fechaVenceCurso <= '{$fechaHastaVenceCurso} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAnulado = 1");
                break;
        }
        switch ($estadoRechazado) {
            case '0':
                $queryBuilder->andWhere("a.estadoRechazado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoRechazado = 1");
                break;
        }
        switch ($estadoValidado) {
            case '0':
                $queryBuilder->andWhere("a.estadoValidado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoValidado = 1");
                break;
        }
        switch ($estadoAcreditado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAcreditado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAcreditado = 1");
                break;
        }

        $queryBuilder->addOrderBy('a.codigoAcreditacionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function listaInformeApo()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuAcreditacion::class, 'a')
            ->select('a')
            ->leftJoin('a.empleadoRel', 'e');
        return $queryBuilder->getQuery()->getResult();

    }

    public function generarInfromeApo()
    {
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);
        $arAcreditaciones =$this->listaInformeApo();
        $errores = [];
        $objExcel = new Spreadsheet();
        $objExcel->getActiveSheet();
        $i = 2;
        // Set document properties
        $objExcel->getProperties()->setCreator("EMPRESA")
        ->setLastModifiedBy("EMPRESA")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
        //asignar estilos
        $objExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'Y'; $col++) {
            $objExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');
        }
        $objExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nit')
            ->setCellValue('B1', 'RazonSocial')
            ->setCellValue('C1', 'TipoDocumento')
            ->setCellValue('D1', 'NoDocumento')
            ->setCellValue('E1', 'Nombre1')
            ->setCellValue('F1', 'Nombre2')
            ->setCellValue('G1', 'Apellido1')
            ->setCellValue('H1', 'Apellido2')
            ->setCellValue('I1', 'FechaNacimiento')
            ->setCellValue('J1', 'Genero')
            ->setCellValue('K1', 'Cargo')
            ->setCellValue('L1', 'FechaVinculacion')
            ->setCellValue('M1', 'CodigoCurso')
            ->setCellValue('N1', 'NitEscuela')
            ->setCellValue('O1', 'Nro')
            ->setCellValue('P1', 'TipoEstablecimiento')
            ->setCellValue('Q1', 'TelefonoR')
            ->setCellValue('R1', 'DireccionR')
            ->setCellValue('S1', 'DireccionP')
            ->setCellValue('T1', 'Departamento')
            ->setCellValue('U1', 'Ciudad')
            ->setCellValue('V1', 'EducacionBM')
            ->setCellValue('W1', 'EducacionS')
            ->setCellValue('X1', 'Discapacidad');

        foreach ($arAcreditaciones as $arAcreditacion) {
            //tipo identificacion
            $tipoIdentificacion = 1;
            if ($arAcreditacion->getEmpleadoRel()->getIdentificacionRel()->getCodigoInterface() == 13) {
                $tipoIdentificacion = 1;
            }
            if ($arAcreditacion->getEmpleadoRel()->getIdentificacionRel()->getCodigoInterface() == 12) {
                $tipoIdentificacion = 1;
            }
            if ($arAcreditacion->getEmpleadoRel()->getIdentificacionRel()->getCodigoInterface() == 21) {
                $tipoIdentificacion = 3;
            }
            if ($arAcreditacion->getEmpleadoRel()->getIdentificacionRel()->getCodigoInterface() == 22) {
                $tipoIdentificacion = 3;
            }
            if ($arAcreditacion->getEmpleadoRel()->getIdentificacionRel()->getCodigoInterface() == 41) {
                $tipoIdentificacion = 6;
            }

            //CONTRATO
            $codigoContrato = "";
            if ($arAcreditacion->getEmpleadoRel()->getEstadoContrato()) {
                $codigoContrato = $arAcreditacion->getEmpleadoRel()->getCodigoContratoFk();
            } else {
                $codigoContrato = $arAcreditacion->getEmpleadoRel()->getCodigoContratoUltimoFk();
            }
            if ($codigoContrato == NULL || $codigoContrato == "") {
                $errores[] = ['Empleado' => $arAcreditacion->getEmpleadoRel()->getNumeroIdentificacion(), 'error' => 'No tiene contrato activo, ni ultimo contrato'];
            }
            if (count($errores) <= 0) {
                $arContrato = $em->getRepository(RhuContrato::class)->find($codigoContrato);
                $gradoBachiller = "11";
                $superior = "Ninguna";
                //Recortar cadena de la ciudad del empleado
                $ciudadLabora = $arAcreditacion->getEmpleadoRel()->getCiudadRel()->getNombre();
                $ciudadLabora = explode("-", $ciudadLabora);
                $objExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arConfiguracion->getNit() . $arConfiguracion->getDigitoVerificacion())
                    ->setCellValue('B' . $i, strtoupper($arConfiguracion->getNombre()))
                    ->setCellValue('C' . $i, $tipoIdentificacion)
                    ->setCellValue('D' . $i, $arAcreditacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getNombre1()))
                    ->setCellValue('F' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getNombre2()))
                    ->setCellValue('G' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getApellido1()))
                    ->setCellValue('H' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getApellido2()))
                    ->setCellValue('I' . $i, $arAcreditacion->getEmpleadoRel()->getFechaNacimiento()->format('d/m/Y'))
                    ->setCellValue('J' . $i, $arAcreditacion->getEmpleadoRel()->getCodigoSexoFk() == "M" ? 1 : 2)
                    ->setCellValue('K' . $i, $arAcreditacion->getAcreditacionTipoRel() != null ? $arAcreditacion->getAcreditacionTipoRel()->getCodigoCargoFk():"")
                    ->setCellValue('L' . $i,  $arAcreditacion->getEmpleadoRel()->getContratoRel()->getFecha()?? '')
                    ->setCellValue('M' . $i, $arAcreditacion->getAcreditacionTipoRel()->getCodigoAcreditacionTipoPk())
                    ->setCellValue('N' . $i, $arAcreditacion->getAcademiaRel() ? $arAcreditacion->getAcademiaRel()->getNit() : "")
                    ->setCellValue('O' . $i, $arAcreditacion->getNumeroRegistro())
                    ->setCellValue('P' . $i, "Principal")
                    ->setCellValue('Q' . $i, $arAcreditacion->getEmpleadoRel()->getTelefono())
                    ->setCellValue('R' . $i, $arAcreditacion->getEmpleadoRel()->getDireccion())
                    ->setCellValue('S' . $i, $arConfiguracion->getDireccion())
                    ->setCellValue('T' . $i, $arAcreditacion->getEmpleadoRel()->getCiudadRel()->getDepartamentoRel()->getNombre())
                    ->setCellValue('U' . $i, $ciudadLabora[0])
                    ->setCellValue('V' . $i, $gradoBachiller)
                    ->setCellValue('W' . $i, ucfirst($superior))
                    ->setCellValue('X' . $i, "Ninguna");
                $i++;
            }
        }
        $nombreArchivo = "APO" . $arConfiguracion->getNit() . $arConfiguracion->getDigitoVerificacion() . "" . date('Ymd') . "01";
        $objExcel->getActiveSheet()->setTitle('ApoDatos');
        //Hoja de retiros
        $objExcel->createSheet(1)->setTitle('Retiros')
            ->setCellValue('A1', 'Nit')
            ->setCellValue('B1', 'RazonSocial')
            ->setCellValue('C1', 'TipoDocumento')
            ->setCellValue('D1', 'NoDocumento')
            ->setCellValue('E1', 'FechaRetiro');

        /**
         * ToDo: Se remueve debido a que se listan demasiados registros de contratos, ralentizando la generacion del excel
         */
//        $arContratos = $em->getRepository(RhuContrato::class)->findBy(array('estadoTerminado' => 1));
//        foreach ($arContratos as $arContrato) {
//
//            //tipo identificacion
//            $tipoIdentificacion = 1;
//            if ($arContrato->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 13) {
//                $tipoIdentificacion = 1;
//            }
//            if ($arContrato->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 12) {
//                $tipoIdentificacion = 1;
//            }
//            if ($arContrato->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 21) {
//                $tipoIdentificacion = 3;
//            }
//            if ($arContrato->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 22) {
//                $tipoIdentificacion = 3;
//            }
//            if ($arContrato->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 41) {
//                $tipoIdentificacion = 6;
//            }
//            $objPHPExcel->setActiveSheetIndex(1)
//                    ->setCellValue('A2', $arConfiguracion->getNitEmpresa() . $arConfiguracion->getDigitoVerificacionEmpresa())
//                    ->setCellValue('B2', strtoupper($arConfiguracion->getNombreEmpresa()))
//                    ->setCellValue('C2', $tipoIdentificacion)
//                    ->setCellValue('D2', $arContrato->getEmpleadoRel()->getNombreCorto())
//                    ->setCellValue('E2', $arContrato->getFechaHasta());
//        }
        if (count($errores) > 0) {
            $strMensaje = "No se puede exportar el informe APO, por que los siguientes empleados tienen errores, por favor corregirlos: ";
            $strMensaje .= "<ul>";
            $strMensaje .= implode("", array_map(function ($acreditacion) {
                if (!is_array($acreditacion['error'])) {
                    return "<li>" . $acreditacion['Empleado'] . ": " . $acreditacion['error'] . "</li>";
                } else {
                    return "<li>" . $acreditacion['Empleado'] . ": " . implode(' | ', $acreditacion['error']) . "</li>";
                }
            }, $errores));
            $strMensaje .= "</ul>";
            Mensajes::error($strMensaje);
        } else {
            $objExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $nombreArchivo . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter = new Xlsx($objExcel);
            $objWriter->save('php://output');
            exit;
        }
    }


}