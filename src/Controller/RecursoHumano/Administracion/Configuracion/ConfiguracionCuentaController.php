<?php

namespace App\Controller\RecursoHumano\Administracion\Configuracion;


use App\Entity\RecursoHumano\RhuConfiguracionProvision;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ConfiguracionCuentaController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recursohumano/administracion/configuracion/configuracionprovision/lista", name="recursohumano_administracion_configuracion_configuracionprovision_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnActualizar', SubmitType::class, array('label' => 'Actualizar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnActualizar')->isClicked()) {
                $arrCuentas = $request->request->all();
                $error = $em->getRepository(RhuConfiguracionProvision::class)->actualizarCuentas($arrCuentas);
                if($error == true){
                    Mensajes::error("Se eliminaron las cuentas que no existen en el plan de cuentas");
                }
            }
        }
        $arConfiguracionProvisiones = $em->getRepository(RhuConfiguracionProvision::class)->lista();
        return $this->render('recursohumano/administracion/configuracion/configuracionProvision.html.twig', [
            'arConfiguracionProvisiones' => $arConfiguracionProvisiones,
            'form' => $form->createView()
        ]);
    }
}