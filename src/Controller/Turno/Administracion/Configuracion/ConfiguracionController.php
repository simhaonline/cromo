<?php
namespace App\Controller\Turno\Administracion\Configuracion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurConfiguracion;
use App\Form\Type\Turno\ConceptoType;
use App\Form\Type\Turno\ConfiguracionType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfiguracionController extends AbstractController
{
    /**
     * @param Request $request
     * @param $codigoConfiguracion integer
     * @Route("/turno/administracion/configuracion/configuracion/nuevo/{codigoConfiguracion}", name="turno_administracion_configuracion_configuracion_nuevo")
     */
    public function nuevo(Request $request, $codigoConfiguracion)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository(TurConfiguracion::class)->find($codigoConfiguracion);

        $form = $this->createForm(ConfiguracionType::class, $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arConfiguracion = $form->getData();
                $em->persist($arConfiguracion);
                $em->flush();
            }
        }

        return $this->render('turno/administracion/configuracion/configuracion.html.twig', [
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView(),
        ]);
    }
}