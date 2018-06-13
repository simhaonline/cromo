<?php

namespace App\Controller\Transporte\Api;

use App\Entity\Transporte\TteGuia;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ApiMovilConductorController extends FOSRestController
{
    /**
     * @Rest\Get("/tte/api/movil/conductor/despacho/guia/{codigoDespacho}", name="tte_api_movil_conductor_despacho_guia")
     */
    public function guia(Request $request, $codigoDespacho)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT g.codigoGuiaPk, 
        WHERE g.codigoDespachoFk = :codigoDespacho'
        )->setParameter('codigoDespacho', 1);

        $qb = $em->createQueryBuilder();
        $qb->from(TteGuia::class, "g")
            ->select("g.codigoGuiaPk")
            ->where("g.codigoDespachoFk = 1");
        return $qb->getQuery()->getResult();

        //return $query->execute();
    }


}
