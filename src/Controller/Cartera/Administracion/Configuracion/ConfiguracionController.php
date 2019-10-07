<?php

namespace App\Controller\Cartera\Administracion\Configuracion;



use App\Entity\Cartera\CarConfiguracion;
use App\Form\Type\Cartera\ConfiguracionType;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfiguracionController extends AbstractController
{

    /**
     * @Route("/cartera/administracion/configuracion/configuracion/lista/{id}", name="cartera_administracion_configuracion_configuracion_lista")
     */
    public function nuevo(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository(CarConfiguracion::class)->find(1);
        $form = $this->createForm(ConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arConfiguracion);
                $em->flush();
                Mensajes::info('Se guardo la configuracion con exito');
            }
        }
        return $this->render('cartera/administracion/configuracion/configuracion.html.twig', [
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView()
        ]);
    }

}

