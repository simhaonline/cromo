<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteMonitoreo;
use App\Entity\Transporte\TteMonitoreoDetalle;
use App\Entity\Transporte\TteMonitoreoRegistro;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteNovedadTipo;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ApiMonitoreoController extends FOSRestController
{

    /**
     * @param Request $request
     * @param int $codigoDespacho
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @Rest\Get("/transporte/api/app/monitoreo/registro/{codigoDespacho}/{latitud}/{longitud}")
     */
    public function registro(Request $request, $codigoDespacho = 0, $latitud, $longitud) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $error = false;
        $mensaje = "";
        $arMonitoreo = $em->getRepository(TteMonitoreo::class)->findOneBy(array('codigoDespachoFk' => $codigoDespacho));
        if($arMonitoreo) {
            $arMonitoreoRegistro = new TteMonitoreoRegistro();
            $arMonitoreoRegistro->setMonitoreoRel($arMonitoreo);
            $arMonitoreoRegistro->setFecha(new \DateTime('now'));
            $arMonitoreoRegistro->setLatitud($latitud);
            $arMonitoreoRegistro->setLongitud($longitud);
            $em->persist($arMonitoreoRegistro);
            $em->flush();
        } else {
            $error = true;
            $mensaje = "No existe monitoreo de este despacho";
        }
        return [
            'error' => $error,
            'mensaje' => $mensaje
        ];
    }

}
