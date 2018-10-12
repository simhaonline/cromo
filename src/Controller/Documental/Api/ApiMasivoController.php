<?php

namespace App\Controller\Documental\Api;

use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocDirectorio;
use App\Entity\Documental\DocMasivo;
use App\Entity\Documental\DocMasivoTipo;
use App\Entity\General\GenConfiguracion;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
class ApiMasivoController extends FOSRestController
{

    /**
     * @param Request $request
     * @param int $identificador
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/documental/api/masivo/masivo/{tipo}/{identificador}")
     */
    public function consulta($tipo = "tte_guia", $identificador = 0) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arMasivo = $this->getDoctrine()->getManager()->getRepository(DocMasivo::class)->findOneBy(array('codigoMasivoTipoFk'=>$tipo, 'identificador' => $identificador));
        if($arMasivo) {
            $arrConfiguracion = $this->getDoctrine()->getManager()->getRepository(DocConfiguracion::class)->archivoMasivo();
            //$archivo = "/almacenamiento/masivo/" . $tipo . "/" . $arMasivo->getDirectorio() . "/" . $arMasivo->getArchivoDestino();
            $archivo = $arrConfiguracion['rutaAlmacenamiento'] . "/masivo/" . $arMasivo->getCodigoMasivoTipoFk() . "/" .  $arMasivo->getDirectorio() . "/" . $arMasivo->getArchivoDestino();
            //$archivo = "/almacenamiento/masivo/guia/1/990023629.pdf";

            $type = pathinfo($archivo, PATHINFO_EXTENSION);
            $data = file_get_contents($archivo);
            $size = filesize($archivo); //bytes
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            return [
                'status' => true,
                'binary' => $base64,
                'size' => $size,
                'type' => $type

            ];
        } else {
            return [
                'status' => false,
                'binary' => "",
                'size' => "",
                'type' => ""

            ];
        }
    }

    /**
     * @param Request $request
     * @param int $identificador
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/documental/api/masivo/crear/{tipo}/{identificador}")
     */
    public function crear($tipo = "tte_guia", $identificador = 0) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $directorioTemporal = $em->getRepository(GenConfiguracion::class)->parametro('rutaTemporal');

        $arMasivoTipo = $em->getRepository(DocMasivoTipo::class)->find($tipo);
        $arrConfiguracion = $em->getRepository(DocConfiguracion::class)->archivoMasivo();
        //$directorioBandeja = $arrConfiguracion['rutaBandeja'];
        $directorioDestino = $arrConfiguracion['rutaAlmacenamiento'] . "/masivo/";
        if(file_exists($directorioDestino)) {
            $arDirectorio = $em->getRepository(DocDirectorio::class)->findOneBy(array('tipo' => 'M', 'codigoMasivoTipoFk' => $tipo));
            if(!$arDirectorio) {
                $arDirectorio = new DocDirectorio();
                $arDirectorio->setCodigoMasivoTipoFk($tipo);
                $arDirectorio->setDirectorio(1);
                $arDirectorio->setNumeroArchivos(0);
                $arDirectorio->setTipo('M');
                $em->persist($arDirectorio);
                $em->flush();
            }
            if($arDirectorio) {
                $arDirectorio = $em->getRepository(DocDirectorio::class)->find($arDirectorio->getCodigoDirectorioPk());
                if($arDirectorio->getNumeroArchivos() >= 50000) {
                    $arDirectorio->setNumeroArchivos(0);
                    $arDirectorio->setDirectorio($arDirectorio->getDirectorio()+1);
                    $em->persist($arDirectorio);
                    $em->flush();
                }
                $directorio = $directorioDestino . $tipo . "/" . $arDirectorio->getDirectorio() . "/";
                if(!file_exists($directorio)) {
                    if(!mkdir($directorio, 0777, true)) {
                        die('Fallo al crear directorio...' . $directorio);
                    }
                }
                $origen = $directorioTemporal .  $identificador . ".jpg";
                $destinoConversion = $directorioTemporal .  $identificador . ".pdf";

                $imagen = getimagesize($origen);    //Sacamos la información
                $ancho = $imagen[0] / 3.77952755905511;              //Ancho
                $alto = $imagen[1] / 3.77952755905511;
                $pdf = new \FPDF('L','mm',array($alto,$ancho));
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->SetFont('Times', '', 12);
                $pdf->image($origen,0,0);
                $pdf->Output($destinoConversion, 'F');
                unlink($origen);
                $origen = $destinoConversion;
                if(file_exists($origen)) {
                    $extension = "pdf";
                    $archivo = $identificador . ".pdf";
                    $tamano = filesize($origen);
                    $arMasivo = new DocMasivo();
                    $arMasivo->setIdentificador($identificador);
                    $arMasivo->setMasivoTipoRel($arMasivoTipo);
                    $arMasivo->setArchivo($archivo);
                    $arMasivo->setExtension($extension);
                    $arMasivo->setDirectorio($arDirectorio->getDirectorio());
                    $arMasivo->setTamano($tamano);
                    $archivoDestino = rand(100000, 999999) . "_" . $identificador;
                    $arMasivo->setArchivoDestino($archivoDestino . "." . $extension);
                    $destino = $directorio . $archivoDestino . "." . $extension;
                    $em->persist($arMasivo);

                    $arDirectorio->setNumeroArchivos($arDirectorio->getNumeroArchivos()+1);
                    $em->persist($arDirectorio);
                    $em->flush();
                    copy($origen, $destino);
                    unlink($origen);
                }

            }
        } else {
            Mensajes::error("No existe el directorio principal " . $directorioDestino);
        }


        return [
            'status' => false,
            'binary' => "",
            'size' => "",
            'type' => ""

        ];
    }

}
