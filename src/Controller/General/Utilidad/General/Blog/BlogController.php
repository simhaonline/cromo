<?php

namespace App\Controller\General\Utilidad\General\Blog;

use App\Entity\General\GenConfiguracion;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/blog/post", name="blog_post")
     */
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->find(GenConfiguracion::class,1);
        $form = $this->createFormBuilder()
            ->add('txtBusqueda',TextType::class,['required' => false])
            ->getForm();
        $form->handleRequest($request);
        $ch = curl_init($arConfiguracion->getWebServiceCesioUrl().'/api/contenido/post');
        if($form->isSubmitted()){
            $arrDatos['busqueda'] = $form->get('txtBusqueda')->getData();
        } else {
            $arrDatos['busqueda'] = '';
        }
        $arrDatos = json_encode($arrDatos);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($arrDatos))
        );
        $arPosts = json_decode(curl_exec($ch));

        return $this->render('general/utilidad/general/blog/inicio.html.twig',[
            'arPosts' => $arPosts,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/blog/descripcion", name="blog_descripcion")
     */
    public function consultarDescripcion(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $arConfiguracion = $em->find(GenConfiguracion::class,1);
        $ch = curl_init($arConfiguracion->getWebServiceCesioUrl().'/api/contenido/post/descripcion');
        $arrDatos = json_encode(['id' => $id]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrDatos);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($arrDatos))
        );
        $descripcion = curl_exec($ch);
        return new JsonResponse($descripcion);
    }
}

