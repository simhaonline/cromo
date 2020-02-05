<?php

namespace App\Controller\General\Seguridad;

use App\Entity\General\GenModulo;
use App\Entity\General\GenProcesoTipo;
use App\Entity\Seguridad\SegUsuarioProceso;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SeguridadUsuarioProcesoController extends AbstractController
{


    /**
     * @Route("/gen/seguridad/usuario/proceso/nuevo/{hash}", name="general_seguridad_usuario_proceso_nuevo")
     */
    public function nuevo(Request $request, $hash){
        $em=$this->getDoctrine()->getManager();
        $session=new Session();
        $form = $this->createFormBuilder()
            ->add('cboModulo', EntityType::class, array(
                'class' => GenModulo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.codigoModuloPk', 'ASC');
                },
                'choice_label' => 'codigoModuloPk',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => $session->get('arSeguridadUsuarioProcesofiltroModulo')||""
            ))
            ->add('cboTipoProceso', EntityType::class, array(
                'class' => GenProcesoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => $session->get('arSeguridadUsuarioProcesofiltroProcesoTipo')||""
            ))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);

        //eliminar variables de session solo cuando se cierre la pestaña

        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('arSeguridadUsuarioProcesofiltroModulo',$form->get('cboModulo')->getData());
            $session->set('arSeguridadUsuarioProcesofiltroProcesoTipo',$form->get('cboTipoProceso')->getData());
        }

        if($form->get('btnGuardar')->isClicked()) {
            $id = $this->verificarUsuario($hash);
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($id != "") {
                $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            }
            if ($arrSeleccionados) {
                foreach ($arrSeleccionados as $codigoProceso) {
                    $arGenProcesoValidar = $em->getRepository('App:General\GenProceso')->find($codigoProceso);
                    if ($arGenProcesoValidar && $arUsuario) {
                        $arSegUsuarioProceso=$em->getRepository('App:Seguridad\SegUsuarioProceso')->findOneBy(['codigoUsuarioFk'=>$arUsuario->getUsername(),'codigoProcesoFk'=>$arGenProcesoValidar->getCodigoProcesoPk()]);
                        if(!$arSegUsuarioProceso) {
                            $arSegUsuarioProceso = (new SegUsuarioProceso());
                            $arSegUsuarioProceso->setCodigoUsuarioFk($arUsuario->getUsername());
                            $arSegUsuarioProceso->setCodigoProcesoFk($arGenProcesoValidar->getCodigoProcesoPk());

                            $em->persist($arSegUsuarioProceso);
                        }
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                $session->set('arSeguridadUsuarioProcesofiltroModulo',null);
                $session->set('arSeguridadUsuarioProcesofiltroProcesoTipo',null);
            }
            else{
                Mensajes::error("No selecciono ningun dato para grabar");
            }
        }
        if(!$form->get('btnGuardar')->isClicked() && !$form->get('btnFiltrar')->isClicked()) {
            $session->set('arSeguridadUsuarioProcesofiltroModulo', null);
            $session->set('arSeguridadUsuarioProcesofiltroProcesoTipo', null);
        }
        $arGenProceso=$em->getRepository('App:General\GenProceso')->lista();
        return $this->render('general/seguridad/seguridad_usuario_proceso/nuevo.html.twig', [
            'form'          =>  $form->createView(),
            'arGenProceso'   =>  $arGenProceso,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/gen/seguridad/usuario/proceso/editar", name="general_seguridad_usuario_proceso_editar")
     */
    public function editar(Request $request){
        $em = $this->getDoctrine()->getManager();
        $codigoSegUsuarioProceso=$request->query->get('id');
        try{
            $arSegUsuarioProceso=$em->getRepository('App:Seguridad\SegUsuarioProceso')->find($codigoSegUsuarioProceso);
            $arSegUsuarioProceso->setIngreso(!$arSegUsuarioProceso->getIngreso());
            $em->persist($arSegUsuarioProceso);
            $em->flush();
            return new JsonResponse(true);
        }catch (\Exception $exception){
            return new JsonResponse(false);
        }
    }

    private function verificarUsuario($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $id = 0;
        if ($hash != '0') {
            $arUsuarios = $em->getRepository('App:Seguridad\Usuario')->findAll();
            if (count($arUsuarios) > 0) {
                $hash = str_replace('&', '/', $hash);
                foreach ($arUsuarios as $arUsuario) {
                    if (password_verify($arUsuario->getUsername(), $hash)) {
                        $id = $arUsuario->getUsername();
                    }
                }
            }
        }
        return $id;
    }
}
