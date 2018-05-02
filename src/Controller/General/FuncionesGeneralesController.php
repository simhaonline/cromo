<?php

namespace App\Controller\General;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FuncionesGeneralesController
{
    const TP_ERROR = "error";
    const TP_OK = "ok";
    const TP_INFO = "info";

    /**
     * Construye los parametros requeridos para generar un mensaje
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
            $em->flush();
        }
        return $this;
    }

    /**
     * @author Andres Acevedo Cartagena
     * @param $arrDatos array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generarExcel($arrDatos)
    {
        if (count($arrDatos) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $j = 0;
            //Se obtienen las columnas del archivo
            $arrColumnas = array_keys($arrDatos[0]);
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                //Se valida si la columna es el pk para cambiar su nombre en el excel por ID
                if (strpos($arrColumnas[$j], 'Pk')) {
                    $sheet->setCellValue($i . '1', 'ID');
                } elseif (preg_match('/[A-Z]/', $arrColumnas[$j])) {
                    $arrCol = preg_split('/(?=[A-Z])/', $arrColumnas[$j]);
                    $arrCol = implode('_', ($arrCol));
                    $sheet->setCellValue($i . '1', strtoupper($arrCol));
                } else {
                    $sheet->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                }
                $j++;
            }
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
                $sheet->setCellValue($i . $j, $dato);
                $i++;
            }
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Solicitudes.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

}
