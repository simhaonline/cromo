<?php

namespace App\General;

use App\Utilidades\Mensajes;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

final class General
{
    private $container;
    private $edad = 25;

    private function __construct()
    {
        /**
         * Lógica para inicializar utilidades...
         */
        global $kernel;
        $this->container = $kernel->getContainer();
    }

    /**
     * @return General
     */
    public static function get()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new General();
        }
        return $instance;
    }

    /**
     * @param $arrDatos array
     * @param $nombre string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function setExportar($arrDatos, $nombre)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if (count($arrDatos) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $j = 0;
            //Se obtienen las columnas del archivo
            $arrColumnas = array_keys($arrDatos[0]);
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $sheet->getColumnDimension($i)->setAutoSize(true);
                $sheet->getStyle(1)->getFont()->setBold(true);
                $campo = strpos($arrColumnas[$j], 'Pk') !== false ? 'ID' : $arrColumnas[$j];
                $sheet->setCellValue($i . '1', strtoupper($campo));
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
            header("Content-Disposition: attachment;filename={$nombre}.xls");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        } else {
            Mensajes::error('El listado esta vacío, no hay nada que exportar');
        }
    }
}