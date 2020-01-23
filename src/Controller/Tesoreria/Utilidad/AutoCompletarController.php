<?php


namespace App\Controller\Tesoreria\Utilidad;


use App\Entity\RecursoHumano\RhuEmpleado;
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

    /**
     * @Route("/api/recursohumano/autocompletar/empleado", name="recursohumanoAutocompletarEmpleado")
     */
    public function autocompletarBuscarEmpleado(Request $request){
        $em = $this->getDoctrine()->getManager();
        $dataRaw = $request->request->get('rawText');
        $raw = ['limiteRegistros'=>10];
        if ($dataRaw != ""){
            $raw['filtros'] = [
                'nombreCorto'=>$dataRaw,
                'numeroIdentificacion'=>$dataRaw,
            ];
            $arEmpleados = $em->getRepository(RhuEmpleado::class)->autoCompletar($raw);

            if (count($arEmpleados)>0){
                $jsonEmpleado = new JsonResponse($arEmpleados);
                return $jsonEmpleado;
            }
        }
    }
}