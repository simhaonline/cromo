<?php

namespace App\Controller\General\Administracion\NotificacionTipo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerPermisosFunciones;
use App\Entity\General\GenModelo;
use App\Entity\General\GenModulo;
use App\Entity\General\GenNotificacionTipo;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificacionTipoController extends BaseController
{
    protected $clase = GenNotificacionTipo::class;
    protected $claseNombre = "GenNotificacionTipo";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "NotificacionTipo";
    protected $nombre = "NotificacionTipo";

    /**
     * @Route("/general/administracion/notificaciontipo/nuevaNotificacionTipo/lista", name="general_administracion_notificacion_tipo_nuevaNotificacionTipo_lista")
     */
    public function lista(Request $request){
        $em=$this->getDoctrine()->getManager();
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
            ->add('cbFiltroModelo', EntityType::class, array(
                'class' => GenModelo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('mo')
                        ->andWhere("mo.codigoModuloFk='Financiero'")
                        ->orderBy('mo.codigoModeloPk', 'ASC');
                },
                '',
                'choice_label' => 'codigoModeloPk',
                'required' => true,
                'data_class' => null,
                'data' => "",
            ))
            ->getForm();
        $form->handleRequest($request);
        $arNotificacionTipo=$em->getRepository('App:General\GenNotificacionTipo')->lista();

        return $this->render('general/administracion/notificacion_tipo/notificacion_tipo/lista.html.twig',[
            'form'=>$form->createView(),
            'arNotificaionTipo'=>$arNotificacionTipo,
        ]);
    }

    /**
     * @Route("/general/administracion/notificaciontipo/nuevaNotificacionTipo/nuevo", name="general_administracion_notificacion_tipo_nuevaNotificacionTipo_nuevo")
     */
    public function nuevo(Request $request, TokenStorageInterface $user)
    {
        $em=$this->getDoctrine()->getManager();
        $session = new Session();
        $usuario=$user->getToken()->getUser();
        $form = $this->createFormBuilder()
            ->add('txtNombreUsuario', TextType::class, array('required'=>false))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')) {
                $session->set('arGenNotificacionTipoNombreUsuario', $form->get('txtNombreUsuario')->getData());
            }
            if ($form->get('btnGuardar')) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    $usuariosExistentes=true;
                    $usuarios=[];
                    foreach ($arrSeleccionados as $codigoUsuario) {
                        $arUsuarioValidar = $em->getRepository('App:Seguridad\Usuario')->find($codigoUsuario);
                        if(!$arUsuarioValidar){
                            $usuariosExistentes=false;
                            Mensajes::error("Existe error con el usuario");
                        }
                        array_push($usuarios,$arUsuarioValidar->getUsername());

                    }
                    if ($usuariosExistentes) {

                        $arNotificacionTipo = $em->getRepository('App:General\GenNotificacionTipo')->find(3);
                        $arNotificacionTipo
                            ->setUsuarios($usuarios?json_encode($usuarios):null);
                            $em->persist($arNotificacionTipo);
                        }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    $session->set('arGenNotificacionTipoNombreUsuario', null);
                }
            }
        }
        $arUsuario=$em->getRepository('App:General\GenNotificacionTipo')->listaUsuarios($usuario->getId());
        return $this->render('general/administracion/notificacion_tipo/notificacion_tipo/nuevo.html.twig',[
           'form'=>$form->createView(),
            'arUsuario'=>$arUsuario,
        ]);
    }
}
