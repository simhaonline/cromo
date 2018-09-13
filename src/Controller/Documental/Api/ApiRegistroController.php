<?php

namespace App\Controller\Documental\Api;

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
     * @Rest\Get("/documental/api/registro/masivo/{identificador}")
     */
    public function consulta(Request $request, $identificador = 0) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $archivo = "/almacenamiento/masivo/guia/1/990023629.pdf";
        try{
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
        }
        catch (\Exception $exception){
            return ['error'=>$exception];
        }
    }

}
