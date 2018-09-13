<?php

namespace App\Controller\Documental\Api;

use App\Entity\Documental\DocRegistro;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ApiRegistroController extends FOSRestController
{

    /**
     * @param Request $request
     * @param int $identificador
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Post("/documental/api/registro/masivo/{tipo}/{identificador}")
     */
    public function consulta($tipo = "", $identificador = 0) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $arRegistro = $this->getDoctrine()->getManager()->getRepository(DocRegistro::class)->findOneBy(array('identificador' => $identificador));
        if($arRegistro) {
            $archivo = "/almacenamiento/masivo/" . $tipo . "/" . $arRegistro->getDirectorio() . "/" . $arRegistro->getArchivoDestino() . "." . $arRegistro->getExtension();
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
