<?php

namespace App\Controller\General\Administracion\NotificacionTipo;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\General\GenModulo;
use App\Entity\General\GenNotificacionTipo;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificacionTipoController extends MaestroController
{


    public $tipo = "administracion";
    public $modelo = "GenNotificacionTipo";

    protected $clase = GenNotificacionTipo::class;
    protected $claseNombre = "GenNotificacionTipo";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "NotificacionTipo";
    protected $nombre = "NotificacionTipo";

    /**
     * @Route("/general/administracion/notificaciontipo/lista", name="general_administracion_notificacion_tipo_lista")
     */
    public function lista(Request $request, TokenStorageInterface $user){
        $em=$this->getDoctrine()->getManager();
        $usuario=$user->getToken()->getUser();
        $session=new Session();
        $form=$this->createFormBuilder()
            ->add('cbFiltroModulo', EntityType::class, array(
                'class' => GenModulo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.codigoModuloPk', 'ASC');
                },
                'choice_label' => 'codigoModuloPk',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => $session->get('arGenNotificacionTipoFiltroModulo')||""
            ))
            ->add('cbFiltroModelo', ChoiceType::class, array(
                'required'=>false,
                'placeholder' => "TODOS",
            ))
            ->add('btnFiltrar',SubmitType::class, ['label' => 'Filtrar'])
            ->add('btnPrueba',SubmitType::class,['label'=>'Prueba'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->get('btnFiltrar')->isClicked()) {
            $arModeloSelect=$request->request->get('form');
            $arModeloSelect=$arModeloSelect['cbFiltroModelo'];
            $session->set('arGenNotificacionTipoFiltroModulo', $form->get('cbFiltroModulo')->getData());
            $session->set('arGenNotificacionTipoFiltroModelo', $arModeloSelect);
        }
        if($form->get('btnPrueba')->isClicked()){
            FuncionesController::crearNotificacion(1, "descripcion de la prueba", null);
            return $this->redirectToRoute('general_administracion_notificacion_tipo_lista');
        }
        $arNotificacionTipo=$em->getRepository('App:General\GenNotificacionTipo')->lista();

        return $this->render('general/administracion/notificacion_tipo/notificacion_tipo/lista.html.twig',[
            'form'=>$form->createView(),
            'arNotificaionTipo'=>$arNotificacionTipo,
            'arModeloSelect'=>$arModeloSelect??""
        ]);
    }

    /**
     * @Route("/general/administracion/notificaciontipo/editar/{codigoNotificacion}", name="general_administracion_notificacion_tipo__editar")
     */
    public function editar(Request $request, TokenStorageInterface $user, $codigoNotificacion)
    {
        $em=$this->getDoctrine()->getManager();
        $session = new Session();
        $session->set('arGenNotificacionTipoNombreUsuario', null);
        $usuario=$user->getToken()->getUser();
        $arNotificacionTipo = $em->getRepository(GenNotificacionTipo::class)->find($codigoNotificacion);
        $form = $this->createFormBuilder()
            ->add('txtNombreUsuario', TextType::class, array('required'=>false))
            ->add('estadoActivo', CheckboxType::class,  ['required' => false, 'data' => $arNotificacionTipo->getEstadoActivo()])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('arGenNotificacionTipoNombreUsuario', $form->get('txtNombreUsuario')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $usuarios=[];
                if ($arrSeleccionados) {
                    $usuariosExistentes=true;
                    foreach ($arrSeleccionados as $codigoUsuario) {
                        $arUsuarioValidar = $em->getRepository('App:Seguridad\Usuario')->find($codigoUsuario);
                        if(!$arUsuarioValidar){
                            $usuariosExistentes=false;
                            Mensajes::error("Existe error con el usuario");
                        }
                        array_push($usuarios,$arUsuarioValidar->getUsername());

                    }
                    if ($usuariosExistentes) {

                        $arNotificacionTipo = $em->getRepository('App:General\GenNotificacionTipo')->find($codigoNotificacion);
                        $arNotificacionTipo->setEstadoActivo($form->get('estadoActivo')->getData());
                        $arNotificacionTipo->setUsuarios($usuarios?json_encode($usuarios):null);
                        $em->persist($arNotificacionTipo);
                    }
                }
                else{
                    $arNotificacionTipo = $em->getRepository('App:General\GenNotificacionTipo')->find($codigoNotificacion);
                    $arNotificacionTipo->setEstadoActivo($form->get('estadoActivo')->getData());
                    $arNotificacionTipo->setUsuarios($usuarios?json_encode($usuarios):null);
                    $em->persist($arNotificacionTipo);
                }

                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                $session->set('arGenNotificacionTipoNombreUsuario', null);
            }
        }
        $arUsuarioNotificacion=$em->getRepository('App:General\GenNotificacionTipo')->find($codigoNotificacion)->getUsuarios();
        $arUsuarioNotificacion=json_decode($arUsuarioNotificacion);
        $arUsuario=$em->getRepository('App:General\GenNotificacionTipo')->listaUsuarios($usuario->getUsername());
        return $this->render('general/administracion/notificacion_tipo/notificacion_tipo/nuevo.html.twig',[
            'form'=>$form->createView(),
            'arUsuario'=>$arUsuario,
            'arUsuarioNotificacion'=>$arUsuarioNotificacion,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/comboxDependienteModelo", name="general_administracion_notificacion_tipo__comboDependiente")
     */
    public function comboxDependienteModelo(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $modulo = $request->query->get('id');

        return new JsonResponse($em->getRepository('App:General\GenModelo')->modeloXModulo($modulo));

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/cambiarEstadoNotificacion", name="general_administracion_notificacion_tipo__cambiarEstadoNotificacion")
     */
    public function cambiarEstadoNotificacion(Request $request){
        $em = $this->getDoctrine()->getManager();
        $codigoNotificacionTipo=$request->query->get('id');
        try{
            $arNotificacionTipo=$em->getRepository('App:General\GenNotificacionTipo')->find($codigoNotificacionTipo);
            $arNotificacionTipo->setEstadoActivo(!$arNotificacionTipo->getEstadoActivo());
            $em->persist($arNotificacionTipo);
            $em->flush();
            return new JsonResponse(true);
        }catch (\Exception $exception){
            return new JsonResponse(false);
        }
    }


}
