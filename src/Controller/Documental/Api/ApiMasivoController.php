<?php

namespace App\Controller\Documental\Api;

use App\Entity\Documental\DocConfiguracion;
use App\Entity\Documental\DocMasivo;
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

}
