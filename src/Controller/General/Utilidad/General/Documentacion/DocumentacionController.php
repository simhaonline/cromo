<?php

namespace App\Controller\General\Utilidad\General\Documentacion;

use App\Entity\General\GenConfiguracion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DocumentacionController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/documentacion/buscar", name="documentacion_buscar")
     */
    public function buscar(Request $request)
    {
        $arTemas = [];
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class,1);
        $form = $this->createFormBuilder()
            ->add('choBusquedaTipo',ChoiceType::class,[
                'choices' => ['TODOS' => 'TOD','TITULO' => 'TIT','DESCRIPCION' => 'DES' ],
                'attr' => ['class' => 'btn btn-default dropdown-toggle']
            ])
            ->add('txtBusqueda',TextType::class,['required' => true,'attr' => ['class' => 'input-lg']])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $arrDatos = json_encode([
                'busqueda' => $form->get('txtBusqueda')->getData(),
                'tipoBusqueda' => $form->get('choBusquedaTipo')->getData()
            ]);
            $ch = curl_init($arConfiguracion->getWebServiceCesioUrl().'/api/documentacion/buscar');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($arrDatos))
            );
            $arTemas = json_decode(curl_exec($ch));
        }
        return $this->render('general/utilidad/general/documentacion/buscar.html.twig',[
            'arTemas' => $arTemas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/documentacion/calificar", name="documentacion_calificar")
     */
    public function calificar(Request $request){
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class,1);
        $arrCalificacion = explode('-',$request->query->get('calificacion'));
        $arrDatos['id'] = $arrCalificacion[1];
        $arrDatos['calificacion'] = $arrCalificacion[2];
        $arrDatos = json_encode($arrDatos);
        $ch = curl_init($arConfiguracion->getWebServiceCesioUrl().'/api/documentacion/calificar');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($arrDatos))
        );
        $respuesta = json_decode(curl_exec($ch));
        return new JsonResponse($respuesta);
    }

//    /**
//     * @Route("/documentacion/video/{url}", name="documentacion_video")
//     */
//    public function modalVideo($url)
//    {
//        return $this->render('general/utilidad/general/documentacion/video.html.twig', [
//            'url' => str_replace('&','/',$url),
//        ]);
//    }
}

