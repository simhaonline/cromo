<?php

namespace App\Controller\General\Utilidad\General\Reportar;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReportarController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/general/utilidad/general/reportar/lista/{ruta}", name="general_utilidad_general_reportar_lista")
     */
    public function nuevo(Request $request, $ruta)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('ruta', TextType::class, ['required' => true])
            ->add('descripcion',TextareaType::class,['required' => true])
            ->add('')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('general/utilidad/general/reportar/nuevo.html.twig',[

        ]);
    }

    public function listadoAreas()
    {
        // Get cURL resource
        $em = $this->getDoctrine()->getManager(); // instancia el entity manager
        $serviceUrl = $em->getRepository('App:General\GenConfiguracion')->getUrl();
        // Get cURL resource
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $serviceUrl . 'area/lista',
        ));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        return $resp;
    }

    public function listadoCargos()
    {
        $em = $this->getDoctrine()->getManager();
        $serviceUrl = $em->getRepository('App:Configuracion')->getUrl();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => 'https://my-json-server.typicode.com/Avera123/jsonserver/usuarios',
            CURLOPT_URL => $serviceUrl . 'cargo/lista',
        ));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        return $resp;
    }

    public function listadoPrioridad()
    {
        $em = $this->getDoctrine()->getManager(); // instancia el entity manager
        $serviceUrl = $em->getRepository('App:Configuracion')->getUrl();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => 'https://my-json-server.typicode.com/Avera123/jsonserver/usuarios',
            CURLOPT_URL => $serviceUrl . 'prioridad/lista',
        ));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        return $resp;
    }

    public function listadoCategoriaCasos()
    {
        $em = $this->getDoctrine()->getManager(); // instancia el entity manager
        $serviceUrl = $em->getRepository('App:Configuracion')->getUrl();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => 'https://my-json-server.typicode.com/Avera123/jsonserver/usuarios',
            CURLOPT_URL => $serviceUrl . 'caso/lista/categoria',
        ));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        return $resp;
    }

}

