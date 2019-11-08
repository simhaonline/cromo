<?php


namespace App\Controller\Tesoreria\Utilidades;


use App\Entity\Tesoreria\TesTercero;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AutoCompletarController extends  AbstractController
{
    /**
     * @Route("/api/tesoreria/autocompletarTercero", name="tesoreriaAutocompletarTercero")
     */
    public function autocompletarTercero(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dataRaw = $request->query->get('rawText');
        $raw = ['limiteRegistros'=>10];
        if ($dataRaw != ""){
            $raw['filtros'] = [
                'nombreCorto'=>$dataRaw,
                'numeroIdentificacion'=>$dataRaw,
            ];
            $arTerceros = $em->getRepository(TesTercero::class)->autoCompletar($raw);
            if (count($arTerceros)>0){
              $jsonArTerceros = new JsonResponse($arTerceros);
              return $jsonArTerceros;
          }
        }
    }
}