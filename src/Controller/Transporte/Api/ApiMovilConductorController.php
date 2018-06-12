<?php

namespace App\Controller\Transporte\Api;

use App\Controller\ApiBaseController;
use Symfony\Component\HttpFoundation\Request;

class ApiMovilConductorController extends ApiBaseController
{

    public function lista(Request $request)
    {
        $parametros=$this->getParameters($request);
        $em=$this->getDoctrine();
        $region=$em->getRepository('App:Configuracion\Region')->listaRegion($parametros);
        $encabezados=['id','name','descripcion','estado'];
        return $this->generateListResponse($encabezados,$region);
    }


}
