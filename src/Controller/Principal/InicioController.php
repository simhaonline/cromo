<?php

namespace App\Controller\Principal;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenTarea;
use App\Form\Type\General\TareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="inicio")
     */
    public function inicio(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arTarea = new GenTarea();
            $arTarea->setFecha(new \DateTime('now'));

        $form = $this->createForm(TareaType::class, $arTarea);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arTarea->setUsuarioAsigna($this->getUser()->getUsername());
            $em->persist($arTarea);
            $em->flush();
            return $this->redirect($this->generateUrl('inicio'));
        }
        $arTareas = $em->getRepository(GenTarea::class)->lista($this->getUser()->getUsername());
        return $this->render('principal/inicio.html.twig',[
            'arTareas' => $arTareas,
            'form' => $form->createView()
        ]);
        /*return $this->render('v2/general/inicio.html.twig',[
            'arTareas' => $arTareas,
            'form' => $form->createView()
        ]);*/
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/tarea/terminar", name="tarea_terminar")
     */
    public function terminarTarea(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $arTarea = $em->find(GenTarea::class,$id);
        $arTarea->setEstadoTerminado(1);
        $em->persist($arTarea);
        $em->flush();
        return new JsonResponse(true);
    }
}

